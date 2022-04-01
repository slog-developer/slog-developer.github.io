<? 
	include_once FX_PATH."/CLASS/Stats.lib.php";
		
	$C_stats = new Stats;
	unset($args);
	$args['mem_idx']	= $user_info['mem_idx'];
	$args['mem_division']	= $user_info['mem_division'];
	$funding_cnt = $C_stats->get_user_funding_cnt($args);
 
?>
<div class="gnb_section">
	<div class="logo">
		<a href="/index.php"><img src="/std/images/logo.png" alt="logo" /></a>
	</div>
	<nav class="fs_gnb">
		<ul>
			<li><a href="/std/funding/join_funding.php" class="fs_lnb">펀딩참여하기</a></li>
			<li><a href="/std/publish/pb_start.php" class="fs_lnb">펀딩발행하기</a></li>
			<li><a href="/std/funding/company_list.php" class="fs_lnb">기업리스트</a></li>			
			<li><a href="/std/guide/guide_01.php" class="fs_lnb">이용가이드</a></li>
			<li>
				<a href="javascript:void(0);" class="fs_lnb"><i class="fas fa-ellipsis-h"></i></a>
				<ul class="arrow_box">
					<li><a href="/std/company/about_ss.php">SUCCESTOCK 소개</a></li>
					<li><a href="/std/board/board.php?board_code=notice">공지사항</a></li>
					<li><a href="/std/service/faq.php">고객센터</a></li>										
				</ul>
			</li>			
		</ul>
	</nav>
	<div class="lojo_secetion">
		<div class="login_link">
			<ul class="login_lnb">				
				<li>
					<!-- 로그인 시에만 fs_lnb_user_info 클래스가 붙는다. --> 
					<? if ($user_info['mem_idx'] == ""){ ?>
						<a href="/std/account/login.php">로그인</a>
					<?}else{?>
						<?
							$_url_mypage = "";
							$_hearder_name = "";
							
							if ($user_info['mem_division'] == "1" or $user_info['mem_division'] == "2")
							{
								$_url_mypage = "/std/mypage/mypage_info.php";
								$_hearder_name = $user_info['mem_name'];
							}
							else if ($user_info['mem_division'] == "3")
							{
								$_url_mypage = "/std/mypage/issuer_info.php";
								$_hearder_name = $_SESSION['mem_corporation_name'];
							}
						?>
						<a href="javascript:void(0);" class="purple_main"><?=$_hearder_name?></a>님</a>
						<ul class="arrow_box arrow_user_info">
							<li class="ar_user_name">
								<span class="ar_name"><a href="javascript:void(0);" class="purple_main"><?=$_hearder_name?>님</a></span>
								<span class="ar_logout"><a href="/std/account/login_out.php" class="fs_lnb_user_info">로그아웃</a></span><br />
							</li> 
							<li><span><a href="<?=$_url_mypage?>"><i class="fas fa-user"></i>마이페이지</a></span></li>
							<?if ($user_info['mem_division'] == "1" or $user_info['mem_division'] == "2"){?>
								<li><span><i class="fas fa-clipboard-list"></i>참여 펀딩 수 : <b><?=$funding_cnt['ing_invest_tot']?>/<?=$funding_cnt['invest_tot']?></b></span></li>
							<?}else{?>
								<li><span><i class="fas fa-clipboard-list"></i>발행 펀딩 수 : <b><?=$funding_cnt['invest_tot']?></b></span></li>
							<?}?>
						</ul>
					<?}?>
				</li>
				<? if ($user_info['mem_idx'] == ""){ ?>
					<li><a href="/std/join/select_type.php">회원가입</a></li>
				<?}?>
			</ul>
		</div>	            
	</div>
</div>