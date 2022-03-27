<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";

	$C_member = new Member;

	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}

	unset($args);
	$args['mem_idx']	= $user_info['mem_idx'];
	$args['list_type']	= "row";
	$mem_info			= $C_member->get_user_info($args);

	$tab_click_1 = " white_bg noBo";

	//관심업종
	$data_list = $C_member->set_interesttype_setting($data_args);

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>
</head>
 <body>
<form id="frm" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" id="mode" value="m_0">
<input type="hidden" name="search_type" id="search_type" >
<input type="hidden" name="interest_type" id="interest_type" >
<input type="hidden" name="hid_filename_1" id="hid_filename_1">
<input type="hidden" name="hid_filename_2" id="hid_filename_2">
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
				<div class="mypage_title">마이페이지</div>
				<div class="mypage_wrap">
					<div class="tabs_box_v2">
						<? include_once "_mypage_tab.php" ?>						
					</div>
					<div class="tabs_container">
						<div class="page_contents">
							<div id="info_list" class="tabs_contents_v2">
								<div class="info_list_contents">
									<div class="my_in_content">
										<div class="input_tit"><span>이름</span></div>
										<div class="fs_input_v3 mgb10">
											<input type="text" class="fs_input_text white_bg" value="<?=$user_info['mem_name']?>" readOnly />
										</div>
									</div>
									<div class="my_in_content">
										<div class="input_tit"><span>아이디(이메일)</span></div>
										<div class="fs_input_v3 mgb10">
											<input type="text" class="fs_input_text white_bg" id="txt_mem_id" value="<?=$user_info['mem_id']?>" readOnly />
										</div>
									</div>
									<div class="my_in_content" id="div_pwd_1">
										<div class="input_tit"><span>비밀번호</span></div>
										<div class="fs_input_v3 mgb10">
											<input type="password" class="fs_input_text white_bg" value="roTlqkfshak" disabled />
											<button type="button" id="btn_pwd_chg" class="certi_box floatR" >변경하기</button>
										</div>
									</div>
									<div class="my_in_content" id="div_pwd_2" style="display:none;"><!-- 새 비밀번호 입력 -->
										<div class="input_tit"><span>새 비밀번호</span></div>
										<div class="fs_input_v3 pw_box">
										<input type="password" name="txt_mem_pwd" id="txt_mem_pwd" class="fs_input_text input_pw" placeholder="비밀번호를 입력해주세요" maxlength="20" />
										<span class="floatR" id="span_pwd_1_green" style="display:none;"><i class="fas fa-check green"></i></span>
										<span class="floatR" id="span_pwd_1_red" style="display:none;"><i class="fas fa-times red"></i></span>
										</div>
										<div class="input_alert"><p>영문 + 숫자 + 특수문자 조합의 8자리 이상으로 설정</p></div>
									</div>
									<div class="my_in_content" id="div_pwd_2_1" style="display:none;"><!-- 새 비밀번호 확인 -->
										<div class="input_tit"><span>비밀번호 확인</span></div>
										<div class="fs_input_v3 pw_check_box mgb10">
											<input type="password" name="txt_mem_confirm" id="txt_mem_confirm" class="fs_input_text input_pw_check" placeholder="비밀번호를 입력해주세요" maxlength="20" />
											<button type="button" id="btn_pwd_chg_act" class="certi_box floatR" >변경하기</button>
										</div>
									</div>
									<?if ($user_info['mem_corporation_type'] == "1"){?>
									<div class="my_in_content">
										<div class="input_tit"><span>사업자등록번호</span></div>
										<div class="fs_input_v3 mgb10">
											<input type="text" value="<?=substr($mem_info['dec_mem_business_number'], 0,3)?>-<?=substr($mem_info['dec_mem_business_number'], 3,2)?>-<?=substr($mem_info['dec_mem_business_number'], 5,5)?>" class="input_cc" readOnly />
										</div>
									</div>
									<div class="my_in_content">
										<div class="input_tit"><span>법인번호</span></div>
										<div class="fs_input_v3 mgb10">
											<input type="text" value="<?=substr($mem_info['dec_mem_corporation_number'], 0,6)?>-<?=substr($mem_info['dec_mem_corporation_number'], 6,7)?>" class="input_cc" readOnly />
										</div>
									</div>
									<?}else if ($user_info['mem_corporation_type'] == "2"){?>
									<div class="my_in_content">
										<div class="input_tit"><span>고유번호</span></div>
										<div class="fs_input_v3 mgb10">
											<input type="text" value="<?=substr($mem_info['dec_mem_business_number'], 0,3)?>-<?=substr($mem_info['dec_mem_business_number'], 3,2)?>-<?=substr($mem_info['dec_mem_business_number'], 5,5)?>" class="input_cc" readOnly />
										</div>
									</div>
									<?}?>
									<div class="my_in_content">
										<div class="input_tit"><span><?if ($user_info['mem_division'] == "2"){?>담당자 <?}?>휴대폰 번호</span></div>
										<div class="fs_input_v3 mgb10" id="div_hp_0">
											<input type="text" class="input_pn white_bg" value="<?=$mem_info['dec_mem_hp']?>" disabled />
											<button type="button" class="certi_box floatR" id="btn_hp_chg">변경하기</button>
										</div>
										<div class="fs_input_v3 mgb10" id="div_hp_1" style="display:none;"><!-- 인증번호발송 -->
											<select id="txt_mem_hp_1" name="txt_mem_hp_1" class="ph_box">
												<option selected="selected">010</option>
												<option>011</option>
												<option>016</option>
												<option>017</option>
												<option>018</option>
												<option>019</option>
											</select>
											<em class="ph_bar">-</em>
											<input type="text" name="txt_mem_hp_2" id="txt_mem_hp_2" maxlength="4" class="ph_num input_pn" numberOnly />
											<em class="ph_bar">-</em>
											<input type="text" name="txt_mem_hp_3" id="txt_mem_hp_3" maxlength="4" class="ph_num input_pn" numberOnly />
											<button type="button" class="certi_box floatR" id="btn_hp_authSend">인증번호 발송</button>
											<span class="floatR mgr10" id="span_hp_auth_green" style="display:none;"><i class="fas fa-check green"></i></span>
											<span class="floatR mgr10" id="span_hp_auth_red" style="display:none;"><i class="fas fa-times red"></i></span>
										</div>
										<div class="fs_input_v3 mgb10" id="div_hp_auth" style="display:none;"><!-- 인증하기 -->
											<input type="text" name="txt_mem_hp_authCode" id="txt_mem_hp_authCode" maxlength="6" placeholder="인증번호를 입력해주세요" class="fs_input_text input_name" numberOnly />
											<button type="button" class="certi_box floatR" id="btn_sms_auth">인증하기</button>
											<button type="button" class="certi_box floatR" id="btn_hp_chk_actor" style="display:none;">휴대폰 번호 변경</button>
										</div>
									</div>
									<div class="my_in_content">
										<div class="input_tit input_tit_add"><span>주소</span></div>
										<input type="text" id="sample4_postcode" name="sample4_postcode" placeholder="우편번호" readOnly value="<?=$mem_info['mem_postnum']?>">
										<input type="button" id="sample4_postcode_check" onclick="sample4_execDaumPostcode()" value="우편번호 찾기"><br>
										<input type="text" id="sample4_roadAddress" name="sample4_roadAddress" placeholder="도로명주소" readOnly value="<?=$mem_info['dec_mem_addr1']?>">
										<input type="text" id="sample4_jibunAddress" name="sample4_jibunAddress" placeholder="지번주소" value="<?=$mem_info['dec_mem_addr2']?>">
										<span id="guide" style="color:#999"></span>
									</div>
									<!-- 우편번호검색 -->
									<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
									<script>
										//본 예제에서는 도로명 주소 표기 방식에 대한 법령에 따라, 내려오는 데이터를 조합하여 올바른 주소를 구성하는 방법을 설명합니다.
										function sample4_execDaumPostcode() {
											new daum.Postcode({
												oncomplete: function(data) {
													// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

													// 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
													// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
													var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
													var extraRoadAddr = ''; // 도로명 조합형 주소 변수

													// 법정동명이 있을 경우 추가한다. (법정리는 제외)
													// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
													if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
														extraRoadAddr += data.bname;
													}
													// 건물명이 있고, 공동주택일 경우 추가한다.
													if(data.buildingName !== '' && data.apartment === 'Y'){
													   extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
													}
													// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
													if(extraRoadAddr !== ''){
														extraRoadAddr = ' (' + extraRoadAddr + ')';
													}
													// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
													if(fullRoadAddr !== ''){
														fullRoadAddr += extraRoadAddr;
													}

													// 우편번호와 주소 정보를 해당 필드에 넣는다.
													document.getElementById('sample4_postcode').value = data.zonecode; //5자리 새우편번호 사용
													document.getElementById('sample4_roadAddress').value = fullRoadAddr;
													document.getElementById('sample4_jibunAddress').value = data.jibunAddress;

													// 사용자가 '선택 안함'을 클릭한 경우, 예상 주소라는 표시를 해준다.
													if(data.autoRoadAddress) {
														//예상되는 도로명 주소에 조합형 주소를 추가한다.
														var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
														document.getElementById('guide').innerHTML = '(예상 도로명 주소 : ' + expRoadAddr + ')';

													} else if(data.autoJibunAddress) {
														var expJibunAddr = data.autoJibunAddress;
														document.getElementById('guide').innerHTML = '(예상 지번 주소 : ' + expJibunAddr + ')';

													} else {
														document.getElementById('guide').innerHTML = '';
													}
												}
											}).open();
										}
									</script>
									<div class="my_in_content">
										<?if ($user_info['mem_division'] == "1"){?>
											<div class="input_tit"><span>신분증 사본 등록</span></div>
										<?}else{?>
											<div class="input_tit"><span>법인자료 사본 등록</span></div>
										<?}?>
										<div class="fs_input_v3">
											<input type="file" name="Filedata_1" id="Filedata_1">
											<button type="button" id="btn_upload_1" class="certi_box floatR">파일 업로드</button>
										</div>
										<div class="input_alert">
											<p class="mgb10">최근 자료 등록일 : <span class="purple_sub_v1"><?=substr($mem_info['mem_fileupload1_date'],0,10)?></span></p>
										</div>
									</div>
									<?if ($user_info['mem_division'] == "1"){?>
									<div class="my_in_content"><!-- 추가기획사항 12.03 sg -->
										<div class="input_tit"><span>직종</span></div>
										<div class="fs_input_v3 mgb10">
											<select name="ddl_mem_job_type" id="ddl_mem_job_type" class="investor_box H35 Wfull">
												<option value="">선택 안함</option>
												<option value="경영/인사/관리">경영/인사/관리</option>
												<option value="금융/회계">금융/회계</option>
												<option value="투자/VC">투자/VC</option>
												<option value="IT">IT</option>
												<option value="교육">교육</option>
												<option value="의료/법률">의료/법률</option>
												<option value="보건/복지">보건/복지</option>
												<option value="문화/예술/엔터">문화/예술/엔터</option>
												<option value="일반 사무직">일반 사무직</option>
												<option value="기타">기타</option>
											</select>
										</div>
									</div>
									<?}?>
									<div class="my_in_content">
										<div class="input_tit"><span>투자자 유형</span></div>
										<div class="fs_input_v3 mgb10"> 
											<select class="investor_box H35 W90p" id="mem_invest_type" name="mem_invest_type" <?if ($mem_info['mem_invest_type_chk'] == 1){?>disabled<?}?> >
												<option value="1">일반 투자자</option>
												<option value="2">적격 투자자</option>
												<option value="3">전문 투자자</option>
											</select>
											<a href="javascript:void(0);"><i class="fas fa-question-circle gray_color"></i></a>
										</div>
									</div>
									<div class="my_in_content">
										<div class="input_tit"><span>투자 관련 자료등록</span></div>
										<div class="fs_input_v3">
											<input type="file" name="Filedata_2" id="Filedata_2">
											<button type="button" id="btn_upload_2" class="certi_box floatR">파일 업로드</button>
										</div>
										<div class="input_alert">
											<?if ($mem_info['mem_fileupload2_date']){?>
											<p class="mgb10">최근 자료 등록일 : <span class="purple_sub_v1"><?=substr($mem_info['mem_fileupload2_date'],0,10)?></span></p>
											<?}?>
										</div>
									</div>
									<div class="my_in_content">
										<div class="input_tit"><span>부가정보 수신</span></div>
										<div class="input_tit W75p"><span>SUCCESTOCK 관련 정보를 수신합니다.[선택]</span></div>
										<div class="terms_tit_check mgl130">
											<div class="terms_tit_checkbox mgr20">
												<input class="checkbox" type="checkbox" id="mem_sms_yn" name="mem_sms_yn" value="1" />
												<label for="mem_sms_yn"></label>
												<label for="mem_sms_yn"></label><span class="save_id_text">SMS 수신</span>
											</div>
											<div class="terms_tit_checkbox mgr20">
												<input class="checkbox" type="checkbox" id="mem_mail_yn" name="mem_mail_yn" value="1" />
												<label for="mem_mail_yn"></label>
												<label for="mem_mail_yn"></label><span class="save_id_text">이메일 수신</span>
											</div>
										</div>
									</div>
									<div class="my_in_content_v2">
										<div class="input_tit floatN"><span>관심 업종 선택</span></div>

										<?
											$arr_interest = explode(',',$mem_info['mem_interest_industry']);
											for ($i = 0; $i < count($data_list); $i++)
											{
												$mit_idx = $data_list[$i]['mit_idx'];
												$mit_name = $data_list[$i]['mit_name'];

												$str_checked = "";
												for($j=0; $j < count($arr_interest); $j++)
												{
													if ($mit_idx == $arr_interest[$j])
													{
														$str_checked = "checked";
														break;
													}
												}
										?>

											<div class="my_in_chb terms_tit_checkbox W190 mgr10" id="chk_group_interest">
												<input class="checkbox" type="checkbox" name="text" id="<?=$mit_idx?>" <?=$str_checked?>/>
												<label for="<?=$mit_idx?>"></label><span class="save_id_text"><?=$mit_name?></span>
											</div>
										<?
											}
										?>


									</div>
								</div><!-- info_list_contents end -->
								<div class="page_button_wrap">
									<button type="button" id="btn_update" class="page_agree">수정하기</button>
									<button type="button" onclick="location.href='/';" class="page_close">취소</button>
								</div>
							</div><!-- info_list end -->
						</div><!-- page_contents end -->
					</div><!-- tabs_container end -->
				</div><!-- mypage_wrap end -->
			</div>
		</div>
	</div>
	<!-- container end -->
	<div class="modal_bg">
		<div class="modal_body">
			<div class="modal_contents">
				<div class="modal_text">
					<h2>투자자 유형 안내</h2>
					<p><b>1. 일반 투자자</b><br />
					만 19세 이상의 일반 투자자 입니다.<br />
					<b>[투자한도]</b><br />
					기업당 최대 투자금 : 500만원<br />
					연간 총투자금 : 1,000만원	</p>
					<p><b>2. 적격 투자자</b><br />
					아래 내용 중 1가지 이상을 충족해야 합니다.<br />
					• 근로 소득 금액이 1억원 초과<br />
					• 사업 소득 금액이 1억원 초과<br />
					• 근로 소득 금액과 사업 소득 금액의 합계가 1억원 초과<br />
					• 금융소득(이자소득+배당소득)이 2천만원 초과<br />
					• 금융전문자격시험에 합격하고 금융투자전문 인력으로<br />
					  협회에 3년이상 등록되어 있는 경우<br />
					<b>[투자한도]</b>
					기업당 최대 투자금 : 1,000만<br />
					연간 총투자금 : 2,000만원	</p>
					<p><b>3. 전문 투자자</b><br />
					아래 내용 중 1가지 이상을 충족해야 합니다.</p>
					<div class="gradient"></div>
				</div>
			</div>
			<div class="modal_button_wrap">
				<button type="button" class="modal_close">닫기</button>
			</div>
		</div>
	</div>
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
		$(".fs_input_v3>a").click(function(){
			$(".modal_bg").addClass("active");
		});
		$(".modal_close").click(function(){
			$(".modal_bg").removeClass("active");
		});
		
		$("#ddl_mem_job_type").val("<?=$mem_info['mem_job_type']?>")
		$("#mem_invest_type").val("<?=$mem_info['mem_invest_type']?>");

		$(":checkbox[name=mem_sms_yn]").prop("checked", <?=$mem_info['sms_yn']?>);
		$(":checkbox[name=mem_mail_yn]").prop("checked", <?=$mem_info['mail_yn']?>);

		$("#btn_hp_chg").click(function () {
			$("#div_hp_0").css("display","none");
			$("#div_hp_1").css("display","");

			$("#div_pwd_1").css("display","");
			$("#div_pwd_2").css("display","none");
			$("#div_pwd_2_1").css("display","none");
			$("#span_pwd_1_red").css("display","none");
			$("#txt_mem_pwd").val('');
			$("#txt_mem_confirm").val('');
		});


		$("#btn_hp_authSend").click(function () {
			if ($("#txt_mem_hp_2").val() == "")
			{
				alert("핸드폰번호를 입력해주세여");
				$("#span_hp_auth_red").css("display","");
				$("#txt_mem_hp_2").focus();
				return false;
			}

			if ($("#txt_mem_hp_3").val() == "")
			{
				alert("핸드폰번호를 입력해주세여");
				$("#span_hp_auth_red").css("display","");
				$("#txt_mem_hp_3").focus();
				return false;
			}

			var hp = $("#txt_mem_hp_1").val()+"-"+$("#txt_mem_hp_2").val()+"-"+$("#txt_mem_hp_3").val();

			$("#search_type").val('phone');
			var str_option = $("#frm").serialize();
			$.ajax({
				url: "/std/join/_Proc/join_id_Chk.php",
				data: str_option,
				type: 'POST',
				dataType: 'json',
				traditional: true,
				async: true,
				timeout: 60000,
				error: function (request, status, error) {
				},
				success: function (response) {
					if(response.chkCnt == 0){
						var sendCnt = _authCode.SendSMS($("#txt_mem_id").val(), hp, 17,1);

						if(sendCnt)
						{
							$('#txt_mem_hp_1').attr("readOnly", true);
							$('#txt_mem_hp_2').attr("readOnly", true);
							$('#txt_mem_hp_3').attr("readOnly", true);

							$("#div_hp_auth").css("display","");
						}
					}
					else
					{
						alert("이미 등록되 있는 휴대폰 번호입니다.");
						$("#span_hp_auth_red").css("display","");
						return false;
					}
				}
			});

		});

		$("#btn_sms_auth").click(function () {
			if ($("#txt_mem_hp_authCode").val() == "")
			{
				alert("인증번호를 입력해주세여");
				$("#txt_mem_hp_authCode").focus();
				$("#span_hp_auth_red").css("display","");
				return false;
			}

			var hp = $("#txt_mem_hp_1").val()+"-"+$("#txt_mem_hp_2").val()+"-"+$("#txt_mem_hp_3").val();

			var rtn = _authCode.CheckCode_SMS($("#txt_mem_id").val(), hp, $("#txt_mem_hp_authCode").val(), 1);

			if(rtn)
			{
				$("#txt_mem_pwd").val('');
				$("#txt_mem_confirm").val('');
				$("#btn_sms_auth").css("display","none");
				$("#btn_hp_chk_actor").css("display","");

			}
		});

		$("#btn_hp_chk_actor").click(function () {
			if(confirm("휴대폰 번호를 변경하시겠습니까?"))
			{
				$('#btn_update').trigger('click');
			}
			else
			{
				location.reload();
			}
		});

		$("#btn_pwd_chg").click(function () {
			$("#div_pwd_1").css("display","none");
			$("#div_pwd_2").css("display","");
			$("#div_pwd_2_1").css("display","");

			$("#div_hp_0").css("display","");
			$("#span_hp_auth_red").css("display","none");
			$("#div_hp_1").css("display","none");

			$("#txt_mem_hp_2").val('');
			$("#txt_mem_hp_3").val('');
		});

		$("#btn_pwd_chg_act").click(function () {
			if($("#txt_mem_pwd").val() == "" || $("#txt_mem_confirm").val() == "")
			{
				alert("비밀번호를 입력해주세요.");
				$("#txt_mem_pwd").focus();
				$("#span_pwd_1_red").css("display","");
				return false;
			}

			//Pwd Check
			var rtn_pwd = variousChecked.PasswordChecked($("#txt_mem_pwd").val(), $('#txt_mem_confirm').val());

			if(!rtn_pwd)
			{
				$('#txt_mem_confirm').val('');
				$("#txt_mem_pwd").focus();

				$("#span_pwd_1_green").css("display","none");
				$("#span_pwd_1_red").css("display","");
			}
			else
			{
				$("#span_pwd_1_green").css("display","");
				$("#span_pwd_1_red").css("display","none");
			}

			$('#btn_update').trigger('click');

		});

		$("#btn_upload_1").click(function () {
			var img = $('#Filedata_1');
			var imgFile = img[0].files[0];
			var ext = img.val().split(".").pop().toLowerCase();

			if( $.inArray(ext, ["png","jpg","bmp"]) === -1 ){
				alert('이미지 파일만 업로드 가능합니다.');
				$("#Filedata_1").val('');
				return false;
			}

			var formData = new FormData();
			formData.append("imagepath", "join");
			formData.append("upload_path", "mypage_info_<?=$user_info['mem_idx']?>");
			formData.append("Filedata", $("input[name=Filedata_1]")[0].files[0]);
			formData.append("file_type", "img");
			$("#upload_type").val('img');

			$.ajax({
				type : 'post',
				url : '/se/photo_uploader/popup/file_uploader_ajax.php',
				data : formData,
				dataType: 'json',
				processData : false,
				contentType : false,
				traditional: true,
				async: true,
				timeout: 60000,
				success : function(response) {
					if(response.file_name != "")
					{
						$("#hid_filename_1").val(response.file_name);
						$('#btn_update').trigger('click');
					}
				},
				error : function(error) {
					alert("Upload Error-1 :: img");
				}
			});
		});

		$("#btn_upload_2").click(function () {
			var img = $('#Filedata_2');
			var imgFile = img[0].files[0];
			var ext = img.val().split(".").pop().toLowerCase();

			if( $.inArray(ext, ["zip"]) === -1 ){
				alert('압축파일(zip)만 업로드 가능합니다.');
				$("#Filedata_2").val('');
				return false;
			}

			$("#upload_type").val('zip');
			var formData = new FormData();
			formData.append("imagepath", "join");
			formData.append("upload_path", "mypage_info_<?=$user_info['mem_idx']?>");
			formData.append("file_type", "zip");
			formData.append("Filedata", $("input[name=Filedata_2]")[0].files[0]);

			$.ajax({
				type : 'post',
				url : '/se/photo_uploader/popup/file_uploader_ajax.php',
				data : formData,
				dataType: 'json',
				processData : false,
				contentType : false,
				traditional: true,
				async: true,
				timeout: 60000,
				success : function(response) {
					if(response.file_name != "")
					{
						$("#hid_filename_2").val(response.file_name);
						$('#btn_update').trigger('click');
					}
				},
				error : function(error) {
					alert("Upload Error-2 :: Zip");
				}
			});
		});

		$("#btn_update").click(function () { 

			if($("#sample4_postcode").val() == "" )
			{
				alert("우편번호를 입력해주세여");
				$("#sample4_postcode").focus();
				return false;
			}

			if($("#sample4_roadAddress").val() == "" )
			{
				alert("도로명주소를 입력해주세여");
				$("#sample4_roadAddress").focus();
				return false;
			}

			if($("#sample4_jibunAddress").val() == "" )
			{
				alert("지번주소를 입력해주세여");
				$("#sample4_jibunAddress").focus();
				return false;
			}

			var arr_selected = [];
			$('div#chk_group_interest input[type=checkbox]').each(function() {
			   if ($(this).is(":checked")) {
				   arr_selected.push($(this).attr('id'));
			   }
			});
			
			
			if($("#ddl_mem_job_type").val() == "" )
			{
				alert("직종을 선택해주세요.");
				$("#ddl_mem_job_type").focus();
				return false;
			}

			$("#interest_type").val(arr_selected);

			var str_option = $("#frm").serialize();
			$.ajax({
				url: "/std/mypage/_Proc/mypage_actor.php",
				data: str_option,
				type: 'POST',
				dataType: 'json',
				traditional: true,
				async: true,
				timeout: 60000,
				error: function (request, status, error) {
				},
				success: function (response) {

					if(response.err_code == 0 || response.err_code == 1)
					{
						alert("정상적으로 수정되었습니다.");
						location.reload();
					}
					else
					{
						alert("Update Error!!");
					}
				}
			});
		});
	});


</script>
