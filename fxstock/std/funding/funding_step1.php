<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member = new Member; 
	$C_funding	= new Funding;
	
	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("로그인이 필요합니다.", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}
	
	unset($args);
	$args['mem_idx']	= $user_info['mem_idx'];
	$args['list_type']	= "row";
	$mem_info			= $C_member->get_user_info($args);

	if($mem_info["mem_invest_type_chk"] == "0")
	{
		execJava("관리자 승인이 필요합니다.(투자자 유형확인)", "", "history.back();");
	}
	
	$funding_idx	= $_REQUEST['funding_idx'];

	unset($args);
	$args['mem_idx']		= $user_info['mem_idx'];
	$args['funding_idx']	= $funding_idx;
	$invest_chk = $C_funding->get_funding_invest_cnt($args);

	if($invest_chk != "0")
	{
		execJava("이미 펀딩 투자한 상품입니다.", "", "history.back();");
	}

	unset($args);
	$args['list_type']		= "row";
	$args['funding_idx']	= $funding_idx;
	$funding_view = $C_funding->get_funding_issue($args);

	if($funding_view['remain_tot_cnt'] < 1 )
	{
		if ($funding_view['funding_type'] == "1")
		{
			execJava("남아있는 발행 주식가 없습니다.", "", "history.back();");
		}else
		{
			execJava("남아있는 발행 구좌가 없습니다.", "", "history.back();");
		}
	}

	if($funding_view == "")
	{
		execJava("", "", "location.href='/'");
	}
 
	$_SESSION["SESSION_FUNDING_IDX"] = $funding_idx;
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
							<h1 class="red mgb40">투자위험 안내</h1>
							<p>본 위험고지는 자본시장과 금융투자업에 관한 법률 (이하 “자본시장법”이라고
							함) 제 117조의 7 제4항에 따라 귀하가 SUCCESTOCK에 청약을 주문하기 
							전에 투자의 위험을 사전에 충분히 인지할 수 있도록 SUCCESTOCK이 
							귀하에게 교부하는 것입니다.</p>
						</div>
						<div class="funding_step_terms mgt30">
							<p>1. 귀하는 투자대상인 금융투자상품은 자본시장법에 따른 “증권”에 해당하므로 원본손실의 위험성이 있으
							며, 청약증거금 등 투자한 자금의 원본을 회수할 없음에 따른 손실의 위험이 있음을 이해합니다. 또한 귀하
							가 예상하거나 기대하는 수익의 일부 또는 전부를 얻지 못할 수 있습니다.</p><br />
							<p>2. 귀하는 SUCCESTOCK의 홈페이지에 게재(정정)된 모집되는 증권의 발행조건, 발행인의 재무상태가 
							기재된 서류 및 사업계획서의 내용, 증권의 취득에 따른 투자위험요소 등을 충분히 확인하였으며, 청약의 
							주문 시 회사는 투자 위험의 고지 등에 관하여 별도로 정한 방법에 의하여 통지하는 내용에 대하여 확인
							합니다.</p><br />
							<p>3. 귀하는 금번에 발행되는 비상장 증권의 발행목적은 한국거래소의 상장에 있는 것은 아니므로 증권의 환
							금성에 큰 제약이 있음과 귀하가 예상하는 회수금액에 대한 일부 또는 전부를 회수할 수 없는 위험이 있음을
							이해하며, 귀하가 이를 감당할 수 있음을 확인합니다.</p><br />
							<p>4. 귀하는 시장의 상황, 제도의 변경이 있을 수 있으며, 자본시장법 등 관련법규에 근거하여 투자의 한도에 
							제한이 있는 경우 이를 준수하여야 함을 이해합니다.</p><br />
							<p>5. 귀하는 자본시장법 제117조의10 제7항에 따라 전문투자자(벤처캐피탈 등)에 대한 매도 등 예외적인 경우
							를 제외하고는 원칙적으로 6개월간 전매가 제한된다는 점을 이해합니다.</p><br />
							<p>6. 귀하는 정부가 투자대상인 증권과 관련하여 게재된 사항이 진실 또는 정확하다는 것을 인정하거나 해당 
							증권의 가치를 보증 또는 승인한 것이 아니라는 점과 게재된 투자정보에 관한 사항은 청약기간 종료 전에
							정정될 수 있음을 확인합니다.</p><br />
							<p>7. 귀하는 청약기간 중에만 한정하여 청약의 철회가 가능하며(청약기간 종료일 이후에는 청약의 철회 불가), 
							청약기간의 종료 시 모집금액이 발행인이 목표한 모집예정금액의 80%미달하는 경우 증권의 발행은 취소된
							다는 점을 이해합니다.</p><br />
							<p>8. 귀하는 아래 사항들에 대하여도 이해하였음을 확인합니다.<br />
								<span>1) SUCCESTOCK은 온라인소액증권 청약과 관련하여 별도의 수수료는 징수하지 않습니다.</span><br />
								<span>2) 발행인이 증권의 발행조건과 관련하여 조기상환조건을 설정한 경우 이에 대한 구체적인 내용을 홈페이
								지를 통해 반드시 확인하여야 합니다.</span><br />
								<span>3) SUCCESTOCK은 자본시장법상의 온라인소액투자중개업자로서의 지위에서 온라인소액증권발행인과
								온라인소액투자중개계약을 체결하여 위 발행인이 발행하는 증권에 대한 청약 거래를 중개하므로, 모집예
								정금액에 도달한다고 하더라도 직접 증권의 발행하거나 주금을 납입 받지 않습니다.</span><br />
								<span>4) 청약의 우대차별사유 : SUCCESTOCK은 발행인의 요청에 따라 합리적이고 명확한 기준(선착순 등)에 따라 투자자의 자격 등을 제한할 수 있습니다.
									 이 경우 귀하는 위 기준과 조건에 따라 청약거래에 있어 차별을 받게 될 수 있습니다.</span><br /><br />

							위 사항들은 청약의 주문 거래에 수반되는 위험∙제도 및 청약의 주문 거래와 관련하여 귀하가 알아야 할 사항을 간략하게 서술한 것으로 귀하의 위 거래와 관련하여
							발생될 수 있는 모든 위험과 중요 사항을 전부 기술한 것은 아닙니다. 따라서 상세한 내용은 SUCCESTOCK 및 관계법규를 통하여 확인하여야 합니다.
							또한 이 고지서는 청약의 주문 관련 법규 등에 우선하지 못한다는 점을 양지하시기 바랍니다.
							</p>
							<div class="gradient_v4"></div>
						</div>						
					</div><!-- funding_step_wrap end -->
					<div class="funding_btn_form">
						<button type="button" class="fs_full_btn" onclick="location.href='/std/funding/funding_step2.php'">투자계속하기</button>
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
