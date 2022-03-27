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
	<div id="container"><!-- 각 페이지 별 전체를 감싸는 div -->
		<div class="fs_article"><!-- 슬라이드 영역 및 바로가기 링크 영역 -->
            <!-- img slide BG -->
			<div class="slide_bg_section">
				<div class="row_wrap">
					<div class="img_banner_wrap thumb_img">	
						<div class="posA tac_section tac_green" style="z-index:2;">
							<span class="tac_tit">주식형</span>
							<span class="tac_mark">
								<button type="button" class="star_toogle" onclick="location.href='#'"><i class="fas fa-star book_mark"></i></button>
							</span>										
						</div>
						<ul>											
							<li><img src="/std/images/card_ui/3.jpg" alt="썸네일1" /></li>
							<li><img src="/std/images/card_ui/4.jpg" alt="썸네일2" /></li>
							<li><img src="/std/images/card_ui/5.jpg" alt="썸네일3" /></li>
							<li><img src="/std/images/card_ui/6.jpg" alt="썸네일4" /></li>
						</ul>
					</div>
					<div class="slide_content_text_v2">
						<span>오늘 00명이 조회한 펀딩입니다.</span>
						<h1>(주)스마트 관리</h1>
						<p>FXRENT 서비스 관련 특허를 보유한 국내 유일 FX마진거래 손익 렌탈 사업!</p>
					</div>                            
				</div>
			</div><!-- slide_bg_section -->
			<div class="sub_tab" id="sub_tab_form">
				<ul class="sub_tab_list">
					<li><a href="/std/funding/recruit_funding.php">모집내용</a></li>
					<li><a href="/std/funding/company_info.php">회사정보</a></li>
					<li class="purple_bottom"><a href="/std/funding/company_news_list.php">회사소식</a></li>
					<li><a href="/std/funding/company_board_list.php">게시판</a></li>
				</ul>
			</div>
			<div class="funding_container">
				<div class="recruit_wrap">
					<div class="recruit_container_1">
						<div class="notice_sub_tit mgb20 ">
							<h4>글 제목이 노출되는 영역</h4>
							<p><i class="far fa-clock"></i>
							2017.01.15 <span>15:31</span></p>
						</div>
						<div class="notice_section">
						What is Lorem Ipsum?
						Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
						There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc. <br /><br />
						Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
						</div>
						<div class="notice_sub_tit mgb20"></div>
						<button type="button" class="certi_box floatR" onclick="window.location.href='/std/funding/company_news_list.php'">목록보기</button>						
					</div>
				</div><!-- recruit_wrap end -->
				<div class="chat_wrap" id="chat_wrap_form">
					<h1>100명 참여</h1>
					<div class="chat_progress_section">
						<div class="progressBar_v3">
							<div class="progress_v3 pg_body_v3"></div>
						</div>
						<div class="progress_value_v3">50%</div>
					</div>
					<div class="recruit_content">
						<h4>목표금액 : 600,000,000원</h4>
						<h4 class="floatR">12일 남음</h4>
						<ul class="chat_list mgt15">
							<li><p>현재모집금액</p><span>420,000,000원</span></li>
							<li><p>연 이자율</p><span>8%</span></li>
							<li><p>구좌 당 금액</p><span>10,000원</span></li>
							<li><p>채권 만기일</p><span>2019.08.10</span></li>
							<li><p>투자 단계</p><span>Series A</span></li>
							<li><p>현재 기업가치</p><span>1,000,000,000원</span></li>							
						</ul>
						<p class="bold">(본 펀딩 발행 전 주식 수 x 주식 금액 합산)</p>
					</div><!-- recruit_content end -->
					<div class="chat_box">
						<div class="chat_input">
							<input type="text" class="chat_text" placeholder="메시지를 입력해주세요." maxlength="50"/><i class="far fa-smile pd6"></i>
						</div>
					</div>
					<div class="fs_btn_box mgt10">
						<button type="button" class="certi_box_v4">투자하러가기</button>
						<button type="button" class="chat_list_btn"><i class="fas fa-list-ul white"></i></button>
					</div>
				</div>
				<a class="top_btn"><i class="fas fa-angle-up"></i></a>
			</div>			
		</div><!-- fs_article end -->
		<div class="fs_article">
			<div class="row_wrap">
				<div class="fs_section full_section">
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
</body>
</html>

<!-- progress script -->
<script>
$(document).ready(function(){
	$('.pg_body_v3').animate({width:'50%'},2000);
}); 
</script>