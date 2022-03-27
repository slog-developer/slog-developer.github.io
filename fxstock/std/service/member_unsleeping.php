<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php"; 
	include_once FX_PATH."/CLASS/Member.lib.php";
	require_once FX_PLUGIN_PATH.'/captcha/securimage.php';	//캡차
	
	$C_member = new Member;
	if($C_member->check_login($user_info)) {	//로그인 되어 있는 경우 index 페이지로 이동
		execJava("이미 로그인되어 있습니다.", "", "location.href='/';");
	}
	session_destroy();

	$returnUrl = v3chk($_REQUEST['returnUrl'], "", 200);
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
			<div class="unsleeping_container">
				<div class="unsleeping_form">
					<h1>휴면 회원 해제하기</h1>
					<p>
					회원님은 yyyy년 mm월 dd일 이후로 1년 이상 사용 이력이 없어<br>
					휴면 상태로 전환 되었습니다.<br>
					휴면 상태를 해제하시려면 회원가입 시 등록하신 정보를 입력하여 주시기 바랍니다.
					</p>
					<div class="my_in_content_v3">
						<div class="input_tit"><span>이름</span></div>
						<div class="fs_input_v3">
							<input type="text" class="fs_input_text input_name white_bg"  />
						</div>
					</div>
					<div class="my_in_content_v3">
						<div class="input_tit"><span>비밀번호</span></div>
						<div class="fs_input_v3">
							<input type="password" class="fs_input_text input_pw white_bg"  />
						</div>
					</div>
					<div class="my_in_content_v3">
						<div class="input_tit"><span>핸드폰 번호</span></div>
						<div class="fs_input_v3">
							<input type="text" class="fs_input_text input_pn white_bg"  />
						</div>
					</div>
					<div class="page_button_wrap">
						<button type="button" onclick="window.location.href=''" class="page_agree">휴면 해제하기</button>
					</div>
				</div>
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
