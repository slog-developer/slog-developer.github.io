<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member = new Member; 
	$C_funding	= new Funding;
	
	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("로그인이 필요합니다.", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}

	if($_SESSION["SESSION_FUNDING_IDX"] == "" || $_SESSION["SESSION_INVEST_CNT"] == "" || $_SESSION["SESSION_INVEST_TOTAL"] == "" || $_SESSION["SESSION_STOCK_EXCHANGE"] == "" )
	{
		execJava("", "", "location.href='/'");
	}

	$funding_idx = $_SESSION["SESSION_FUNDING_IDX"];
	
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
		$funding_str_1 = "주";
	}
	else
	{
		$funding_str_1 = "구좌";
	}

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>
</head>
 <body>
<form name="request" method="post" action="bankpay.php" target='INVISIBLE' accept-charset="euc-kr">
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
						<div class="funding_contents_v3">
							<div class="add_tit_input_section">
								<div class="input_tit"><span>주식 수량</span></div>
								<div class="fs_input_txt_v2 mgb10"><h1 class="floatR pdr20"><?=$_SESSION["SESSION_INVEST_CNT"]?><?=$funding_str_1?></h1></div>

								<div class="input_tit"><span>총 투자 금액</span></div>
								<div class="fs_input_v8 mgb10">
									<div class="funding_num floatR"><p class="mgr10"><?=number_format($_SESSION["SESSION_INVEST_TOTAL"])?></p><span>원</span></div>
								</div>								
							</div>
							<div class="terms_sub_tit">
								<span>개인정보 처리방침</span>
								<span><a href="#">크게보기</a></span>
								<div class="terms_tit_check floatR">
									<input class="checkbox" type="checkbox" id="agree_v2" name="agree_v2" value="1" />
									<label for="agree_v2"></label>
									<label for="agree_v2"></label><span class="save_id_text">동의</span>
								</div>
							</div>
							<div class="terms_contents_v2">
								<div class="terms_text">
									개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침
									개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침
									개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침
									개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침
									개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침
									개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침개인정보 처리방침
								</div>
								<div class="gradient_v5">
								</div>
							</div>
						</div>
					</div><!-- funding_step_wrap end -->
					<div class="funding_btn_form">
						<button type="button" class="fs_full_btn" id='btn_next'>투자금 입금</button>
					</div>
				</div><!-- tabs_container end -->
			</div>
		</div> 
	</div>
	<!-- container end -->
	<div class="modal_bg">
		<div class="modal_body">
			<div class="modal_contents">
				<div class="modal_text">					
					<p><b>SUCCESTOCK 이용약관</b><br />
					본 약관은 ㈜스마트 시큐리티(이하 “회사“라고 함)가 운영하는 인터넷 사이트(이하 “사이트＂라고 함)에서 제공하는 모든 서비스(이하 “서비스＂라고 함)를 이용함에 있어 “회사＂와 회원 간의 권리와 의무 및 책임사항에 관한 사항을 규정함을 목적으로 합니다.</p>					
					<p><b>목차</b><br />
					제 1조 약관의 적용<br />
					제 2조 용어의 정의<br />
					제 3조 서비스 종류<br />
					제 4조 서비스 이용계약의 성립 등<br />
					제 5조 회원가입<br />
					제 6조 회원정보의 수집과 보호<br />
					제 7조 회원의 의무<br />
					제 8조 서비스 이용권리의 양도 등<br />
					제 9조 계약해지 및 이용제한<br />
					제 10조 전자메일에 대한 회원의 의무와 책임<br />
					제 11조 게시물 등의 관리</p>	
					<p>제 1조 약관의 적용<br />
					제 2조 용어의 정의<br />
					제 3조 서비스 종류<br />
					제 4조 서비스 이용계약의 성립 등<br />
					제 5조 회원가입<br />
					제 6조 회원정보의 수집과 보호<br />
					제 7조 회원의 의무<br />
					제 8조 서비스 이용권리의 양도 등<br />
					제 9조 계약해지 및 이용제한<br />
					제 10조 전자메일에 대한 회원의 의무와 책임<br />
					제 11조 게시물 등의 관리</p>
					<p>제 1조 약관의 적용<br />
					제 2조 용어의 정의<br />
					제 3조 서비스 종류<br />
					제 4조 서비스 이용계약의 성립 등<br />
					제 5조 회원가입<br />
					제 6조 회원정보의 수집과 보호<br />
					제 7조 회원의 의무<br />
					제 8조 서비스 이용권리의 양도 등<br />
					제 9조 계약해지 및 이용제한<br />
					제 10조 전자메일에 대한 회원의 의무와 책임<br />
					제 11조 게시물 등의 관리</p>
					<div class="gradient_v3"></div>
				</div>
			</div>
			<div class="modal_button_wrap">
				<button type="button" class="modal_agree" id="btn_agree_pop">동의하기</button>
				<button type="button" class="modal_close">닫기</button>
			</div>
		</div>
	</div>
	<!-- footer -->
	<div id="footer"><!-- 푸터링크 영역 및 푸터컨텐츠 영역 -->
		<? include_once FX_SITE."/std/inc/php/footer.php" ?>
	</div>
	<!-- footer end -->
  </div>
 <!-- wrap end -->

 <!--bank pay-->
<input type="hidden" name="hd_approve_no" value="15201418"/>
<?
	//거래번호 생성	
	$times= date("s", time());  // 초 -> 년-월-일 시:분:초  변환
	$hd_serial_no = $user_info['mem_idx'].str_replace(":", "", $times);   
	
	if(strlen($hd_serial_no) == 3)
	{
		$hd_serial_no = "0000".$hd_serial_no;
	}
	
	if(strlen($hd_serial_no) == 4)
	{
		$hd_serial_no = "000".$hd_serial_no;
	}
	
	if(strlen($hd_serial_no) == 5)
	{
		$hd_serial_no = "00".$hd_serial_no;
	}

	if(strlen($hd_serial_no) == 6)
	{
		$hd_serial_no = "0".$hd_serial_no;
	}
	
	if(strlen($hd_serial_no) > 7)
	{
		$hd_serial_no = substr($hd_serial_no,0,7);
	}

	$hd_serial_no = "0000000";

	$bankpayURL = "https://pgtest.kftc.or.kr:7743/StartBankPay.do";  //real : https://www.bankpay.or.kr:7443/StartBankPay.do
?>
<input type="hidden" name="hd_serial_no" value="<?=$hd_serial_no?>"/> 
<input type="hidden" name="hd_firm_name" value="SUCCESTOCK"/>
<input type="hidden" name="hd_item_name" value="<?=$funding_view['funding_top_memo']?>"/>
<input type="hidden" name="tx_amount" value="<?=$_SESSION["SESSION_INVEST_TOTAL"]?>"/>
<input type="hidden" name="tx_bill_yn" value="Y">
<input type="hidden" name="tx_vat_yn" value="Y">
<input type="hidden" name="tx_bill_vat" value="0">
<input type="hidden" name="tx_svc_charge" value="0">
<input type="hidden" name="tx_bill_tax" value="0">
<input type="hidden" name="tx_bill_deduction" value="0">
<input type="hidden" name="tx_age_check" value="Y19">
<input type="hidden" name="returnURL" value="_pay.php"/>
<input type="hidden" name="bankpayURL" value="<?=$bankpayURL?>"/> 
 </form>
 </body>
</html>
<iframe id='INVISIBLE' name='INVISIBLE' height="600" width="720" style='display:none;' ></iframe>

<script type="text/javascript" src="/std/inc/js/_common.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		$(".terms_sub_tit>span>a").click(function(){
			$(".modal_bg").addClass("active");
		});
		$(".modal_close").click(function(){
			$(".modal_bg").removeClass("active");
		});
		
		$("#btn_agree_pop").on('click', function(e){   
			$("input:checkbox[name='agree_v2']").prop('checked', true);
			$(".modal_close").trigger('click');
		});

		$("#btn_next").on('click', function(e){   
			if(!$("input:checkbox[name='agree_v2']").is(":checked"))
			{
				alert('개인정보 처리방침 동의 해주세요.');
				return false;
			}

			//결제모듈 Open
			OnPayButtonClick();

		});
		
	});
 
	function OnPayButtonClick() {
		var form = document.request;
		if(form.canHaveHTML) { // detect IE
			document.charset = form.acceptCharset;
		}
		form.submit();
	}

	function go_step4()
	{
		location.href = 'funding_step4.php';

	}
</script>