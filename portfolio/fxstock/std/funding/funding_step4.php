<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member = new Member; 
	$C_funding	= new Funding;
	
	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("로그인이 필요합니다.", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}
	
	$funding_idx = $_SESSION["SESSION_FUNDING_IDX"];


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
							<h1 class="mgb50">투자하기</h1>
						</div>
						<div class="funding_contents_v4">
							<div class="add_tit_input_section">
								<div class="funding_txt mgb50">
									<h1 class="deepblue mgb20">투자 신청이 완료 되었습니다.</h1>
									<p>투자 내용이 예탁결제원에 전송되어 최종 투자가 완료되기까지 영엽일 <br />
									기준 2~3일 소요 됩니다. <br /><br />
									투자하신 펀딩의 성공 여부는 마이페이지 > 펀딩 참여내역에서 확인 가능합니다.</p>
								</div>
								<div class="input_tit"><span>주식 수량</span></div>
								<div class="fs_input_txt_v2 mgb10"><h1 class="floatR pdr20"><?=number_format($_SESSION["SESSION_INVEST_CNT"])?><?=$funding_str_1?></h1></div>
								<div class="input_tit"><span>총 투자 금액</span></div>
								<div class="fs_input_v8 mgb10">
									<div class="funding_num floatR"><p class="mgr10"><?=number_format($_SESSION["SESSION_INVEST_TOTAL"])?></p><span>원</span></div>
								</div>
							</div>
						</div>
					</div><!-- funding_step_wrap end -->
					<div class="funding_btn_form">
						<button type="button" class="fs_full_btn" onclick="location.href='/'">확인</button>
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
