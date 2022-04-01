<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";

	if ($_SESSION["REG_MEM_ID"] == "")
	{
		execJava("Invalid Access.", "", "location.replace('/std/join/select_type.php');");
	}  

	$sFileURL = v3chk($_GET['sFileURL'], "", 100);
	if ($sFileURL != "" && $_SESSION["REG_MEM_ID"] != "")
	{
		$C_member	= new Member; 

		unset($args);
		$args['list_type']	= "row";
		$args['mem_id']		= $_SESSION["REG_MEM_ID"];
		$mem_view	= $C_member->get_user_info($args); 
		
		unset($args);
		$args['mode']				= "CI"; 
		$args['mem_idx']			= $mem_view['mem_idx'];
		$args['mem_fileupload1']	= $sFileURL;
		$err_code					= $C_member->set_user_join_fileupload($args);
		
		if ($err_code == 1)
		{
			session_destroy();
			execJava("회원가입이 완료되었습니다.", "", "location.replace('../../index.php');");
		}
	}

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>
</head>
 <body>
<form id="frm" name="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="imagepath" value="join"> 
<input type="hidden" name="callback" value="/std/join/join_personal_end.php">
<input type="hidden" name="callback_func" value="1">
<input type="hidden" name="upload_path" value="join_corporate_<?=$_SESSION["REG_MEM_ID"]?>"> 
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
				<div class="section_title">회원가입 <span>완료</span><p>투자자 회원(법인)</p></div>					
					<div class="idpw_wrap">
						<div class="tabs_container">
							<div class="reset_contents">							
								<div class="find_id_title">
									<p>투자자 회원(법인) 가입이 완료 되었습니다.</p>
									<p>아래의 서류를 하나의 압축파일로 업로드하시면 담당자가 확인 후 승인절차가 진행됩니다.</p>
									<p>승인 완료 후 SUCCESTOCK에서 투자 활동을 진행하실 수 있습니다.</p>
									<h3>필요서류</h3>
									<h3>1) 법인 투자자</h3>
									<ul>
										<li>· 대표자 실명확인증표 : 대표자의 신분증(주민등록증 / 운전면허증 / 여권 사본)<br />
                                                ※ 공동대표의 경우 대표자 모두의 실명확인증표가 필요합니다.</li>
										<li>· 사업자등록증</li>
										<li>· 등기사항전부증명서 (발급일로부터 3개월 내 / 말소사항 포함)</li>
										<li>· 전문 투자자 인증 서류 (법인전문투자자 확인서 or 당연전문투자자 관련 서류)</li>
									</ul>
									<h3>2) 투자조합 투자자</h3>
									<ul>
										<li>· 대표자 실명확인증표 : 대표자의 신분증(주민등록증 / 운전면허증 / 여권 사본)<br />
																					※ 공동대표의 경우 대표자 모두의 실명확인증표가 필요합니다.</li>
										<li>· 조합 고유번호증</li>
										<li>· 조합등록원부 (중소벤처기업부에서 발급, 조합원에 관한 사항 포함)</li>
										<li>· 등기사항전부증명서 (발급일로부터 3개월 내 / 말소사항 포함)</li>
									</ul>
								</div>
								<div class="fileBox">
									<div class="input_tit"><span>파일 등록하기</span></div>
										<input type="text" id="txt_filename" class="fileName" placeholder="파일을 등록해주세요" readonly="readonly">
										<label for="uploadBtn" class="btn_file">찾아보기</label>
										<input type="file" name="Filedata"  id="uploadBtn" class="uploadBtn">
										<div class="input_alert mgb30">
											<p>.ZIP확장자로 된 파일만 등록 가능합니다.<br />스캔 파일 업로드는 향후 마이페이지에서도 가능합니다.</p>
										</div>
								</div><!-- fileBox end -->								
								<div class="fs_form">
									<div class="fs_btn_box">
										<button type="button" class="fs_full_btn" id="btnUpload">파일 업로드</button>
									</div>
								</div>

							</div>
						</div><!-- tabs_container end -->
					</div><!-- idpw_wrap end -->
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

<script type="text/javascript">
	$( function() {

		var uploadFile = $('.fileBox .uploadBtn'); //파일첨부 js
		uploadFile.on('change', function(){
			if(window.FileReader){
				var filename = $(this)[0].files[0].name;
			} else {
				var filename = $(this).val().split('/').pop().split('\\').pop();
			}
			$(this).siblings('.fileName').val(filename);
		});
	});

	$(document).ready( function(){
		
		$("#btnUpload").click(function () {
			var img = $('#uploadBtn');
			var imgFile = img[0].files[0];
			var ext = img.val().split(".").pop().toLowerCase(); 
 
			if( $.inArray(ext, ["zip"]) === -1 ){
				alert('압축 파일만 업로드 가능합니다.');
				$("#txt_filename").val('');
				return false;
			}

			$("#frm").attr("action", "/se/photo_uploader/popup/file_uploader_zip.php");
			$('#frm').submit();
		});
	});
</script>