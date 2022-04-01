<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";

	if ($_SESSION["REG_MEM_ID"] == "")
	{
		execJava("Invalid Access.", "", "location.replace('/std/join/select_type.php');");
	}  

	$callback_func	= v3chk($_GET['callback_func'], "", 1);
	if ($callback_func != "" && $_SESSION["REG_MEM_ID"] != "")
	{
		$C_member	= new Member; 

		$args['list_type']	= "row";
		$args['mem_id']		= $_SESSION["REG_MEM_ID"];
		$mem_view	= $C_member->get_user_info($args); 
		
		unset($args);
		for ($ii = 1; $ii <= 7; $ii++){
			$args['mem_fileupload'.$ii.'']	= v3chk($_GET['sFileName_'.$ii.''], "", 100);
		}
		$args['mode']				= "II"; 
		$args['mem_idx']			= $mem_view['mem_idx'];

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
<input type="hidden" name="upload_path" value="join_issuer_<?=$_SESSION["REG_MEM_ID"]?>"> 
<input type="hidden" name="callback" value="/std/join/join_issuer_end.php">
<input type="hidden" name="callback_func" value="1">

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
				<div class="section_title">회원가입 <span>완료</span><p>기업 회원(발행인) | 회사법인</p></div>					
					<div class="idpw_wrap">
						<div class="tabs_container">
							<div class="reset_contents">							
								<div class="find_id_title">
									<p>기업 회원(발행인) 가입이 완료 되었습니다.</p>
									<p>아래의 서류를 하나의 압축파일로 업로드하시면 담당자가 확인 후 승인절차가 진행됩니다.</p>
									<p>승인 완료 후 SUCCESTOCK에서 투자 활동을 진행하실 수 있습니다.</p>
									<h3>필요서류</h3>
									<h3>1) 법인 발행인</h3>
									<ul>
										<li>· 대표자 실명확인증표 : 대표자의 신분증(주민등록증 / 운전면허증 / 여권 사본)<br />
                                                ※ 공동대표의 경우 대표자 모두의 실명확인증표가 필요합니다.</li>
										<li>· 사업자등록증</li>
										<li>· 등기사항전부증명서 (발급일로부터 3개월 내 / 말소사항 포함)</li>
										<li>· 전문 투자자 인증 서류 (법인전문투자자 확인서 or 당연전문투자자 관련 서류)</li>
									</ul>
								</div>
								

								<div class="fileBox_multi">
									<div class="input_tit"><span>대표자 실명 확인 증표</span></div>
									<div class="ln57"><input type="file" name="Filedata_7"  id="txt_filename_7" class="fileName"></div>
								</div>

								<div class="fileBox_multi">
									<div class="input_tit"><span>사업자 등록증 등록</span></div>
									<div class="ln57"><input type="file" name="Filedata_1"  id="txt_filename_1" class="fileName"></div>
								</div>
								
								<div class="fileBox_multi">
									<div class="input_tit"><span>법인 통장 등록</span></div>
									<div class="ln57"><input type="file" name="Filedata_2"  id="txt_filename_2" class="fileName"></div>
								</div>
								
								<div class="fileBox_multi">
									<div class="input_tit"><span>법인 등기부 동본 등록</span></div>
									<div class="ln57"><input type="file" name="Filedata_3"  id="txt_filename_3" class="fileName"></div>	
								</div>
								
								<div class="fileBox_multi">
									<div class="input_tit"><span>정관 전문 등록</span></div>
									<div class="ln57"><input type="file" name="Filedata_4"  id="txt_filename_4" class="fileName"></div>	
								</div>
								
								<div class="fileBox_multi">
									<div class="input_tit"><span>재무재표 등록</span></div>
									<div class="ln57"><input type="file" name="Filedata_5"  id="txt_filename_5" class="fileName"></div>	
								</div>								
								
								<div class="fileBox_multi">
									<div class="input_tit"><span>투자 설명서 등록</span></div>
									<div class="ln57"><input type="file" name="Filedata_6"  id="txt_filename_6" class="fileName"></div>
									<div class="input_alert mgb30">
										<p>스캔 파일 확장자는 JPG,PNG,BMP,PDF 파일만 등록 가능합니다.<br />신분증 스캔 파일 업로드는 향후 마이페이지에서도 가능합니다.</p>
									</div>
								</div>

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
	$(document).ready( function(){
		
		$("#btnUpload").click(function () {
			
			<?for ($ii = 1; $ii <= 6; $ii++){?>
				<?
					if ($ii == 1)
					{
						$str = "사업자 등록증";
					}
					else if ($ii == 2)
					{
						$str = "법인 통장";
					}
					else if ($ii == 3)
					{
						$str = "법인 등기부 동본";
					}
					else if ($ii == 4)
					{
						$str = "정관 전문";
					}
					else if ($ii == 5)
					{
						$str = "재무재표";
					}
					else if ($ii == 6)
					{
						$str = "투자 설명서";
					}
					else if ($ii == 7)
					{
						$str = "대표자 실명 확인 증표";
					}
				?>
				var img_<?=$ii?> = $('#txt_filename_<?=$ii?>');
				var imgFile = img_<?=$ii?>[0].files[0];
				var ext = img_<?=$ii?>.val().split(".").pop().toLowerCase(); 
				if( $.inArray(ext, ["png","jpg","bmp", "pdf"]) === -1 ){
					alert('이미지 파일만 업로드 가능합니다.[<?=$str?>]');
					$("#txt_filename_<?=$ii?>").val('');
					return false;
				}

			<?}?>

			$("#frm").attr("action", "/se/photo_uploader/popup/file_uploader_multi.php");
			$('#frm').submit();
		});

	});
</script>