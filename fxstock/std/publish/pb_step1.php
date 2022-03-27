<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member	= new Member;
	$C_funding	= new Funding;

	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}
	
	if($user_info['mem_division'] != "3") {	//권한 체크
		execJava("발행 권한이 없습니다.", "", "history.back();");
	}

	unset($args);
	$args['mem_idx']	= $user_info['mem_idx'];
	$args['list_type']	= "row";

	$funding_view = $C_funding->get_funding_issue($args);
	
	if($funding_view['funding_issue_status'] == "0")
	{ 
		execJava("작성 중인 내용이 있습니다.", "", "location.href='pb_step3.php';");
	}
	else if($funding_view['funding_issue_status'] > "0" && $funding_view['funding_issue_status'] <= "4")
	{
		execJava("심사중인 펀딩이 있습니다.", "", "location.href='pb_step2.php';");
	}
	else if($funding_view['funding_issue_status'] == "100")
	{
		execJava("이미 발행중인 펀딩이 있습니다.", "", "location.href='/';");
	}
	else if($funding_view['funding_issue_status'] == "5")
	{
		$funding_view = null;
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
				<div class="section_title">펀딩 발행하기</div>
				<div class="tabs_container mgb30">
					<div class="funding_step_wrap">
						<div class="funding_contents_v4">
							<h2>증권형 크라우드 펀딩 신청 조건을 확인하신 후 펀딩 신청을 하시면 SUCCESTOCK의 담당자 확인 후 연락 드립니다.</h2>

							<h3>• 증권형 크라우드 펀딩 신청 조건</h3>
							<h4>1. 법인 사업자만 증권형 크라우드 펀딩 신청이 가능합니다. <br />(개인 사업자는 법인전환 후 신청하여야 합니다.)</h4>
							<h4>2. 정관 명시 : 기업은 정관 또는 이에 준하는 것에서 제3자배정에 관한 근거와 조항이 있어야 합니다. 이때 SUCCESTOCK을 통해서만 제3자배정은을 할 수 있다는 내용이 포함되어야 합니다.</h4>
							<h4>3. 주주총회 또는 이사회 결의</h4>
							<p>증권형 크라우드 펀딩을 신청하는 기업은 펀딩 신청 전에 주주총회 또는 이사회에서 
							신주 발행 및 모집예정 금액과 제3자배정에 관한 결의를 완료하고, 기존 주주들에게 
							통지를 완료하여야 합니다.</p>
							<h4>4. 증권형 크라우드 펀딩을 신청하는 기업의 주식은 한국예탁결제원에 등록된 통일주권이어야 합니다.</h4>
							<h4>5. 아래의 업종은 증권형 크라우드 펀딩 신청이 제한됩니다.</h4>
							<p>금융 및 보험업 / 부동산업 / 숙박업 (호텔업, 휴양콘도 운영업, 기타 관광숙박시설
							 운영업은 가능) / 음식점업 (상시 근로자 20명 이상의 음식점업은 가능) / 무도장 운영업 / 
							 골프장 및 스키장 운영업 / 기타 갬블링 및 베팅업</p>
							 <h4>6. 증권형 크라우드 펀딩을 통해 발행할 수 있는 한도는 연간 7억원입니다.</h4>

							 <h3>• 증권형 크라우드 펀딩 신청 절차</h3>
							 <h4>SUCCESTOCK에서 증권형 크라우드 펀딩을 신청하시면 아래와 같은 절차를 진행 후 펀딩 등록이 완료 됩니다.</h4>
							 <p class="blue">신청서 작성 > SUCCESTOCK 담당자 확인 > 신청 기업 실사 > 펀딩 등록 완료</p>
							 <h4>실사 항목</h4>
							 <h4>1. 필수 서류 제출 확인 (모든 서류는 3개월 이내에 발급된 자료여야합니다.)</h4>
							 <p>※ 신청 기업의 정관은 증권형 크라우드 펀딩 발행을 위한 내용 및 별도의 SUCCESTOCK과 협의된 내용으로 수정 되었는지 확인합니다.</p>
							 <h4>2. 비즈니스 모델 및 차별성, 핵심역량 등 확인</h4>
							 <p>※ 사업내용 영역에 위의 3가지 항목이 모두 포함되어야 합니다.</p>
							 <h4>3. 기업정보 메뉴에 모든 항목이 작성되어 있는지 확인 및 잘못 등록된 정보는 없는지 확인</h4>
						</div>
					</div><!-- funding_step_wrap end -->
					<div class="funding_btn_form">
						<button type="button" class="fs_full_btn" onclick="location.href='/std/publish/pb_step2.php'">신청하기</button>
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
