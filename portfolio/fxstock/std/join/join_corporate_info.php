<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";

	if ($_SESSION["REG_MEM_ID"] == "")
	{
		execJava("Invalid Access.", "", "location.replace('/std/join/select_type.php');");
	} 
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>
</head>
 <body>
<form id="frm" method="post">
<input type="hidden" name="search_type" id="search_type" >
<input type="hidden" name="mode" id="mode" value="CI">
<input type="hidden" id="chk_auth" value="0">
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
				<div class="section_title">회원가입<p>투자자 회원(법인) | <span>회사법인</span></p></div>					
					<div class="idpw_wrap">
						<div class="tabs_container">
							<div class="reset_contents">
								<div class="add_tit_input_section">
									<div class="input_tit"><span>아이디(이메일)</span></div>
									<div class="fs_input_v3 mgb10">
										<input type="email" name="txt_mem_id" id="txt_mem_id" class="fs_input_text white_bg" value="<?=$_SESSION["REG_MEM_ID"]?>" readOnly />
									</div>
								</div>
								<div class="add_tit_input_section">
									<div class="input_tit"><span>비밀번호</span></div>
									<div class="fs_input_v3 pw_box">
										<input type="password" name="txt_mem_pwd" id="txt_mem_pwd" class="fs_input_text input_pw" placeholder="비밀번호를 입력해주세요" maxlength="20" />
										<span class="floatR" id="span_pwd_1_green" style="display:none;"><i class="fas fa-check green"></i></span>
										<span class="floatR" id="span_pwd_1_red" style="display:none;"><i class="fas fa-times red"></i></span>
									</div>									
									<div class="input_alert"><p>영문 + 숫자 + 특수문자 조합의 8자리 이상으로 설정</p></div>
								</div>
								<div class="add_tit_input_section">
									<div class="input_tit"><span>비밀번호 확인</span></div>
									<div class="fs_input_v3 pw_check_box mgb10">
										<input type="password" name="txt_mem_confirm" id="txt_mem_confirm" class="fs_input_text input_pw_check" placeholder="비밀번호를 입력해주세요" maxlength="20" />
										<span class="floatR" id="span_pwd_2_green" style="display:none;"><i class="fas fa-check green"></i></span>
										<span class="floatR" id="span_pwd_2_red" style="display:none;"><i class="fas fa-times red"></i></span>
									</div>
								</div>
								<div class="add_tit_input_section">
									<div class="input_tit"><span>법인명</span></div>
									<div class="fs_input_v3 mgb10">
										<input type="text" name="txt_mem_corporation_name" class="fs_input_text input_name" placeholder="법인명 입력" maxlength="40" />
									</div>
								</div> 

								<?if ($_SESSION["_duplicate_businessNumber_Chk"] == "1" and $_SESSION["_duplicate_corporationNumber_Chk"] == "1" ){?>
								<div class="add_tit_input_section">
									<div class="input_tit"><span>사업자등록번호</span></div>
									<div class="fs_input_v3 mgb10">
										<input type="text" value="<?=substr($_SESSION["REG_MEM_BUSINESS_NUMBER"], 0,3)?>-<?=substr($_SESSION["REG_MEM_BUSINESS_NUMBER"], 3,2)?>-<?=substr($_SESSION["REG_MEM_BUSINESS_NUMBER"], 5,5)?>" class="input_cc" readOnly />
									</div>
								</div>
								<div class="add_tit_input_section">
									<div class="input_tit"><span>법인번호</span></div>
									<div class="fs_input_v3 mgb10">
										<input type="text" value="<?=substr($_SESSION["REG_MEM_CORPORATION_NUMBER"], 0,6)?>-<?=substr($_SESSION["REG_MEM_CORPORATION_NUMBER"], 6,7)?>" class="input_cc" readOnly />
									</div>
								</div>
								<?}?>
								
								<?if ($_SESSION["_duplicate_johapNumber_Chk"] == "1"){?>
								<div class="add_tit_input_section">
									<div class="input_tit"><span>고유번호</span></div>
									<div class="fs_input_v3 mgb10">
										<input type="text" value="<?=substr($_SESSION["REG_MEM_JOHAP_NUMBER"], 0,3)?>-<?=substr($_SESSION["REG_MEM_JOHAP_NUMBER"], 3,2)?>-<?=substr($_SESSION["REG_MEM_JOHAP_NUMBER"], 5,5)?>" class="input_cc" readOnly />
									</div>
								</div>
								<?}?>

								<div class="add_tit_input_section">
									<div class="input_tit"><span>대표자 성명</span></div>
									<div class="fs_input_v3 mgb10">
										<input type="text" name="txt_mem_name" id="txt_mem_name" class="fs_input_text input_name" maxlength="10" placeholder="대표자 성명 입력" />
									</div>
								</div>
								<div class="add_tit_input_section">
									<div class="input_tit"><span>휴대폰 번호</span></div>
									<div class="fs_input_v3 mgb10">
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
								</div>
								<div class="add_tit_input_section" id="div_hp_auth" style="display:none;">
									<div class="input_tit"><span></span></div>
									<div class="fs_input_v3 mgb10">
										<input type="text" name="txt_mem_hp_authCode" id="txt_mem_hp_authCode" maxlength="6" placeholder="인증번호를 입력해주세요" class="fs_input_text input_name" numberOnly />
										<button type="button" class="certi_box floatR" id="btn_sms_auth">인증하기</button>
									</div>
								</div>
								<div class="add_tit_input_section">
									<div class="input_tit input_tit_add"><span>주소</span></div>
									<input type="text" id="sample4_postcode" name="sample4_postcode" placeholder="우편번호" readOnly>
									<input type="button" id="sample4_postcode_check" onclick="sample4_execDaumPostcode()" value="우편번호 찾기"><br>
									<input type="text" id="sample4_roadAddress" name="sample4_roadAddress" placeholder="도로명주소" readOnly>
									<input type="text" id="sample4_jibunAddress" name="sample4_jibunAddress" placeholder="지번주소">
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
								<div class="add_tit_input_section">
									<div class="input_tit"><span>투자자 유형</span></div>
									<div class="fs_input_v3 mgb10">
										<select id="txt_mem_invest_type" class="ph_box W90p H35" name="txt_mem_invest_type">
											<option value="1" selected="selected">일반 투자자</option>
											<option value="2">적격 투자자</option>
											<option value="3">전문 투자자</option>
										</select>
										<span class="floatR"><a href="#" class="fas fa-question-circle gray_color"></a></span>
									</div>
								</div>
								<div class="add_tit_input_section mgb30">
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
								</div><!-- add_tit_input_section end -->
								<div class="fs_form">
									<div class="fs_btn_box"> 
										<button type="button" class="fs_full_btn" id="btn_next">다음 <i class="fas fa-angle-double-right"></i></button>
									</div>
								</div>
							</div>
						</div><!-- tabs_container end -->
					</div><!-- idpw_wrap end -->
			</div>
		</div> 
		<!-- 휴대폰번호에 숫자만 입력되도록 함 -->
		<script>
		$("input:text[numberOnly]").on("keyup", function() {
			$(this).val($(this).val().replace(/[^0-9]/g,""));
		});
		</script>
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
				<button class="modal_close">닫기</button>
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
				$("#chk_auth").val('1');
				$("#div_hp_auth").css("display","none");
				$("#span_hp_auth_green").css("display",""); 
				$("#span_hp_auth_red").css("display","none");
				$("#btn_hp_authSend").css("display","none");
			} 
		});	

		$('#txt_mem_confirm').focusout(function(){
			//Pwd Check
			var rtn_pwd = variousChecked.PasswordChecked($("#txt_mem_pwd").val(), $('#txt_mem_confirm').val()); 

			if(!rtn_pwd)
			{
				$('#txt_mem_confirm').val('');
				$("#txt_mem_pwd").focus();
				
				$("#span_pwd_1_red").css("display",""); 
				$("#span_pwd_2_red").css("display",""); 
			}
			else
			{
				$("#span_pwd_1_green").css("display","");
				$("#span_pwd_1_red").css("display","none");
				$("#span_pwd_2_green").css("display","");
				$("#span_pwd_2_red").css("display","none");
			}
		});

		$("#btn_next").click(function (){
			if($("#txt_mem_pwd").val() == "" || $("#txt_mem_confirm").val() == "")
			{
				alert("비밀번호를 입력해주세요."); 
				$("#txt_mem_pwd").focus();
				$("#span_pwd_1_red").css("display",""); 
				$("#span_pwd_2_red").css("display",""); 
				return false;
			}

			if($("#txt_mem_name").val() == "" || $("#txt_mem_name").val().length < 2 )
			{
				alert("이름을 입력해주세요."); 
				$("#txt_mem_name").focus();
				return false;
			}
			
			if($("#chk_auth").val() == "0" )
			{
				alert("휴대폰 인증을 해주세요."); 
				$("#span_hp_auth_red").css("display","");
				$("#txt_mem_hp_2").focus();
				return false;
			} 
			
			if($("#chk_auth_jumin").val() == "0" )
			{
				alert("주민등록번호 중복확인을 해주세요."); 
				$("#span_jumin_check_red").css("display",""); 
				$("#txt_mem_jumin_1").focus();
				return false;
			}  
			
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
			
			var str_option = $("#frm").serialize();
			$.ajax({
				url: "/std/join/_Proc/join_actor.php",
				data: str_option,
				type: 'POST',
				dataType: 'json',
				traditional: true,
				async: true,
				timeout: 60000,
				error: function (request, status, error) {
				},
				success: function (response) {
					
					if(response.err_code == 0)
					{
						location.href = "join_corporate_end.php";
					}
					else
					{
						alert("회원가입 실패-"+response.err_code);
						return false;
					}
				}
			});
			
		});


	});
</script>