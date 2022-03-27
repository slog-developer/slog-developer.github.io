<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member	= new Member;
	$C_funding	= new Funding;
	
	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}

	unset($args); 
	
	$args['mem_idx']	= $user_info['mem_idx'];
	$args['list_type']	= "row";
	$mem_info			= $C_member->get_user_info($args); 
	
	if($mem_info['mem_division'] != "3") {	//권한 체크
		execJava("", "", "location.href='/';");
	}

	$isUpdate = false;
	$args['list_type']	= "row";
	$args['funding_issue_status'] = "1";
	$funding_view = $C_funding->get_funding_issue($args);
 
	if(!$funding_view)
	{ 
		execJava("등록되지 않는 펀딩입니다.", "", "location.href='/';");
	} 

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>
</head>
 <body>
<form id="frm" method="post">
<input type="hidden" name="flag" value='step4'>
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
				<div class="temp_container mgt30">
					<div class="temp_contents">
						<div class="temp_section_v1">
							<div class="cont_in_tit">
								<h1>펀딩 투자하기</h1>
								<p class="mgt20">프리미엄 노출은 유료이며 신청 항목에 따라 요금이 차등하여 과금됩니다.</p>
							</div>
							<div class="cont_in_choice mgt20">
								<input type="checkbox" id="banner_v1" name="banner_v1" value="1" />
								<label class="temp_btn temp_v1" for="banner_v1">
									<h1>메인 배너 영역</h1>
									<div class="temp_img"><img src="../images/funding_img/temp_img_v1.png" alt="banner_v1" /></div>
								</label>
								<input type="checkbox" id="banner_v2" name="banner_v2" value="1"/>
								<label class="temp_btn temp_v2" for="banner_v2">
									<h1>1단 배너 영역</h1>
									<div class="temp_img"><img src="../images/funding_img/temp_img_v2.png" alt="banner_v2" /></div>
								</label>
								<input type="checkbox" id="banner_v3" name="banner_v3" value="1"/>
								<label class="temp_btn temp_v3" for="banner_v3">
									<h1>3단 배너 영역</h1>
									<div class="temp_img"><img src="../images/funding_img/temp_img_v3.png" alt="banner_v3" /></div>
								</label>
							</div>
						</div>
					</div>
					<div class="temp_contents mgt20 mgb20" style='display:none;'>
						<div class="temp_section_v2">
							<div class="cont_in_tit">
								<h1>템플릿 선택하기</h1>
							</div>
							<div class="cont_in_choice mgt20">
								<input type="radio" id="template_v1" name="template_v1" value='1' checked/>
								<label class="temp_btn temp_v1" for="template_v1">
									<h1>White</h1>
									<div class="temp_img"><img src="../images/funding_img/temp_img_v4.jpg" alt="template_v1" /></div>
								</label>
								<input type="radio" id="template_v2" name="template_v1" value='2'/>
								<label class="temp_btn temp_v2" for="template_v2">
									<h1>Purple</h1>
									<div class="temp_img"><img src="../images/funding_img/temp_img_v5.jpg" alt="template_v2" /></div>
								</label>
								<input type="radio" id="template_v3" name="template_v1" value='3'/>
								<label class="temp_btn temp_v3" for="template_v3">
									<h1>blue</h1>
									<div class="temp_img"><img src="../images/funding_img/temp_img_v6.jpg" alt="template_v3" /></div>
								</label> 
							</div>
						</div>
					</div>
					<div class="page_button_wrap">
						<button type="button" class="page_agree" id='btn_update'>발행완료</button>
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
<script type="text/javascript"> 
$(document).ready(function() {
	$("#btn_update").on('click', function(e){   

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
				if(response.err_code == 1)
				{
					//alert('발행 신청이 완료되었습니다.');
					location.href = 'pb_end.php';
				}
				else
				{
					alert('Error -'+response.err_code);
					return false;
				}
			}
		});
		
	});	
});
 
</script>