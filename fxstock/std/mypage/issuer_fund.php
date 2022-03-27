<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member = new Member; 
	$C_funding	= new Funding;
	
	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("로그인이 필요합니다.", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}
 
	$start_dt			= v3chk($_REQUEST['s_datepicker'], "", 10);
	$end_dt				= v3chk($_REQUEST['e_datepicker'], "", 10);
	$page				= v3chk($_REQUEST['page'], "int");
	$funding_status		= $_REQUEST['ddl_issue_status']; 
	
	if ($funding_status == "")
	{
		$funding_status = "100";
	}

	$invest_idx		= v3chk($_REQUEST['hid_invest_idx'], "int");


	if ($page == "") $page = 1;
	$page_size = 5;
	
	unset($args);
	$args['count']						= true;
	$args['list_type']					= "single";
	$args['mem_idx']					= $user_info['mem_idx'];	
	$args['funding_review']				= false;
	$args['funding_issue_status']		= $funding_status; 

	$tot_cnt = $C_funding->get_funding_issue($args);

	unset($args);
	$args['page']						= $page;
	$args['page_size']					= $list_num; 
	$args['mem_idx']					= $user_info['mem_idx'];	
	$args['funding_review']				= false;
	$args['funding_issue_status']		= $funding_status; 

	$data_list = $C_funding->get_funding_issue($args);
	
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
		<? include_once FX_SITE."/std/inc/php/header.php" ?>
	</div>
	<!-- header end -->
	<!-- container -->
	<div id="container">
		<div class="fs_article">
			<div class="user_page_container">
				<div class="mypage_title">마이페이지</div>
				<div class="mypage_wrap">
					<div class="tabs_box_v2">
						
						<button type="button" class="idpw_tabs" onclick="location.href='/std/mypage/issuer_info.php'">기본정보</button>
						<button type="button" class="idpw_tabs white_bg noBo" onclick="location.href='/std/mypage/issuer_fund.php'">펀딩발행내역</button>
					</div>
					<div class="tabs_container">
						<div class="page_contents">
							<div id="funding_list" class="tabs_contents_v2">
								<div class="funding_wrap">
									<div class="fs_input_v4 mgb20 W660">
										<span>정렬</span>
										<span>|</span> 
										<select class="date_box H35" id="ddl_issue_status" name="ddl_issue_status"> 
											<option value="100">진행중인 펀드</option>
											<option value="200">종료된 펀드</option>
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
						alert('참여 취소 처리 되었습니다.');
						location.reload();
					} 
				}
			}); 
		} 
	}

</script>