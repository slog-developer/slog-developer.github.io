<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
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
			<div class="user_page_container">
				<div class="cs_title">펀딩 발행신청 완료</div>
				<div class="cs_wrap">
					<div class="pb_end_wrap Wfull mgb50">
						<p class="fs_20 mgb40"><i class="fas fa-star fs_20"></i>&ensp;펀딩 발행신청 프로세스<span style="color: #005cea;">&nbsp;(최대 약 한 달 소요)</span></p>
						<div class="pb_end_box">							
							<p>신청서 작성&emsp;&ensp;<i class="fas fa-chevron-right"></i>&emsp;&ensp;SUCCESTOCK 담당자 확인&emsp;&ensp;<i class="fas fa-chevron-right"></i>&emsp;&ensp;신청 기업 실사&emsp;&ensp;<i class="fas fa-chevron-right"></i>&emsp;&ensp;펀딩 등록 완료</p>
						</div>
						<div class="pb_end_txt">
							<p><span class="fs_20">펀딩 발행신청이 완료되었습니다.</span><br />
							SUCCESTOCK 담당자가 내용 확인 후 이상이 없을 경우 귀사의 실사를 위해 <span class="red">최대 2주 이내</span>에 연락을 드릴 예정입니다.<br />
							문의사항은 SUCCESTOCK 문의 주시기 바랍니다.</p>
						</div>
						<div class="cs_box">
							<i class="fas fa-headset"></i>
							<p style="font-size:20px; vertical-aling:super;">|</p>
							<p>SUCCESTOCK 고객센터 | 02 - 3429 - 3255 <br />상담 가능시각 | 평일 09:00 ~ 18:00</p>							
						</div>
						<div class="page_button_wrap">
							<button type="button" onclick="window.location.href='/index.php'" class="page_agree">확인</button>
						</div>
					</div>
				</div><!-- cs_wrap end -->				
			</div><!-- user_page_container end -->
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
 </body>
</html>
