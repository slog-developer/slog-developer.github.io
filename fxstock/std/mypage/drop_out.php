<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member = new Member; 
	$C_funding	= new Funding;

	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}

	unset($args);
	$args['mem_idx']	= $user_info['mem_idx'];
	$args['list_type']	= "row";
	$mem_info			= $C_member->get_user_info($args);

	$hid_auth_chk	= v3chk($_REQUEST['hid_auth_chk'], "int");

	if($hid_auth_chk == 1)
	{
		unset($data_args);
		$rtn	= 0;
		$data_args = array(
						"mem_idx"=>$user_info['mem_idx'],
						"count"=>true,
						"list_type"=>"single",
						"start_dt"=>$start_dt,
						"end_dt"=>$end_dt
						);

		$tot_cnt	= $C_funding->get_funding_invest_info($data_args);

		$tot_cnt = 0;

		if($tot_cnt > 0)
		{
			$rtn = -1001;
		}
		else
		{
			unset($args);
			$args['mem_idx']	= $user_info['mem_idx'];
			$rtn	= $C_member->set_userinfo_Drop($args);
		}
		
		echo json_encode($rtn);  
		exit;
	}

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>
</head>
 <body>
<form id="frm" method="post">
<input type="hidden" id="hid_auth_chk" name="hid_auth_chk" >
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
				<div class="mypage_title">회원탈퇴</div>
				<div class="mypage_wrap">
					<div class="tabs_box_v2">
						<? include_once "_mypage_tab.php" ?>						
					</div>
					<div class="drop_out_container">
						<div class="drop_out_contents">
							<h2 class="mgb20">탈퇴정보</h2>
							<div class="info_list_contents">
								<div class="add_tit_input_section_v3">
									<div class="input_tit"><span>아이디(이메일)</span></div>
									<div class="fs_input_v3 mgb10">
										<input type="text" class="fs_input_text white_bg" value="<?=$user_info['mem_id']?>" disabled />
									</div>
								</div>
								<div class="add_tit_input_section_v3">
									<div class="input_tit"><span>이름</span></div>
									<div class="fs_input_v3 mgb10">
										<input type="text" class="fs_input_text white_bg" value="<?=$user_info['mem_name']?>" disabled />
									</div>
								</div>
								<div class="add_tit_input_section_v3">
									<div class="input_tit"><span>휴대폰 번호</span></div>
									<div class="fs_input_v3 mgb10">
										<input type="text" style="width:150px;" class="ph_num input_pn white_bg" value="<?=$mem_info['dec_mem_hp']?>" disabled />			
										<button type="button" class="certi_box floatR" id='btn_hp_authSend'>인증번호발송</button>
										<span class="floatR mgr10" style='display:none;' id='span_1'><i class="fas fa-check green"></i></span>
									</div>
								</div>
								<div class="add_tit_input_section_v3" id='div_hp_auth' style='display:none'>
									<div class="input_tit"><span>인증번호 입력</span></div>
									<div class="fs_input_v3 mgb10 pn_box">	
										<input type="text" class="input_pn" id='txt_mem_hp_authCode' name='txt_mem_hp_authCode' placeholder="인증번호를 입력해주세요" numberOnly />
										<button type="button" class="certi_box floatR" id='btn_sms_auth'>인증하기</button>
										<span class="floatR mgr10" id="span_hp_auth_green" style="display:none;"><i class="fas fa-check green"></i></span>
										<span class="floatR mgr10" id="span_hp_auth_red" style="display:none;"><i class="fas fa-times red"></i></span>
									</div>
								</div>
								<div class="add_tit_input_section_v3" id="div_hp_auth" style="display:none;">
									<div class="input_tit"><span>비밀번호</span></div>
									<div class="fs_input_v3 pw_box">
										<input type="password" name="txt_pwd" name="txt_pwd" class="fs_input_text input_pw" placeholder="비밀번호를 입력해주세요" />
										<span class="floatR mgr10" id="span_pwd_green" style="display:none;"><i class="fas fa-check green"></i></span>
										<span class="floatR mgr10" id="span_pwd_red" style="display:none;"><i class="fas fa-times red"></i></span>
									</div>
								</div>							
							</div><!-- info_list_contents end -->
							<div class="drop_out_section">
								<h2 class="mgb20 mgt50">탈퇴안내</h2>
								<p>SUCCESTOCK을 이용해주신 고객님께 감사드립니다.</p>
								<p>아래 <span class="blue">유의사항</span>을 확인하신 후 탈퇴 가능 상황에서 동의하시면 탈퇴 처리가 진행됩니다.</p>
								<ul class="drop_out_terms">
									<li><i class="fas fa-check purple_sub_v1 mgr10"></i><span>참여/모집 중인 프로젝트</span>가 있을 경우 탈퇴가 불가능합니다.</li>
									<li><i class="fas fa-check purple_sub_v1 mgr10"></i>탈퇴 후 <span>1개월 이내에</span> 동일한 정보로 재가입이 불가능합니다.</li>
									<li><i class="fas fa-check purple_sub_v1 mgr10"></i>탈퇴 시 보유하고 계신 <span>포인트는 즉시 소멸되며</span> 복구되지 않습니다.</li>
									<li><i class="fas fa-check purple_sub_v1 mgr10"></i>탈퇴하시더라도 SUCCESTOCK에서 펀딩을 통해 보유하고 계신 주식은 <span>6개월 이내에 매매가 불가합니다.</span></li>
								</ul>
								<div class="terms_tit_checkbox mgt30">
									<input class="checkbox" type="checkbox" name="chk_attention" id="chk_attention" />
									<label for="chk_attention"></label>
									<label for="chk_attention"></label><span class="save_id_text">유의사항을 확인하였으며, 동의합니다.</span>
								</div>
							</div>
							<div class="page_button_wrap mgt30">
								<button type="button" class="page_agree" id='btn_drop'>탈퇴하기</button>
								<button type="button" class="page_close" onclick="location.href='/'">취소</button>
							</div>
							
						</div><!-- drop_out_contents end -->
					</div><!-- tabs_container end -->
				</div><!-- mypage_wrap end -->				
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
<script>
	$(document).ready(function(){
		$("#btn_hp_authSend").click(function () {
			var sendCnt = _authCode.SendSMS("<?=$user_info['mem_id']?>", "<?=$mem_info['dec_mem_hp']?>", 17,1);

			if(sendCnt)
			{
				$("#div_hp_auth").css("display","");
			}
		});

		
		$("#btn_sms_auth").click(function () {
			if ($("#txt_mem_hp_authCode").val() == "")
			{
				alert("인증번호를 입력해주세여");
				$("#txt_mem_hp_authCode").focus();
				$("#span_hp_auth_red").css("display","");
				return false;
			}

			var rtn = _authCode.CheckCode_SMS("<?=$user_info['mem_id']?>", "<?=$mem_info['dec_mem_hp']?>", $("#txt_mem_hp_authCode").val(), 1);

			if(rtn)
			{
				$("#btn_sms_auth").css("display","none");
				$("#btn_hp_authSend").css("display","none");
				$("#span_hp_auth_red").css("display","none");
				$("#span_hp_auth_green").css("display","");
				$("#hid_auth_chk").val(1);
			}
		});
		
		$("#btn_drop").click(function () {
			if($("#hid_auth_chk").val() != 1)
			{
				alert('휴대폰 인증을 해주세요.');
				return false;
			}

			if($('input:checkbox[id="chk_attention"]').is(":checked") != true)
			{
				alert("유의사항 확인에 동의해 주세요");
				return false;
			}

			if(confirm('회원 탈퇴 하시겠습니까?'))
			{
				var str_option = $("#frm").serialize();
				$.ajax({
					url: "drop_out.php",
					data: str_option,
					type: 'POST',
					dataType: 'json',
					traditional: true,
					timeout: 60000,
					error: function (request, status, error) {
					},
					success: function (response) {
						
						if(response == 1)
						{
							alert('회원 탈퇴 처리 되었습니다.');
							location.href = '/std/account/login_out.php';
						} 
						else if(response == -1001)
						{
							alert('펀딩 참여가 있으면 탈퇴 할 수 없습니다.');
						}
					}
				}); 
			} 
			
		});
		
	});
</script>