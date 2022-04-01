<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member = new Member; 
	$C_funding	= new Funding;
	
	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("로그인이 필요합니다.", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}
	
	unset($args);
	$args['mem_idx']	= $user_info['mem_idx'];
	$args['list_type']	= "row";
	$mem_info			= $C_member->get_user_info($args);

	if($mem_info["mem_invest_type_chk"] == "0")
	{
		execJava("관리자 승인이 필요합니다.(투자자 유형확인.)", "", "history.back();");
	}
	 
	$funding_idx	= $_SESSION["SESSION_FUNDING_IDX"];
	if($funding_idx == "")
	{
		execJava("", "", "location.href='/'");
	}

	unset($args);
	$args['mem_idx']		= $user_info['mem_idx'];
	$args['funding_idx']	= $funding_idx;
	$invest_chk = $C_funding->get_funding_invest_cnt($args);

	if($invest_chk != "0")
	{
		execJava("이미 펀딩 투자한 상품입니다.", "", "history.back();");
	}

	unset($args);
	$args['list_type']		= "row";
	$args['funding_idx']	= $funding_idx;
	$funding_view = $C_funding->get_funding_issue($args);

	if($funding_view == "")
	{
		execJava("", "", "location.href='/'");
	}
		
	if ($funding_view['funding_type'] == "1")
	{
		$funding_str_1 = "주식";
		$funding_str_2 = "주";
	}
	else
	{
		$funding_str_1 = "채권";
		$funding_str_2 = "구좌";
	}
 
	//투자한도
	if($mem_info["mem_invest_type"] == "1")
	{
		//일반투자
		$invest_limit_money = 5000000;
	}
	elseif($mem_info["mem_invest_type"] == "2")
	{
		//적격투자
		$invest_limit_money = 10000000;
	}
	elseif($mem_info["mem_invest_type"] == "3")
	{
		//전문투자
		$invest_limit_money = $funding_view['remain_tot_cnt'] * $funding_view['stock_per_amount'];
	} 
	 
	//구매 가능 수량 : 주당 금액 * 수량 < 투자한도
	if($funding_view['funding_type'] == "1")
	{
		$buy_stock_cnt =  floor($invest_limit_money / $funding_view['stock_per_amount']);  
			
		if ($buy_stock_cnt > $funding_view['remain_tot_cnt'])
		{
			$invest_limit_money = $funding_view['remain_tot_cnt'] * $funding_view['stock_per_amount'];
			$buy_stock_cnt = $funding_view['remain_tot_cnt'];
		}
	}
	else
	{
		$buy_stock_cnt =  floor($invest_limit_money / $funding_view['bond_account_per_amount']); 
			
		if ($buy_stock_cnt > $funding_view['remain_tot_cnt'])
		{
			$invest_limit_money = $funding_view['remain_tot_cnt'] * $funding_view['bond_account_per_amount'];
			$buy_stock_cnt = $funding_view['remain_tot_cnt'];
		}
	}

	// invest request
	$is_request_1			= 0;
	$is_request_2			= 0;
	$is_request_3			= 0;
	$invest_cnt				= str_replace(",", "", $_POST['txt_invest_cnt']);   
	$invest_total			= str_replace(",", "", $_POST['txt_invest_total']); 
	$stock_exchange			= $_POST['ddl_stock_exchange']; 
	$stock_account			= $_POST['txt_stock_account']; 

	if($invest_cnt)
	{
		//구매가능수량 체크
		if($invest_cnt <= $buy_stock_cnt)
		{		 
			$is_request_1	= 1;
		}
		else
		{
			$is_request_1	= -1;
		}

		//투자금액 체크
		if($invest_total <= $invest_limit_money)
		{
			$is_request_2	= 1;
		}
		else
		{
			$is_request_2	= -2;
		}
		
		//최소 구매수량 체크
		if($funding_view['funding_invest_cnt'] <= $invest_cnt)
		{
			$is_request_3	= 1;
		}
		else
		{
			$is_request_3	= -3;
		}

		if($is_request_1 == "1" && $is_request_2 == "1" && $is_request_3 == "1")
		{
			$_SESSION["SESSION_INVEST_CNT"] = $invest_cnt;
			$_SESSION["SESSION_INVEST_TOTAL"] = $invest_total;
			$_SESSION["SESSION_STOCK_EXCHANGE"] = $stock_exchange;
			$_SESSION["SESSION_STOCK_ACCOUNT"] = $stock_account; 

			$rtn['err_code']	= 0; 
		}
		else
		{
			if($is_request_1 != "1")
			{
				$rtn['err_code']	= -1001;
			}
			else if($is_request_2 != "1")
			{
				$rtn['err_code']	= -1002;
			}
			else if($is_request_3 != "1")
			{
				$rtn['err_code']	= -1003;
			}
		}
		
		echo json_encode($rtn);  
		exit;
	}
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>
</head>
 <body>
<form id="frm" method="post">
 <!-- wrap -->
 <div id="wrap">
	<!-- header -->
	<div id="header">
		<? include_once FX_SITE."/std/inc/php/header.php" ?>
	</div>
	<!-- header end -->
	<!-- container -->
	<div id="container">
		<div class="fs_article">
			<div class="user_login_container">
				<div class="section_title">펀딩 투자하기</div>
				<div class="tabs_container mgb30">
					<div class="funding_step_wrap">
						<div class="funding_step_tit">
							<h1 class="mgb30">투자하기</h1>
						</div>
						<div class="funding_contents_v3 mgb40">
							<div class="add_tit_input_section">
								<div class="input_tit"><span><?=$funding_str_1?> 수량</span></div>
								<div class="fs_input_v3 mgb10">
									<input type="text" name="txt_invest_cnt" id="txt_invest_cnt" maxlength='5' class="fs_input_text Wfull input_name" placeholder="신청할 <?=$funding_str_1?> 수량을 입력하여 주세요." numberOnly_Comma />
								</div>
								<div class="input_alert mgb20"><h5>1<?=$funding_str_2?> 당 가격 ㅣ  
									<?if($funding_view['funding_type'] == "1"){?>
										<span class="purple_sub_v1 bold"><?=number_format($funding_view['stock_per_amount'])?>원</span> / 구매 가능 수량 <?=$buy_stock_cnt?>주</h5>
									<?}else{?>
										<span class="purple_sub_v1 bold"><?=number_format($funding_view['bond_account_per_amount'])?>원</span> / 구매 가능 수량 <?=$buy_stock_cnt?>구좌</h5>
									<?}?>
								</div>
								<div class="input_tit"><span>총 투자 금액</span></div>
								<div class="fs_input_v8 mgb10">
									<input type="text" name="txt_invest_total" id="txt_invest_total" maxlength='10' class="fs_input_text W90p" readOnly /><span>원</span>
								</div>
								<div class="input_tit"><span>나의 투자등급</span></div>
								<div class="fs_input_txt mgb10"><h1>일반 투자자</h1></div>
								<div class="input_tit"><span>나의 투자한도</span></div>
								<div class="fs_input_txt mgb10"><h1><?=number_format($invest_limit_money)?>원</h1></div>
								<div class="input_alert mgb20"><h5>예탁 결제원에서 조회한 회원님의 투자등급 및 한도입니다.</h5></div>								
							</div><!-- add_tit_input_section end -->					
						</div><!-- funding_contents_v3 end -->
						<div class="funding_step_tit">
							<h1 class="mgb30">증권계좌 등록</h1>
						</div>
						<div class="funding_contents_v4 mgb20">
							<div class="add_tit_input_section">
								<div class="input_tit"><span>증권사명</span></div>
								<div class="fs_input_v3 mgb10">
									<select class="ph_box W90p H35" id="ddl_stock_exchange" name="ddl_stock_exchange">
										<option value="">===선택해주세요===</option>
										<option value="키움증권" >키움증권</option>
										<option value="현대증권">현대증권</option>
										<option value="한국투자증권">한국투자증권</option>
									</select>
								</div>
								<div class="input_tit"><span>계좌번호</span></div>
								<div class="fs_input_v3 mgb10">
									<input type="text" name="txt_stock_account" id="txt_stock_account" class="fs_input_text Wfull input_name" placeholder="계좌번호 입력" numberOnly maxlength="10" />
								</div>
								<div class="input_alert mgb20"><h5>증권계좌는 반드시 회원님의 본인명의 계좌만 등록 가능합니다.</h5></div>
							</div><!-- add_tit_input_section end -->
						</div><!-- funding_contents_v3 end -->											
					</div><!-- funding_step_wrap end -->
					<div class="funding_btn_form">
						<button type="button" class="fs_full_btn" id="btn_next">다음</button>
					</div>
				</div><!-- tabs_container end -->
			</div>
		</div> 
	</div>
	<!-- container end -->	
	<!-- footer -->
	<div id="footer"><!-- 푸터링크 영역 및 푸터컨텐츠 영역 -->
		<? include_once FX_SITE."/std/inc/php/footer.php" ?>
	</div>
	<!-- footer end -->
  </div>
 <!-- wrap end -->
 </form>
 </body>
</html>

<script type="text/javascript" src="/std/inc/js/_common.js"></script>
<script>
$(document).ready(function(){
	 
	$("#txt_invest_cnt").keyup(function() { 
		
		if(uncomma($("#txt_invest_cnt").val()) > <?=$buy_stock_cnt?>)
		{
			alert('구매 가능 수량 보다 초과 하였습니다.');
			$("#txt_invest_cnt").val('');
			$("#txt_invest_total").val('');
			return false;
		}

		<?if($funding_view['funding_type'] == "1"){?>
			var invest_money = uncomma($("#txt_invest_cnt").val()) * <?=$funding_view['stock_per_amount']?>;
		<?}else{?>
			var invest_money = uncomma($("#txt_invest_cnt").val()) * <?=$funding_view['bond_account_per_amount']?>;
		<?}?>

		$("#txt_invest_total").val(comma(invest_money)); 
	});

	$("#btn_next").on('click', function(e){    

		if(!$("#txt_invest_cnt").val() )
		{
			alert('구매 수량을 입력해주세요.');
			$("#txt_invest_cnt").focus(); 
			return false;
		}
		
		if($("#txt_invest_cnt").val() < <?=$funding_view['funding_invest_cnt']?>)
		{
			alert("최소 구매 수량은 <?=$funding_view['funding_invest_cnt']?>주 입니다.");
			return false;
		}

		if(!$("#ddl_stock_exchange").val() )
		{
			alert('증권사를 선택해주세요.');
			$("#ddl_stock_exchange").focus(); 
			return false;
		}

		if(!$("#txt_stock_account").val() )
		{
			alert('계좌번호를 입력 해주세요.');
			$("#txt_stock_account").focus(); 
			return false;
		}
		
		var str_option = $("#frm").serialize();
		$.ajax({
			url: "/std/funding/funding_step2.php",
			data: str_option,
			type: 'POST',
			dataType: 'json',
			traditional: true,
			timeout: 60000,
			error: function (request, status, error) {
			},
			success: function (response) {
				
				if(response.err_code == 0)
				{
					location.href = 'funding_step3.php';
				} 
			}
		}); 

	}); 

}); 
</script>