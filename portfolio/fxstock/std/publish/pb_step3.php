<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";
	include_once FX_PATH."/CLASS/Funding.lib.php";

	$C_member	= new Member;
	$C_funding	= new Funding;
	
	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}

	unset($args); 
	$funding_idx = $_GET['funding_idx'];
	
	if($funding_idx == "")
	{
		$args['mem_idx']	= $user_info['mem_idx'];
		$args['list_type']	= "row";
		$mem_info			= $C_member->get_user_info($args); 
		
		if($mem_info['mem_division'] != "3") {	//권한 체크
			execJava("", "", "location.href='/';");
		}
	}
	else
	{
		$args['funding_idx']	= $funding_idx;
	}

	$isUpdate = false;
	$args['list_type']	= "row";
	$funding_view = $C_funding->get_funding_issue($args);
 
	if(!$funding_view)
	{ 
		execJava("등록되지 않는 펀딩입니다.", "", "location.href='/';");
	} 

	$_SESSION['isEditor']	= 1; 
?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>

<style>
#dialog-background {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,.3);
    z-index: 10;
}
#my-dialog {
    display: none;
    position: fixed;
    left: calc( 30% - 160px ); top: calc( 30% - 70px ); 
    background: #fff;
    z-index: 11;
    padding: 10px;
}
</style>

</head>
 <body>
<form id="frm" method="post"> 
<input type="hidden" name="flag" id="flag">
<input type="hidden" name="mode" id="mode"> 
<input type="hidden" name="funding_issue_status" value="<?=$funding_view['funding_issue_status']?>">
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
			<div class="row_wrap">
				<div class="funding_txt_box">
					<p>펀딩 발행을 신청하시면 SUCCESTOCK 담당자가 확인 후 실사 관련 안내전화를 드립니다. <br />
					모집내용, 회사정보의 모든 항목을 입력하신 후 “신청하기” 버튼을 클릭하시면 신청이 완료가 됩니다. <br />
					(회사정보 > CEO 인사말 / 주요 사업 및 수익 구조 / 사업계획은 1가지 이상만 필수로 등록 하셔도 됩니다.)</p>
				</div>
			</div>
			<div class="pb_bg_section"> 
				<? include_once "inc_top.php" ?> 
			</div><!-- pb_bg_section end -->
			<div class="sub_tab" id="sub_tab_form">
				<ul class="sub_tab_list_v2">
					<li id="link_step2" style='cursor: pointer;'>모집내용</li>
					<li id="link_step3" style='cursor: pointer;' class="purple_bottom">회사정보</li>
				</ul>
			</div>
			<div class="funding_container">
				<div class="nav_recruit" id="nav_recruit_form">
					<ul>
						<li><a href=".recruit_container_1">기본정보</a></li>
						<li><a href=".recruit_container_2">CEO 인사말</a></li>
						<li><a href=".recruit_container_3">회사 이야기</a></li>
						<li><a href=".recruit_container_4">구성원 인터뷰</a></li>
						<li><a href=".recruit_container_5">회사자료</a></li>
					</ul>
				</div>
				<div class="chat_wrap" id="chat_wrap_form">
					<div class="recruit_content">
						<? include_once "inc_right.php" ?> 			
					</div><!-- recruit_content end -->						
					<div class="fs_btn_box mgt10"> 
						<?				 
							$issue_update_isOk = 0;
							if($funding_view['funding_issue_status'] > "0" && $funding_view['funding_issue_status'] < "4")
							{
								$isUpdate = true;
								$issue_update_isOk = $funding_view['issue_update_isOk'];
							}   
				
							if($user_info['mem_idx'] == $funding_view['mem_idx'])
							{
								if($funding_view['funding_issue_status'] == "0")
								{
									$issue_update_isOk = "1";
								}
								else
								{
									$issue_update_isOk = $funding_view['issue_update_isOk'];
								}
							}
							else
							{
								$issue_update_isOk = 0;
							}

							if($issue_update_isOk == "1")
							{
						?>
								<button type="button" class="certi_box_v5" id="btn_update">
								<?
									if($funding_view['funding_issue_status'] == "0")
									{
										echo "신청하기";
									}
									else
									{
										echo "수정하기";
									}
								?>
								</button>
								<button type="button" id='btn_cancel' class="chat_list_btn_v2">취소</button>
						<?
							}

						?>

					</div>
				</div>
				<div class="recruit_wrap">
					<div class="recruit_container_1">
						<div class="recruit_tit mgb20">
							<h1>회사 기본정보</h1>
							<h2 class="deepblue">(마이페이지에서 수정 가능합니다.)</h2>
						</div>
						<div class="recruit_content">							
							<ul class="recruit_list">
								<li><p>업종</p><span><?=$funding_view['mit_name']?></span></li>
								<li><p>대표자명</p><span><?=$funding_view['mem_name']?></span></li>
								<li><p>설립일자</p><span><?=substr($funding_view['mem_establishment_dt'], 0,10)?></span></li>
								<li><p>임직원 수</p><span><?=$funding_view['mem_issuer_peopleCnt']?>명</span></li>
								<li class="Wfull"><p>주소</p><span><?=$funding_view['dec_mem_addr1']?> <?=$funding_view['dec_mem_addr2']?></span></li>
								<li class="Wfull"><p>웹사이트</p><span class="lightblue text_deco"><a href="<?=$funding_view['mem_issuer_website']?>" target="_blank"><?=$funding_view['mem_issuer_website']?></a></span></li>
								<li class="Wfull"><p>이메일</p><span><?=$funding_view['mem_id']?></span></li>
								<li class="Wfull"><p>회사 대표번호</p><span><?=$funding_view['mem_issuer_tel']?></span></li>
							</ul>							
						</div><!-- recruit_content end -->			
					</div>
					<div class="recruit_container_2">
						<div class="recruit_tit">
							<h1>CEO 인사말</h1>
							<div class="recruit_txt floatR">
								<div class="terms_tit_check">
									<input class="checkbox" type="checkbox" id="chk_ceo_agree" name="chk_ceo_agree" value="1"  <?if ($funding_view['step3_contents_1_isChk'] == "1"){?>checked<?}?> />
									<label for="chk_ceo_agree"></label>
								</div>
							</div>
							<p class="disIB floatR pdr5">
								<button class="question floatR" type="button">
									<i class="fas fa-question-circle gray_color"></i>
									<ul class="arrow_box_v2">
										<li><h5>[소득공제율]</h5></li>
										<li>투자금액 구간 별로 투자금액이 소득공제에 <br /> 적용되는 비율이 다릅니다.</li>
										<li>· 3,000만원 이하 100%</li>
										<li>· 3,000만원 초과 5,000만원 이하 70%</li>
										<li>· 5,000만원 초과 30% </li>
									</ul>
								</button>
							</p>
							<h4 class="floatR ln20">CEO 인사말 동영상 제작 신청하기</h4>
						</div>
						<div class="recruit_content">
							<p><div id="summernote_1"><?=$funding_view['step3_contents_1']?></div></p>
						</div>
					</div>
					<div class="recruit_container_3">
						<div class="recruit_tit">
							<h1>회사 이야기</h1>
						</div>
						<div class="recruit_content mgb10">
							<p><div id="summernote_2"><?=$funding_view['step3_contents_2']?></div></p>
						</div>
					</div>
					<div class="recruit_container_4">
						<div class="recruit_tit">
							<h1>구성원 인터뷰</h1>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>회사에 참여하게 된 동기는 무엇입니까?</h3>
							<p><div id="summernote_3"><?=$funding_view['step3_contents_3']?></div></p>
						</div>
						<div class="add_tit_input_section">
							<div class="input_tit_v4">
								<span class="purple_sub_v1">답변자정보</span>
							</div>
							<div class="fs_input_v9">
								<input type="text" id="txt_Answer_3" name="txt_Answer_3" class="fs_input_text input_em" placeholder="부서명/이름/직급을 입력해주세요." maxlength="100" value="<?=$funding_view['step3_who_3']?>" />
							</div>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>우리 회사를 자랑해주세요.</h3>
							<p><div id="summernote_4"><?=$funding_view['step3_contents_4']?></div></p>
						</div>
						<div class="add_tit_input_section">
							<div class="input_tit_v4">
								<span class="purple_sub_v1">답변자정보</span>
							</div>
							<div class="fs_input_v9">
								<input type="text" id="txt_Answer_4" name="txt_Answer_4" class="fs_input_text input_em" placeholder="부서명/이름/직급을 입력해주세요." maxlength="100"  value="<?=$funding_view['step3_who_4']?>" />
							</div>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>회사를 이끌어가는 원동력은 무엇이라고 생각하세요?</h3>
							<p><div id="summernote_5"><?=$funding_view['step3_contents_5']?></div></p>
						</div>
						<div class="add_tit_input_section">
							<div class="input_tit_v4">
								<span class="purple_sub_v1">답변자정보</span>
							</div>
							<div class="fs_input_v9">
								<input type="text" id="txt_Answer_5" name="txt_Answer_5" class="fs_input_text input_em" placeholder="부서명/이름/직급을 입력해주세요." maxlength="100" value="<?=$funding_view['step3_who_5']?>" />
							</div>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>대표이사님의 장점은 무엇인가요?</h3>
							<p><div id="summernote_6"><?=$funding_view['step3_contents_6']?></div></p>
						</div>
						<div class="add_tit_input_section">
							<div class="input_tit_v4">
								<span class="purple_sub_v1">답변자정보</span>
							</div>
							<div class="fs_input_v9">
								<input type="text" id="txt_Answer_6" name="txt_Answer_6" class="fs_input_text input_em" placeholder="부서명/이름/직급을 입력해주세요." maxlength="100" value="<?=$funding_view['step3_who_6']?>" />
							</div>
						</div>
						<div class="interview_section">
							<h3><span class="interview_icon"></span>3년, 5년 후 우리 회사는 어떤 모습일까요?</h3>
							<p><div id="summernote_7"><?=$funding_view['step3_contents_7']?></div></p>
						</div>
						<div class="add_tit_input_section">
							<div class="input_tit_v4">
								<span class="purple_sub_v1">답변자정보</span>
							</div>
							<div class="fs_input_v9">
								<input type="text" id="txt_Answer_7" name="txt_Answer_7" class="fs_input_text input_em" placeholder="부서명/이름/직급을 입력해주세요." maxlength="100" value="<?=$funding_view['step3_who_7']?>" />
							</div>
						</div>
					</div>
					<div class="recruit_container_5">
						<div class="recruit_tit mgb20">
							<h1>회사 자료</h1>
							<h2 class="deepblue">(마이페이지에서 수정 가능합니다.)</h2>
						</div>
						
						<?
							$str_array = array("", "사업자등록증", "법인 통장", "법인 등기부 동본", "정관 전문", "재무재표", "투자 설명서");  
						?>
						<?for ($ii = 1; $ii <= 6; $ii++){?>
							
							<div class="recruit_content mgt20">
								<div class="fs_input_v7">
									<div class="data_txt"><h4><?=$str_array[$ii]?></h4> <span>등록일 : <?=substr($funding_view['mem_fileupload'.$ii.'_date'],0,10)?></span></div>
									<div class="data_icon">
										<a href="javascript:viewImg('<?=$funding_view['mem_fileupload'.$ii.'']?>')" download class="far fa-eye open-dialog"></a>
										<a href="<?=$funding_view['mem_fileupload'.$ii.'']?>" download class="fas fa-download mgl20"></a>
									</div>
								</div>
							</div> 
						<?}?> 

					</div>
				</div><!-- recruit_wrap end -->

				<a class="top_btn"><i class="fas fa-angle-up"></i></a>
			</div><!--  funding_container end -->
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
<?for($i=1;$i<=7;$i++){?>
	<textarea name="bc_contents_<?=$i?>" style="display:none;"></textarea>
<?}?>

<div id="my-dialog">
	<img id="img_view">
</div>
<div id="dialog-background"></div>
 </form>
 </body>
</html>

<script type="text/javascript" src="pb_step_common.js"></script>
<script type="text/javascript" src="/std/inc/js/_common.js"></script>
<script type="text/javascript">

function viewImg(img_path)
{
	$("#img_view").attr("src", img_path);
}

$(function(){
	$(".open-dialog,#dialog-background").click(function () {
		$("#my-dialog,#dialog-background").toggle();
	});
});

$(document).ready(function() {
	
	<?
	if ($issue_update_isOk == "0")
	{
	?>
		$('input').prop('readonly', true);
		$('textarea').prop('readonly', true);

		<?for($i=1;$i<=9;$i++){?>
			$('#summernote_<?=$i?>').summernote('disable');
		<?}?>
	<?
	}
	?>

	$('input[type=file]').attr('disabled', true);
	$("#image-label").css("display","none"); 

	<?for($i=1;$i<=7;$i++){?>
		$('#summernote_<?=$i?>').summernote({
			width:538,height: 250
		});
	<?}?> 

	$("#ddl_funding_type").val("<?=$funding_view['funding_type']?>");
	$("#ddl_funding_type_detail_stock").val("<?=$funding_view['funding_type_detail']?>");
	$("#ddl_funding_type_detail_bond").val("<?=$funding_view['funding_type_detail']?>");
	$("#ddl_funding_invest_type").val("<?=$funding_view['funding_type_level']?>");

	$("#ddl_funding_type").attr("disabled",true);
	$("#ddl_funding_type_detail_bond").attr("disabled",true);
	$("#ddl_funding_type_detail_stock").attr("disabled",true);
	$("#ddl_funding_invest_type").attr("disabled",true);

	<?
		if ($funding_view['funding_type'] == "1")
		{
	?> 
			$("#ddl_funding_type_detail_stock").css("display",""); 
			$("#ddl_funding_type_detail_bond").css("display","none"); 
	<?
		}
		else
		{
	?>
			$("#ddl_funding_type_detail_stock").css("display","none"); 
			$("#ddl_funding_type_detail_bond").css("display",""); 
	<?
		}
	?>

	$("#link_step2").on('click', function(e){ 
		location.href= "pb_step2.php?funding_idx=<?=$funding_idx?>";
	});
	
	$("#link_step3").on('click', function(e){ 
		location.href= "pb_step3.php?funding_idx=<?=$funding_idx?>";
	});
	
	

	$("#btn_cancel").on('click', function(e){ 
		
		$("#flag").val('del'); 
		
		if(confirm('펀딩 발행을 취소하시겠습니까?'))
		{
			var str_option = $("#frm").serialize();
			$.ajax({
				url: "/std/publish/_Proc/pb_step_actor.php",
				data: str_option,
				type: 'POST',
				dataType: 'json',
				traditional: true,
				timeout: 60000,
				error: function (request, status, error) {
				},
				success: function (response) { 
					if(response.err_code == 0 || response.err_code == 1)
					{
						alert('펀딩 발행이 취소 되었습니다.');
						location.href = '/';
					}
					else
					{
						alert('Error -'+response.err_code);
						return false;
					}
				}
			}); 

		}
	});
	
	$("#btn_update").on('click', function(e){   
		
			var isChk = false;
		<?
			$arr_msg = array('', 'CEO 인사말을 입력해주세요', '회사 이야기를 입력해주세요', '회사에 참여하게 된 동기를 입력해주세요', '우리 회사를 자랑를 입력해주세요', '회사를 이끌어가는 원동력를 입력해주세요', '대표이사님의 장점을 입력해주세요', '3년, 5년 후 우리 회사 모습을 입력해주세요');
			for($i=1;$i<=7;$i++){
		?>
				$('textarea[name="bc_contents_<?=$i?>"]').val($('#summernote_<?=$i?>').summernote('code'));

				if($('textarea[name="bc_contents_<?=$i?>"]').val() != "<p><br></p>")
				{
					isChk = true;
				}  
		<?
			}
		?>

		if(!isChk)
		{
			alert("회사정보 > CEO 인사말 / 주요 사업 및 수익 구조 / 사업계획은 1가지 이상은 입력하셔야 합니다.");
			return false;
		}
		
		$("#flag").val('step3');

		<?
			if($isUpdate && $issue_update_isOk == "1")
			{
		?>
			$("#mode").val('U');
		<?
			}
			else
			{
		?>
			$("#mode").val('I');
		<?
			}
		?>
		
		var str_option = $("#frm").serialize();
		$.ajax({
			url: "/std/publish/_Proc/pb_step_actor.php?mode=step3",
			data: str_option,
			type: 'POST',
			dataType: 'json',
			traditional: true,
			timeout: 60000,
			error: function (request, status, error) {
			},
			success: function (response) { 
				if(response.err_code == 1)
				{
					<?if ($funding_view['funding_issue_status'] == "0")
					{
					?>
						//alert('신청이 완료되었습니다.');
						location.href = 'pb_template.php';
					<?
					}
					else
					{
					?>
						alert('수정 되었습니다.'); 
						location.reload();
					<?
					}
					?>
				}
				else
				{
					alert('Error -'+response.err_code);
					return false;
				}
			}
		});
		
	});
	
});
 
</script>