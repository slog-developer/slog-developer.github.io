<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member	= new Member;
	$C_funding	= new Funding;

	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}

	$funding_idx = $_GET['funding_idx'];

	unset($args);
	
	if($funding_idx == "")
	{
		$args['mem_idx']	= $user_info['mem_idx'];
		$args['list_type']	= "row";
		$mem_info			= $C_member->get_user_info($args); 
		
		if($mem_info['mem_division'] != "3") {	//권한 체크
			execJava("", "", "location.href='/';");
		}
	}
	else
	{
		$args['funding_idx']	= $funding_idx;
	}

	$isUpdate = false;
	$args['list_type']	= "row";
	$funding_view = $C_funding->get_funding_issue($args); 

	if($funding_view['funding_issue_status'] == "0")
	{ 
		execJava("작성중인 내용이 있습니다.", "", "location.href='pb_step3.php';");
	}
	else if($funding_view['funding_issue_status'] > "0" && $funding_view['funding_issue_status'] <= "4")
	{
		$isUpdate = true;
	}
	else if($funding_view['funding_issue_status'] == "100")
	{
		execJava("이미 발행중인 펀딩이 있습니다.", "", "location.href='/';");
	}
	
	$issue_update_isOk = 0;
	if($user_info['mem_idx'] == $funding_view['mem_idx'])
	{
		$issue_update_isOk = $funding_view['issue_update_isOk'];
	}
 
	$_SESSION['isEditor']	= 1; 

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?> 
</head>
 <body>
<form id="frm" method="post">
<input type="hidden" id="hid_areaCnt" value="1"> 
<input type="hidden" name="hid_filename" id="hid_filename" value="<?=$funding_view['funding_img_name']?>">
<input type="hidden" name="flag" id="flag">
<input type="hidden" name="mode" id="mode">

<input type="hidden" name="hid_funding_type" value="<?=$funding_view['funding_type']?>">
<input type="hidden" name="hid_funding_type_detail" value="<?=$funding_view['funding_type_detail']?>">
<input type="hidden" name="hid_funding_type_level" value="<?=$funding_view['funding_type_level']?>">
<input type="hidden" name="funding_issue_status" value="<?=$funding_view['funding_issue_status']?>">

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
			<div class="row_wrap">
				<div class="funding_txt_box">
					<p>펀딩 발행을 신청하시면 SUCCESTOCK 담당자가 확인 후 실사 관련 안내전화를 드립니다. <br />
					모집내용, 회사정보의 모든 항목을 입력하신 후 “신청하기” 버튼을 클릭하시면 신청이 완료가 됩니다. <br />
					(회사정보 > CEO 인사말 / 주요 사업 및 수익 구조 / 사업계획은 1가지 이상만 필수로 등록 하셔도 됩니다.)</p>
				</div>
			</div>
			<div class="pb_bg_section">
				<? include_once "inc_top.php" ?> 
			</div><!-- pb_bg_section end -->
			<div class="sub_tab" id="sub_tab_form">
				<ul class="sub_tab_list_v2">
					<li id="link_step2" style='cursor: pointer;'  class="purple_bottom">모집내용</li>
					<li id="link_step3" style='cursor: pointer;'>회사정보</li>
				</ul>
			</div>
			<div class="funding_container">
				<div class="nav_recruit" id="nav_recruit_form">
					<ul>
						<li><a href=".recruit_container_1">증권 발행조건</a></li>
						<li><a href=".recruit_container_2">사업 포인트</a></li>
						<li><a href=".recruit_container_3">펀딩 이야기</a></li>
						<li><a href=".recruit_container_4">펀딩 인터뷰</a></li>
					</ul>
				</div>
				<div class="chat_wrap" id="chat_wrap_form">
					<? include_once "inc_right.php" ?> 			
					<div class="fs_btn_box mgt10">
						<? if ($issue_update_isOk == "1"){?>
							<button type="button" id="btn_reg" class="certi_box_v5">수정하기</button>
							<button type="button" id="btn_cancel" class="chat_list_btn_v2">취소</button>
						<?}elseif (!$isUpdate){?>
							<button type="button" id="btn_reg" class="certi_box_v5">회사정보 입력</button>
							<button type="button" class="chat_list_btn_v2" onclick="location.href='/'">취소</button>
						<?}?>
					</div>
				</div>
				<div class="recruit_wrap">
					<div class="recruit_container_1">
						<div class="recruit_tit mgb20">
							<h1>증권 발행 조건</h1>
						</div>
						<div class="recruit_content">		
							<!--주식형-->
							<ul class="recruit_list_v2 pdb50" id="ul_stock">
								<li class="W320">
									<p>총 발행 금액</p>
									<input type="text" id="txt_issue_stock_price_total" name="txt_issue_stock_price_total" value="<?=number_format($funding_view['funding_price_total'])?>" numberOnly_Comma maxlength="12" readOnly />원
								</li>
								<li class="W320">
									<p>최소 투자 금액</p>
									<input type="text" id="txt_issue_stock_invest_amount" name="txt_issue_stock_invest_amount" numberOnly_Comma maxlength="9" style="width:70px;"  value="<?=number_format($funding_view['funding_invest_amount'])?>" readOnly />원
									/ 
									<input type="text" id="txt_issue_stock_invest_cnt" name="txt_issue_stock_invest_cnt" numberOnly_Comma maxlength="9" style="width:70px;"   value="<?=number_format($funding_view['funding_invest_cnt'])?>" /> 주
								</li>
								<li class="W320">
									<p>발행 지분율</p>
									<input type="text" id="txt_issue_stock_share_rate" name="txt_issue_stock_share_rate" numberOnly_Comma maxlength="4" value="<?=$funding_view['stock_share_rate']?>" />%
								</li>
								<li class="W320">
									<p>최저 배당율</p>
									발행가의 <input type="text" id="txt_issue_stock_minimum_rate" name="txt_issue_stock_minimum_rate" style="width:100px;" maxlength="4"  value="<?=$funding_view['stock_minimum_rate']?>" numberOnly_Comma /> %
								</li>
								<li class="W320">
									<p>총 발행 주식수</p>
									<input type="text" id="txt_issue_stock_totCnt" name="txt_issue_stock_totCnt" numberOnly_Comma maxlength="12"   value="<?=number_format($funding_view['stock_tot_cnt'])?>" />주
								</li>
								<li class="W320">
									<p>주 당 금액</p>
									<input type="text" id="txt_issue_stock_per_amount" name="txt_issue_stock_per_amount" numberOnly_Comma maxlength="12" value="<?=number_format($funding_view['stock_per_amount'])?>"  />원
								</li>
								<li class="W320">
									<p>현재 기업가치</p>
									<input type="text" id="txt_issue_stock_company_value" name="txt_issue_stock_company_value" numberOnly_Comma maxlength="12" value="<?=number_format($funding_view['funding_company_value'])?>" />원
								</li>
								<li class="W320">
									<p>증권 입고일</p>
									<input type="text" id="txt_issue_stock_receive_dt" name="txt_issue_stock_receive_dt" class='Calendar' readOnly />
								</li>
								<!--
								<li class="W320">
									<p>펀딩 시작일</p>
									<input type="text" id="txt_issue_stock_startDt" name="txt_issue_stock_startDt" class='Calendar'  readOnly>
								</li>
								<li class="W320">
									<p>펀딩 종료일</p>
									<input type="text" id="txt_issue_stock_endDt" name="txt_issue_stock_endDt" class='Calendar' readOnly>
								</li> 
								-->
								<li class="W320" id='funding_type_detail_1' style="display:none;">
									<p>전환 청구 기간</p>
									<input type="text" id="txt_issue_stock_switch_claim_startDt" name="txt_issue_stock_switch_claim_startDt" readOnly style="width:70px;" class='Calendar'>
									~
									<input type="text" id="txt_issue_stock_switch_claim_endDt" name="txt_issue_stock_switch_claim_endDt" readOnly style="width:70px;"  class='Calendar'>
								</li>
								<li class="W320" id='funding_type_detail_2' style="display:none;">
									<p>전환 조건</p>
									<input type="text" id="txt_issue_stock_switch_condition_1" name="txt_issue_stock_switch_condition_1" numberOnly maxlength="1" style="width:70px;"  value="<?=$funding_view['stock_switch_condition_1']?>"  />
									:
									<input type="text" id="txt_issue_stock_switch_condition_2" name="txt_issue_stock_switch_condition_2" numberOnly maxlength="1" style="width:70px;"  value="<?=$funding_view['stock_switch_condition_2']?>"  /> 
								</li>
								<li class="W320" id='funding_type_detail_3' style="display:none;">
									<p>상환 청구 기간</p>
									<input type="text" id="txt_issue_stock_payback_startDt" name="txt_issue_stock_payback_startDt" readOnly style="width:70px;" class='Calendar'>
									~
									<input type="text" id="txt_issue_stock_payback_endDt" name="txt_issue_stock_payback_endDt" readOnly style="width:70px;" class='Calendar'>
								</li>
							</ul>
							
							<!--채권형-->
							<ul class="recruit_list_v2 pdb50" id="ul_bonds" style="display:none;">
								<li class="W320">
									<p>총 발행 금액</p>
									<input type="text" id="txt_issue_bond_price_total" name="txt_issue_bond_price_total" numberOnly_Comma maxlength="12"  value="<?=number_format($funding_view['funding_price_total'])?>" readOnly />원
								</li>
								<li class="W320">
									<p>최소 투자 금액</p> 
									<input type="text" id="txt_issue_bond_invest_Amount" name="txt_issue_bond_invest_Amount" numberOnly_Comma maxlength="9" style="width:60px;"   value="<?=number_format($funding_view['funding_invest_amount'])?>" readOnly /> 원
									/ 
									<input type="text" id="txt_issue_bond_invest_cnt" name="txt_issue_bond_invest_cnt" numberOnly_Comma maxlength="9" style="width:60px;"   value="<?=number_format($funding_view['funding_invest_cnt'])?>" /> 구좌
								</li>
								<li class="W320">
									<p>구좌 당 금액</p>
									<input type="text" id="txt_issue_bond_account_per_amount" name="txt_issue_bond_account_per_amount" numberOnly_Comma maxlength="12"   value="<?=number_format($funding_view['bond_account_per_amount'])?>" />원
								</li>
								<li class="W320">
									<p>연 이자율</p>
									<input type="text" id="txt_issue_bond_year_rate" name="txt_issue_bond_year_rate" maxlength="3" value="<?=$funding_view['bond_year_rate']?>" />%
								</li>
								<li class="W320">
									<p>발행 구좌수</p>
									<input type="text" id="txt_issue_bond_account_cnt" name="txt_issue_bond_account_cnt" numberOnly_Comma maxlength="12" value="<?=number_format($funding_view['bond_account_cnt'])?>" />구좌
								</li>
								<!--
								<li class="W320">
									<p>펀딩 시작일</p>
									<input type="text" id="txt_issue_bond_startDt" name="txt_issue_bond_startDt" readOnly class='Calendar'>
								</li>
								<li class="W320">
									<p>펀딩 종료일</p>
									<input type="text" id="txt_issue_bond_endDt" name="txt_issue_bond_endDt" readOnly class='Calendar'>
								</li>
								-->
								<li class="W320">
									<p>현재 기업가치</p>
									<input type="text" id="txt_issue_bond_company_value" name="txt_issue_bond_company_value" numberOnly_Comma maxlength="12"  value="<?=number_format($funding_view['funding_company_value'])?>" />원
								</li>
								<li class="W320">
									<p>채권 만기일</p>
									<input type="text" id="txt_issue_bond_receive_dt" name="txt_issue_bond_receive_dt" readOnly class='Calendar' />
								</li>
							</ul> 

							<div class="recruit_txt mgt10">
								<p class="disIB">소득공제혜택 대상 기업 입니다.</p>
								<div class="terms_tit_check disIB vM pdt5">
									<input class="checkbox" type="checkbox" id="chk_agree" name="chk_agree" value="1" <?if ($funding_view['isTax_deduction'] == "1"){?>checked<?}?> />
									<label for="chk_agree"></label>
								</div>
							</div>
						</div><!-- recruit_content end -->			
					</div>
					<div class="recruit_container_2">
						<div class="recruit_tit">
							<h1>사업 포인트</h1>
							<h2 class="deep_gray">펀딩을 발행하는 프로젝트 관련 핵심 포인트를 등록해주세요.</h2>
						</div>
						<div class="recruit_content">
							<div class="input_fields_wrap mgt20">
								<div class="sd_wrap">
									<?if (!$isUpdate){?>
									<div class="disIB mgb20">
										<h1 class="input_fields_tit" name="mytext[]"><input type="text" id="txt_business_point_title_1" name="txt_business_point_title_1" maxlength="40" placeholder="제목 입력(40 글자까지 입력가능)" /></h1>
										<p class="input_fields_con mgt15" name="mytext[]">
											<textarea  id="txt_business_point_content_1" name="txt_business_point_content_1" placeholder="최대 3줄까지 가능/ 100 글자까지 입력가능" rows="4" maxlength="105">
											</textarea>
										</p>
									</div>
									<button class="add_field_plus" type="button"r><i class="fas fa-plus"></i></button>
									<?}else{
																		
										for ($ii = 1; $ii <= 5; $ii++){  
											if($funding_view['business_point_title_'.$ii.''] != "")
											{
									?>
											<script>
												$("#txt_business_point_content_<?=$ii?>").val('');
											</script>
											<div class="disIB mgb20">
												<h1 class="input_fields_tit" name="mytext[]"><input type="text" id="txt_business_point_title_<?=$ii?>" name="txt_business_point_title_<?=$ii?>" maxlength="40" value="<?=$funding_view['business_point_title_'.$ii.'']?>" /></h1>
												<p class="input_fields_con mgt15" name="mytext[]">
													<textarea  id="txt_business_point_content_<?=$ii?>" name="txt_business_point_content_<?=$ii?>" rows="4" maxlength="200"><?=$funding_view['business_point_content_'.$ii.'']?></textarea>
												</p>
											</div>
									<?
											}
										}
									?>
									
									<?}?>
								</div>
							</div>
						</div>
					</div>
					<div class="recruit_container_3">
						<div class="recruit_tit">
							<h1>펀딩 이야기</h1>							
						</div>
						<div class="recruit_content mgb10">
							<h3>1. 사업내용</h3>
							<p class="mgt10"><div id="summernote_1"><?=$funding_view['funding_story_1']?></div></p>
							<h3>2. 사업 차별점 / 특장점</h3>
							<p class="mgt10"><div id="summernote_2"><?=$funding_view['funding_story_2']?></div></p>
							<h3>3. 시장분석</h3>
							<p class="mgt10"><div id="summernote_3"><?=$funding_view['funding_story_3']?></div></p>
						</div>
					</div>
					<div class="recruit_container_4">
						<div class="recruit_tit">
							<h1>펀딩 인터뷰</h1>							
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>간단한 회사 소개를 해주세요.</h3>
							<p><div id="summernote_4"><?=$funding_view['funding_story_4']?></div></p>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>현재 투자 단계에 대해 설명해주세요.</h3>
							<p><div id="summernote_5"><?=$funding_view['funding_story_5']?></div></p>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>진행중인 사업에 대해 소개해주세요.</h3>
							<p><div id="summernote_6"><?=$funding_view['funding_story_6']?></div></p>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>사업의 강점을 상세하게 설명해주세요.</h3>
							<p><div id="summernote_7"><?=$funding_view['funding_story_7']?></div></p>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>향후 계획은 어떻게 되나요?	</h3>
							<p><div id="summernote_8"><?=$funding_view['funding_story_8']?></div></p>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>마지막으로 투자자분들께 회사를 어필해주세요.</h3>
							<p><div id="summernote_9"><?=$funding_view['funding_story_9']?></div></p>
						</div>
					</div>
				</div><!-- recruit_wrap end -->

				<a class="top_btn"><i class="fas fa-angle-up"></i></a>
			</div><!--  funding_container end -->
		</div><!-- fs_article end -->
	</div>
	<!-- container end -->
	<!-- footer -->
	<div id="footer"><!-- 푸터링크 영역 및 푸터컨텐츠 영역 -->
		<? include_once FX_SITE."/std/inc/php/footer.php" ?>
	</div>
	<!-- footer end -->
  </div>
 <!-- wrap end --> 
 
<?for($i=1;$i<=9;$i++){?>
	<textarea name="bc_contents_<?=$i?>" style="display:none;"></textarea>
<?}?>
 </form>
 </body>
</html>
<script type="text/javascript" src="pb_step_common.js"></script>
<script type="text/javascript" src="/std/inc/js/_common.js"></script>
<script type="text/javascript"> 
$(document).ready(function() {
	
	$(".Calendar").datepicker();
	$(".Calendar").datepicker( "option", "dateFormat", "yy-mm-dd");  
 
	<?for($i=1;$i<=9;$i++){?>
		$('#summernote_<?=$i?>').summernote({
			width:538,height: 250
		});
	<?}?>

	//Stock
	$("#txt_issue_stock_invest_cnt").keyup(function() {
		if(!$("#txt_issue_stock_per_amount").val())
		{
			alert('주 당 금액을 입력해주세요');
			$("#txt_issue_stock_per_amount").focus();
			$("#txt_issue_stock_invest_cnt").val('');
			return false;
		}

		var invest_money = uncomma($("#txt_issue_stock_per_amount").val()) * uncomma($("#txt_issue_stock_invest_cnt").val());
		$("#txt_issue_stock_invest_amount").val(comma(invest_money)); 
	});
 
	$("#txt_issue_stock_per_amount").keyup(function() {
		if(!$("#txt_issue_stock_totCnt").val())
		{
			alert('총 발행 주식수를 입력해주세요');
			$("#txt_issue_stock_totCnt").focus();
			$("#txt_issue_stock_per_amount").val('');
			return false;
		}

		var tot_issue_money = uncomma($("#txt_issue_stock_per_amount").val()) * uncomma($("#txt_issue_stock_totCnt").val());
		$("#txt_issue_stock_price_total").val(comma(tot_issue_money));
	});

	//Bond  txt_issue_bond_invest_cnt
	$("#txt_issue_bond_invest_cnt").keyup(function() {
		if(!$("#txt_issue_bond_account_per_amount").val())
		{
			alert('구좌 당 금액을 입력해주세요');
			$("#txt_issue_bond_account_per_amount").focus();
			$("#txt_issue_bond_invest_cnt").val('');
			return false;
		}

		var invest_money = uncomma($("#txt_issue_bond_account_per_amount").val()) * uncomma($("#txt_issue_bond_invest_cnt").val());
		$("#txt_issue_bond_invest_Amount").val(comma(invest_money)); 
	});

	$("#txt_issue_bond_account_per_amount").keyup(function() {
		if(!$("#txt_issue_bond_account_cnt").val())
		{
			alert('총 발행 구좌 수를 입력해주세요');
			$("#txt_issue_bond_account_cnt").focus();
			$("#txt_issue_bond_account_per_amount").val('');
			return false;
		}

		var tot_issue_money = uncomma($("#txt_issue_bond_account_per_amount").val()) * uncomma($("#txt_issue_bond_account_cnt").val());
		$("#txt_issue_bond_price_total").val(comma(tot_issue_money));
	});

	$.uploadPreview({
		input_field: "#img_upload",
		preview_box: "#image-preview",
		label_field: "#image-label"
	});  

	<?if($isUpdate == "1"){?>
		<?
		if ($issue_update_isOk == "0")
		{
		?>
			$('input').prop('readonly', true);
			$('textarea').prop('readonly', true);
			$('input[type=file]').attr('disabled', true);
			$("#image-label").css("display","none"); 

			<?for($i=1;$i<=9;$i++){?>
				$('#summernote_<?=$i?>').summernote('disable');
			<?}?> 
		<?
		}
		?>

		$("#txt_issue_stock_receive_dt").val('<?=substr($funding_view['stock_receive_dt'], 0,10)?>');

		// $("#txt_issue_stock_startDt").val('<?=substr($funding_view['funding_start_dt'], 0,10)?>');
		// $("#txt_issue_stock_endDt").val(' <?=substr($funding_view['funding_end_dt'], 0,10)?>');

		$("#txt_issue_stock_switch_claim_startDt").val('<?=substr($funding_view['stock_switch_claim_start_dt'], 0,10)?>');
		$("#txt_issue_stock_switch_claim_endDt").val('<?=substr($funding_view['stock_switch_claim_end_dt'], 0,10)?>');
		
		$("#txt_issue_stock_payback_startDt").val('<?=substr($funding_view['stock_payback_start_dt'], 0,10)?>');
		$("#txt_issue_stock_payback_endDt").val('<?=substr($funding_view['stock_payback_end_dt'], 0,10)?>');

		// $("#txt_issue_bond_startDt").val('<?=substr($funding_view['funding_start_dt'], 0,10)?>');
		// $("#txt_issue_bond_endDt").val('<?=substr($funding_view['funding_end_dt'], 0,10)?>');
		$("#txt_issue_bond_receive_dt").val('<?=substr($funding_view['bond_receive_dt'], 0,10)?>');

		$("#ddl_funding_type").val("<?=$funding_view['funding_type']?>");
		$("#ddl_funding_type_detail_stock").val("<?=$funding_view['funding_type_detail']?>");
		$("#ddl_funding_type_detail_bond").val("<?=$funding_view['funding_type_detail']?>");
		$("#ddl_funding_invest_type").val("<?=$funding_view['funding_type_level']?>");

		$("#ddl_funding_type").attr("disabled",true);
		$("#ddl_funding_type_detail_bond").attr("disabled",true);
		$("#ddl_funding_type_detail_stock").attr("disabled",true);
		$("#ddl_funding_invest_type").attr("disabled",true);

		<?
			if ($funding_view['funding_type'] == "1")
			{
		?> 
				$("#ddl_funding_type_detail_bond").css("display","none"); 
				<?
					if($funding_view['funding_type_detail'] == "3"){
				?>
					$("#funding_type_detail_3").css("display",""); 
				<?
					}
					else if($funding_view['funding_type_detail'] == "4"){
				?>
					$("#funding_type_detail_1").css("display",""); 
					$("#funding_type_detail_2").css("display",""); 
				<?
					}
					else if($funding_view['funding_type_detail'] == "5"){
				?>
					$("#funding_type_detail_1").css("display",""); 
					$("#funding_type_detail_2").css("display",""); 
					$("#funding_type_detail_3").css("display",""); 
				<?
					}
				?>
		<?
			}
			else
			{
		?>
				$("#ddl_funding_type_detail_stock").css("display","none"); 
				$("#ddl_funding_type_detail_bond").css("display",""); 
		<?
			}
 
		?> 
	<?}else{?>
		$("#txt_business_point_content_1").val('');
		$('input').val('');
	<?}?>

	
	$("#link_step2").on('click', function(e){ 
		location.href= "pb_step2.php?funding_idx=<?=$funding_idx?>";
	});
	
	$("#link_step3").on('click', function(e){ 
		location.href= "pb_step3.php?funding_idx=<?=$funding_idx?>";
	});


 
	$("#ddl_funding_type").change(function(){		

		if($("#ddl_funding_type").val() == "1"){
			$("#ul_bonds").css("display","none"); 
			$("#ul_stock").css("display",""); 

			$("#ddl_funding_type_detail_stock").css("display",""); 
			$("#ddl_funding_type_detail_bond").css("display","none"); 

			$("#ddl_funding_type_detail_stock").val('');
			$("#ddl_funding_type_detail_bond").val('');

			<?for($i=1;$i<=3;$i++){?>
				$("#funding_type_detail_<?=$i?>").css("display","none"); 
			<?}?>
		}
		else
		{
			$("#ul_bonds").css("display",""); 
			$("#ul_stock").css("display","none"); 
 
			$("#ddl_funding_type_detail_stock").css("display","none"); 
			$("#ddl_funding_type_detail_bond").css("display",""); 
			
			$("#ddl_funding_type_detail_stock").val('');
			$("#ddl_funding_type_detail_bond").val('');
			
			<?for($i=1;$i<=3;$i++){?>
				$("#funding_type_detail_<?=$i?>").css("display","none"); 
			<?}?>
		}
	});  

	$("#ddl_funding_type_detail_stock").change(function(){		
		if($("#ddl_funding_type_detail_stock").val() == "1" || $("#ddl_funding_type_detail_stock").val() == "2"){
			//보통주, 우선주
			<?for($i=1;$i<=3;$i++){?>
				$("#funding_type_detail_<?=$i?>").css("display","none"); 
			<?}?>
		}
		else if ($("#ddl_funding_type_detail_stock").val() == "3"){
			//상환우선주
			$("#funding_type_detail_1").css("display","none"); 
			$("#funding_type_detail_2").css("display","none"); 
			$("#funding_type_detail_3").css("display",""); 
		}
		else if ($("#ddl_funding_type_detail_stock").val() == "4"){
			//전환우선주
			$("#funding_type_detail_1").css("display",""); 
			$("#funding_type_detail_2").css("display",""); 
			$("#funding_type_detail_3").css("display","none"); 
		}
		else if ($("#ddl_funding_type_detail_stock").val() == "5"){
			
			//상환전환우선주
			<?for($i=1;$i<=3;$i++){?>
				$("#funding_type_detail_<?=$i?>").css("display",""); 
			<?}?>
			
		}

	}); 

	$("#btn_reg").on('click', function(e){    

		<?
		if ($isUpdate && $issue_update_isOk == "0")
		{
		?>
			location.href = 'pb_step3.php';
			return false;
		<?
		}
		?>
		 
		if($("#ddl_funding_type").val() == "1"){
			
			if(!$("#ddl_funding_type_detail_stock").val()){
				alert('상세선택을 해주세요.');
				return false;
			}
 
			if($("#ddl_funding_type_detail_stock").val() == "3")
			{
				if(!$("#txt_issue_stock_payback_startDt").val() )
				{
					alert('상환 청구기간을 입력해주세요.');
					$("#txt_issue_stock_payback_startDt").focus();
					return false;
				}
				
				if(!$("#txt_issue_stock_payback_endDt").val() )
				{
					alert('상환 청구기간을 입력해주세요.');
					$("#txt_issue_stock_payback_endDt").focus();
					return false;
				}
			}

			if($("#ddl_funding_type_detail_stock").val() == "4")
			{
				if(!$("#txt_issue_stock_switch_claim_startDt").val() )
				{
					alert('전환 청구기간을 입력해주세요.');
					$("#txt_issue_stock_switch_claim_startDt").focus();
					return false;
				}
				
				if(!$("#txt_issue_stock_switch_claim_endDt").val() )
				{
					alert('전환 청구기간을 입력해주세요.');
					$("#txt_issue_stock_switch_claim_endDt").focus();
					return false;
				}
				
				if(!$("#txt_issue_stock_switch_condition_1").val() )
				{
					alert('전환 조건을 입력해주세요.');
					$("#txt_issue_stock_switch_condition_1").focus();
					return false;
				}
				
				if(!$("#txt_issue_stock_switch_condition_2").val() )
				{
					alert('전환 조건을 입력해주세요.');
					$("#txt_issue_stock_switch_condition_2").focus();
					return false;
				}
			}

			
			if($("#ddl_funding_type_detail_stock").val() == "5")
			{
				if(!$("#txt_issue_stock_payback_startDt").val() )
				{
					alert('상환 청구기간을 입력해주세요.');
					$("#txt_issue_stock_payback_startDt").focus();
					return false;
				}
				
				if(!$("#txt_issue_stock_payback_endDt").val() )
				{
					alert('상환 청구기간을 입력해주세요.');
					$("#txt_issue_stock_payback_endDt").focus();
					return false;
				}

				if(!$("#txt_issue_stock_switch_claim_startDt").val() )
				{
					alert('전환 청구기간을 입력해주세요.');
					$("#txt_issue_stock_switch_claim_startDt").focus();
					return false;
				}
				
				if(!$("#txt_issue_stock_switch_claim_endDt").val() )
				{
					alert('전환 청구기간을 입력해주세요.');
					$("#txt_issue_stock_switch_claim_endDt").focus();
					return false;
				}
				
				if(!$("#txt_issue_stock_switch_condition_1").val() )
				{
					alert('전환 조건을 입력해주세요.');
					$("#txt_issue_stock_switch_condition_1").focus();
					return false;
				}
				
				if(!$("#txt_issue_stock_switch_condition_2").val() )
				{
					alert('전환 조건을 입력해주세요.');
					$("#txt_issue_stock_switch_condition_2").focus();
					return false;
				}
			}

			if(!$("#txt_issue_stock_totCnt").val() )
			{
				alert('총 발행 주식수를 입력해주세요.');
				$("#txt_issue_stock_totCnt").focus();
				return false;
			}
			
			if(!$("#txt_issue_stock_per_amount").val() )
			{
				alert('주 당 금액를 입력해주세요.');
				$("#txt_issue_stock_per_amount").focus();
				return false;
			}

			if(!$("#txt_issue_stock_price_total").val() )
			{
				alert('총 발행 금액을 입력해주세요.');
				$("#txt_issue_stock_price_total").focus();
				return false;
			}
			
			if(!$("#txt_issue_stock_invest_cnt").val() )
			{
				alert('최소 투자 주식수를 입력해주세요.');
				$("#txt_issue_stock_invest_cnt").focus();
				return false;
			}
			
			if(!$("#txt_issue_stock_invest_amount").val() )
			{
				alert('최소 투자 금액 입력해주세요.');
				$("#txt_issue_stock_invest_amount").focus();
				return false;
			} 
			
			if(!$.isNumeric($("#txt_issue_stock_share_rate").val()) || !$("#txt_issue_stock_share_rate").val() )
			{
				alert('발행 지분율을 입력해주세요.');
				$("#txt_issue_stock_share_rate").val('');
				$("#txt_issue_stock_share_rate").focus();
				return false;
			}
			
			if(!$.isNumeric($("#txt_issue_stock_minimum_rate").val()) || !$("#txt_issue_stock_minimum_rate").val() )
			{
				alert('최저 배당율을 입력해주세요.');
				$("#txt_issue_stock_minimum_rate").val('');
				$("#txt_issue_stock_minimum_rate").focus();
				return false;
			}
			 
			if(!$("#txt_issue_stock_company_value").val() )
			{
				alert('현재 기업가치를 입력해주세요.');
				$("#txt_issue_stock_company_value").focus();
				return false;
			}
			
			if(!$("#txt_issue_stock_receive_dt").val() )
			{
				alert('증권입고일를 입력해주세요.');
				$("#txt_issue_stock_receive_dt").focus();
				return false;
			}
		}
		else{
			
			if(!$("#ddl_funding_type_detail_bond").val()){
				alert('상세선택을 해주세요.');
				return false;
			}
			
			if(!$("#txt_issue_bond_account_cnt").val() )
			{
				alert('발행 구좌수를 입력해주세요.');
				$("#txt_issue_bond_account_cnt").focus();
				return false;
			}
			
			if(!$("#txt_issue_bond_account_per_amount").val() )
			{
				alert('구좌 당 금액을 입력해주세요.');
				$("#txt_issue_bond_account_per_amount").focus();
				return false;
			}
			
			if(!$("#txt_issue_bond_invest_cnt").val() )
			{
				alert('최소 투자 금액 구좌을 입력해주세요.');
				$("#txt_issue_bond_invest_cnt").focus();
				return false;
			}
			
			if(!$("#txt_issue_bond_invest_Amount").val() )
			{
				alert('최소 투자 금액을 입력해주세요.');
				$("#txt_issue_bond_invest_Amount").focus();
				return false;
			} 

			if(!$("#txt_issue_bond_price_total").val() )
			{
				alert('총 발행 금액을 입력해주세요.');
				$("#txt_issue_bond_price_total").focus();
				return false;
			}
			 
			if(!$.isNumeric($("#txt_issue_bond_year_rate").val()) || !$("#txt_issue_bond_year_rate").val() )
			{
				alert('연 이자율을 입력해주세요.');
				$("#txt_issue_bond_year_rate").val('');
				$("#txt_issue_bond_year_rate").focus();
				return false;
			}
			 
			if(!$("#txt_issue_bond_company_value").val() )
			{
				alert('현재기업가치를 입력해주세요.');
				$("#txt_issue_bond_company_value").focus();
				return false;
			} 
			
			if(!$("#txt_issue_bond_receive_dt").val() )
			{
				alert('채권 만기일를 입력해주세요.');
				$("#txt_issue_bond_receive_dt").focus();
				return false;
			}
		}
		
		if(!$("#hid_filename").val()){
			if(!$("#img_upload").val()){
				alert('상단 이미지를 등록해주세요.');
				return false;
			}
		}

		if(!$("#txt_top_memo").val()){
			alert('펀딩을 표현할 수 있는 문장을 입력해주세요.');
			return false;
		} 
		
		
		if(!$("#ddl_funding_invest_type").val()){
			alert('투자 단계를 해주세요.');
			return false;
		}
		
		var cnt = $( "#hid_areaCnt" ).val();
		for (var i = 1; i <= cnt; i++){ 
			if(!$("#txt_business_point_title_"+i).val() )
			{
				alert('사업포인트 제목을 입력해주세요.');
				$("#txt_business_point_title_"+i).focus();
				return false;
			}
			
			if(!$("#txt_business_point_content_"+i).val() )
			{
				alert('사업포인트 내용을 입력해주세요.');
				$("#txt_business_point_content_"+i).focus();
				return false;
			}
		}

		<?
			$arr_msg = array('', '사업내용을 입력해주세요', '사업 차별점/특장점을 입력해주세요', '시장분석을 입력해주세요', '회사소개를 입력해주세요', '투자 단계를 입력해주세요', '진행중인 사업에 대해 입력해주세요', '사업의 강점을 입력해주세요', '향후 계획을 입력해주세요', '회사 어필을 입력해주세요');
			for($i=1;$i<=9;$i++){
		?>
			$('textarea[name="bc_contents_<?=$i?>"]').val($('#summernote_<?=$i?>').summernote('code'));

			if($('textarea[name="bc_contents_<?=$i?>"]').val() == "<p><br></p>")
			{
				alert('<?=$arr_msg[$i]?>.');
				$('#summernote_<?=$i?>').summernote('focus');
				return false;
			}
 
		<?}?>  

		<?
			if($isUpdate && $issue_update_isOk == "1")
			{
		?>
			$("#mode").val('U');
		<?
			}
			else
			{
		?>
			$("#mode").val('I');
		<?
			}
		?>
		
		if(!$("#hid_filename").val()){
			var img = $('#img_upload');
			var imgFile = img[0].files[0];
			var ext = img.val().split(".").pop().toLowerCase(); 
			
			if( $.inArray(ext, ["png","jpg","gif"]) === -1 ){
				alert('이미지 파일만 업로드 가능합니다.[png,jpg,gif]');
				return false;
			} 
			var formData = new FormData(); 
			formData.append("upload_path", "publish_<?=$user_info['mem_idx']?>");
			formData.append("imagepath", "join");
			formData.append("Filedata", $("input[name=img_upload]")[0].files[0]);
			formData.append("file_type", "img");
			$("#upload_type").val('img');

			$.ajax({
				type : 'post',
				url : '/se/photo_uploader/popup/file_uploader_ajax.php',
				data : formData,
				dataType: 'json',
				processData : false,
				contentType : false,
				traditional: true,
				async: false,
				timeout: 60000,
				success : function(response) {
					if(response.file_name != "")
					{
						$("#hid_filename").val(response.file_name);
					}
				},
				error : function(error) {
					alert("Upload Error-1 :: img");
					return false;
				}
			});
		}
		
		if ( !$("#hid_filename").val() ) 
		{
			alert('파일을 선택해주세요.');
			return false;
		} 
		
		$("#flag").val('step2'); 
		
		var str_option = $("#frm").serialize();
		$.ajax({
			url: "/std/publish/_Proc/pb_step_actor.php",
			data: str_option,
			type: 'POST',
			dataType: 'json',
			traditional: true,
			timeout: 60000,
			error: function (request, status, error) {
			},
			success: function (response) { 
				if(response.err_code == 0 || response.err_code == 1)
				{
					location.href = 'pb_step3.php';
				}
				else if(response.err_code == -1001)
				{
					alert('등록되어 있는 펀딩이 있습니다.'+response.err_code);
					return false;

				}
				else
				{
					alert('Error -'+response.err_code);
					return false;
				}
			}
		}); 
	}); 

	$("#btn_cancel").on('click', function(e){ 
		
		$("#flag").val('del'); 

		if(confirm('펀딩 발행을 취소하시겠습니까?'))
		{
			var str_option = $("#frm").serialize();
			$.ajax({
				url: "/std/publish/_Proc/pb_step_actor.php",
				data: str_option,
				type: 'POST',
				dataType: 'json',
				traditional: true,
				timeout: 60000,
				error: function (request, status, error) {
				},
				success: function (response) { 
					if(response.err_code == 0 || response.err_code == 1)
					{
						alert('펀딩 발행이 취소 되었습니다.');
						location.href = '/';
					}
					else
					{
						alert('Error -'+response.err_code);
						return false;
					}
				}
			}); 

		}
		
	});
});
 
</script>