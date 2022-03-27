<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";
	include_once FX_PATH."/CLASS/KSFCCipher.php"; 

	$C_member = new Member; 
	$C_funding	= new Funding;
	
	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("로그인이 필요합니다.", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}

	$tab_click_2 = " white_bg noBo";

	$start_dt	= v3chk($_REQUEST['s_datepicker'], "", 10);
	$end_dt		= v3chk($_REQUEST['e_datepicker'], "", 10);
	$page		= v3chk($_REQUEST['page'], "int");
	$orders		= v3chk($_REQUEST['ddl_search_orderby'], "", 10); 

	$invest_idx		= v3chk($_REQUEST['hid_invest_idx'], "int");

	if($orders == "")
	{
		$orders = "regdate";
	}

	if($invest_idx != "")
	{ 
		unset($data_args);
		$data_args['list_type']		= "row";
		$data_args['mem_idx']		= $user_info['mem_idx'];
		$data_args['invest_idx']	= $invest_idx;
		$data_view	= $C_funding->get_funding_invest_info($data_args);

		$funding_idx	= $data_view['funding_idx'];
		$invest_cnt		= $data_view['invest_cnt']; 
		$cancelAmt		= $data_view['invest_money'];
		
		$trIdnId		= $data_view['trIdnId'];
		$moid			= $data_view['moid'];
		$sbscIssNo		= $data_view['sbscIssNo'];
		$invest_regdate = str_replace("-","",substr($data_view['invest_regdate'], 0,10));
		$chkDt			= date_create('now')->format('Ymd');

		/*
			실시간 계좌이체 취소 API - 당일취소 : /cancelBankpay  익일취소 : /refundBankpay  
		*/
		$payCd = "01"; //결제수단 ("01":실시간계좌이체)
		$reagCd	= "1011490000310";
		$reagLnsKy	= "MTM5MjMwMjU5ODgyNjI3Nzk2ODAzMzIzMTg1OTcxNDY=";
		$reqDt	= date_create('now')->format('Ymd');
		$reqTm	= date_create('now')->format('his'); 
		
		$cipher = new KSFCCipher($reagLnsKy);  

		if($chkDt == $invest_regdate)
		{
			$svcCd = "0420";
			$endPoint = "/cancelBankpay";	
		}
		else
		{
			$svcCd = "0400";
			$endPoint = "/refundBankpay";	
		}

		$cancelRsn	= "02";
		$requestParam = array(
			"svcCd"=>$svcCd,						//전문구분 "0200"
			"payCd"=>$payCd,						//결제수단 ("01":실시간계좌이체)
			"reagCd"=>$reagCd,						//중개사 ID
			"reagLnsKy"=>$reagLnsKy,				//라이센스키
			"sbscIssNo"=>$sbscIssNo,				//청약종목번호
			"trIdnId"=>$trIdnId,					//실시간계좌이체 결제시 수신받은 거래고유번호 입력
			"moid"=>$moid,							//사용자 임의 식별번호(중개사에서 생성한 거래번호)
			"payDt"=>$invest_regdate,				//원거래일자 (YYYYMMDD)  실시간계좌이체 결제시 결제일자 입력
			"cancelRsn"=>$cancelRsn,				//취소사유 ("01":과오입금분 반환, "02":투자자의 청약의사 철회, "03":초과청약증거금 반환,"04":발행인의 발행 철회,"05": 모집예정금액 미달 시 발행 취소,"06":발행인의 부도,파산 등으로 인한 발행 취소)
			"cancelAmt"=>$cipher->encrypt($cancelAmt),	//취소요청금액 (당일취소시는 원거래금액)
			"reqDt"=>$reqDt,
			"reqTm"=>$reqTm
			); 

		$data = call_bankpay_api($requestParam, $endPoint);

		//print_r($data);

		$resCd	= $data['resCd']; //"EBA0000":정상처리
		$resMsg	= $data['resMsg'];

		if($resCd == "EBA0000")
		{
			unset($data_args);
			$data_args['mem_idx']		= $user_info['mem_idx'];
			$data_args['invest_idx']	= $invest_idx;
			$data_args['funding_idx']	= $funding_idx;
			$data_args['invest_cnt']	= $invest_cnt;

			$rtn['err_code']	= $C_funding->set_funding_invest_cancel($data_args);
		}
		else
		{
			$rtn['err_code']	= $resMsg;
		}

		echo json_encode($rtn);  
		exit; 

	}

	if ($page == "") $page = 1;
	$page_size = 5;
	
	unset($data_args);
	$data_args = array(
					"mem_idx"=>$user_info['mem_idx'],
					"count"=>true,
					"list_type"=>"single",
					"start_dt"=>$start_dt,
					"end_dt"=>$end_dt
					);

	$tot_cnt	= $C_funding->get_funding_invest_info($data_args);

	unset($data_args);
	$data_args = array(
					"mem_idx"=>$user_info['mem_idx'],
					"orders"=>$orders,
					"start_dt"=>$start_dt,
					"end_dt"=>$end_dt,
					"page"=>$page,
					"page_size"=>$page_size
					);

	$data_list	= $C_funding->get_funding_invest_info($data_args);
	
	$params = "start_dt=".$start_dt."&end_dt=".$end_dt."&orders=".$orders; 
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>
</head>
 <body>
<form id="frm" method="post">
<input type="hidden" name="hid_invest_idx" id="hid_invest_idx"  /> 
 <!-- wrap -->
 <div id="wrap">
	<!-- header -->
	<div id="header">
		<? include_once FX_SITE."/std/inc/php/header_sp00.php" ?>
	</div>
	<!-- header end -->
	<!-- container -->
	<div id="container">
		<div class="fs_article">
			<div class="user_page_container">
				<div class="mypage_title">마이페이지</div>
				<div class="mypage_wrap">
					<div class="tabs_box_v2">
						<? include_once "_mypage_tab.php" ?>
					</div>
					<div class="tabs_container">
						<div class="page_contents">
							<div id="funding_list" class="tabs_contents_v2">
								<div class="funding_wrap">
									<div class="fs_input_v4 mgb20 W660">
										<span>정렬</span>
										<span>|</span>
										<select class="date_box H35" name="ddl_search_orderby" id="ddl_search_orderby">
											<option value='regdate'>참여 날짜순</option>
											<option value='money'>참여 금액순</option>
										</select>
										<input type="text" id="s_datepicker" name="s_datepicker" class="W85" value="<?=$start_dt?>">
										<input type="text" id="e_datepicker" name="e_datepicker" class="W85" value="<?=$end_dt?>">
										<button type="button" class="certi_box floatR" id="btn_search">기간조회</button>
									</div> 
									
									<? 
									
									for ($i=0; $i<count($data_list); $i++) {
										$invest_idx					= $data_list[$i]['invest_idx'];
										$funding_idx				= $data_list[$i]['funding_idx'];
										$funding_type				= $arr_Funding_type[$data_list[$i]['funding_type']];
										$funding_img_name			= $data_list[$i]['funding_img_name'];
										$mem_corporation_name		= $data_list[$i]['mem_corporation_name'];
										$funding_type_level			= $arr_Funding_type_invest[$data_list[$i]['funding_type_level']];
										$invest_regdate				= conv_date_format("Y.m.d", $data_list[$i]['invest_regdate']);
										$invest_cancel_date			= conv_date_format("Y.m.d", $data_list[$i]['invest_cancel_date']);
										$funding_price_total		= number_format($data_list[$i]['funding_price_total']); 
										
										$stock_share_rate			= $data_list[$i]['stock_share_rate'];
										$stock_per_amount			= number_format($data_list[$i]['stock_per_amount']);
										$stock_tot_cnt				= number_format($data_list[$i]['stock_tot_cnt']); 

										$bond_year_rate				= $data_list[$i]['bond_year_rate'];
										$bond_account_per_amount	= number_format($data_list[$i]['bond_account_per_amount']);
										$bond_account_cnt			= number_format($data_list[$i]['bond_account_cnt']);

										$invest_cnt					= number_format($data_list[$i]['invest_cnt']);
										$invest_money				= number_format($data_list[$i]['invest_money']);
 
										unset($args);
										$args['funding_idx']		= $funding_idx;
										$args['invest_isCancel']	= "0";
										$_list = $C_funding->get_funding_invest_info($args);	
										$tot_invest_pot = 0;
										$percent = 0;
										for ($ii=0; $ii<count($_list); $ii++)
										{
											$tot_invest_pot = $tot_invest_pot + $_list[$ii]['invest_money'];
										}
											 
										// 몇 퍼센트 : 일부값 / 전체값 * 100
										$percent = ($tot_invest_pot / $data_list[$i]['funding_price_total']) * 100; 
										$limit_day = ( strtotime(substr($data_list[$i]['funding_end_dt'], 0,10)) - strtotime(date("Y-m-d")) ) / 86400;   
 
										$str_div_class = "tac_orange";
										if ($data_list[$i]['funding_type'] == "1")
										{
											$str_div_class = "tac_green";
										}
										 
										$invest_isCancel			= $data_list[$i]['invest_isCancel'];
									?>
									<div class="funding_contents mgb20">
										<div class="funding_img">
											<img src="<?=$funding_img_name?>" alt="" />
											<div class="tac_section <?=$str_div_class?>">
												<span class="tac_tit"><?=$funding_type?></span>
												<span class="tac_mark">
													<button type="button" class="star_toogle" onclick="void(0);"><i class="fas fa-star book_mark"></i></button>
												</span>
											</div>
										</div>
										<div class="funding_text">
											<div class="top_section">
												<h3><?=$mem_corporation_name?></h3>
												<div class="funding_progressBar_wrap">
													<div class="funding_progressBar">
														<div class="funding_progressBar_bg">
															<div class="funding_progressBar_inner percent" id="percent_<?=$i?>"></div>
														</div>
													</div>
													<div class="funding_progressBar_value"><?=number_format($percent, 2, '.', '')?>%</div>
												</div><!-- funding_progressBar_wrap end -->
															
												<script>
													$("#percent_<?=$i?>").animate({width:'<?=number_format($percent, 2, '.', '')?>%'},2000);
												</script>
											</div>
											<div class="sub_section">
												<ul class="sub_text_left">
													<li>투자단계 : <span><?=$funding_type_level?></span></li>
													<li><span><?=$invest_regdate?> 참여</span></li>
												</ul>
												<ul class="sub_text_right">
													<li class="mgr35">목표금액 : <?=$funding_price_total?>원</li>
													<?if ($limit_day == 0){?>					
														<li>종료</li>
													<?}else{?>														
														<li><?=$limit_day?>일 남음</li>
													<?}?>
												</ul>
											</div>
											<?if ($data_list[$i]['funding_type'] == "1"){?>
											<div class="mid_section">
												<ul class="section_content">
													<li class="content_title">발행 지분률</li>
													<li class="content_article"><?=$stock_share_rate?>%</li>
												</ul>
												<ul class="section_content">
													<li class="content_title">발행 주식 수</li>
													<li class="content_article"><?=$stock_tot_cnt?>주</li>
												</ul>
												<ul class="section_content">
													<li class="content_title">주당가격</li>
													<li class="content_article"><?=$stock_per_amount?>원</li>
												</ul>
											</div>
											<div class="bot_section">
												<ul class="section_content">
													<li class="content_title">회원 참여 금액</li>
													<li class="content_article"><?=$invest_cnt?>주 / <?=$invest_money?>원</li>
												</ul>
												<?if ($invest_isCancel == "0"){?>
													<button type="button" class="cancel_apply" onclick="invest_cancel('<?=$invest_idx?>')">참여취소</button>
												<?}else{?>
													<button type="button" class="cancel_apply" ><?=$invest_cancel_date?>에 취소 처리된 펀드입니다.</button>
												<?}?>
											</div>
											<?}else{?>
											<div class="mid_section">
												<ul class="section_content">
													<li class="content_title">연 이자율 </li>
													<li class="content_article"><?=$bond_year_rate?>%</li>
												</ul>
												<ul class="section_content">
													<li class="content_title">발행 구좌 수</li>
													<li class="content_article"><?=$bond_account_cnt?>구좌</li>
												</ul>
												<ul class="section_content">
													<li class="content_title">구좌 당 가격</li>
													<li class="content_article"><?=$bond_account_per_amount?>원</li>
												</ul>
											</div>
											<div class="bot_section">
												<ul class="section_content">
													<li class="content_title">회원 참여 금액</li>
													<li class="content_article"><?=$invest_cnt?>구좌 / <?=$invest_money?>원</li>
												</ul> 
												<?if ($invest_isCancel == "0"){?>
													<button type="button" class="cancel_apply" onclick="invest_cancel('<?=$invest_idx?>')">참여취소</button>
												<?}else{?>
													<button type="button" class="cancel_apply" ><?=$invest_cancel_date?>에 취소 처리된 펀드입니다.</button>
												<?}?>
											</div>
											<?}?>
										</div>
									</div><!-- funding_contents end -->
									<?}?>
									<?
										if ($data_list){
											echo fn_paging($page_size, $page, $tot_cnt, $_SERVER["PHP_SELF"], $params);
										}
									?>
								 
								</div><!-- funding_wrap end -->
							</div><!-- funding_list end -->
						</div><!-- page_contents end -->
					</div><!-- tabs_container end -->
				</div><!-- mypage_wrap end -->
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

		$('.invisible').show();
		$( "#s_datepicker" ).datepicker();
		$( "#s_datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");

		$( "#e_datepicker" ).datepicker();
		$( "#e_datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
		
		$("#btn_search").on('click', function(e){    
			$('#frm').submit();
		}); 

		$("#ddl_search_orderby").val('<?=$orders?>');		
	});

	function invest_cancel(idx)
	{
		if(confirm('해당 펀딩 참여를 취소하시겠습니까?'))
		{
			$("#hid_invest_idx").val(idx);	

			var str_option = $("#frm").serialize();
			$.ajax({
				url: "mypage_fund.php",
				data: str_option,
				type: 'POST',
				dataType: 'json',
				traditional: true,
				timeout: 60000,
				error: function (request, status, error) {
				},
				success: function (response) {
					
					if(response.err_code == 1)
					{
						alert('참여 취소 처리 되었습니다.\n익일 이후 환불처리 됩니다.');
						location.reload();
					} 
					else
					{
						alert('error :: '+response.err_code);
						return false;
					}
				}
			}); 
		} 
	}

</script>