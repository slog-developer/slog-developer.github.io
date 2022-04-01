<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php"; 
	include_once FX_PATH."/CLASS/Board.lib.php"; 
	
	$C_member = new Member; 
	$C_Board = new Board; 
	$board_config['board_idx'] = 1;

	$mode			= v3chk($_REQUEST['mode'], "", 5); 
	$category_idx	= v3chk($_REQUEST['hid_category_idx'], "", 5); //카테고리 번호
	 
	unset($args);
	switch ($mode)
	{
		case "R":
			//질문,답변 List
			$args['category_idx']	= $category_idx;	 

			$sub_list = $C_Board->get_faq_category_sublist_Admin($args); 
			
			$return_list_array = array();
			
			for ($i=0; $i<count($sub_list); $i++){
				$return_list_array[$i] = array(
											'bc_idx' => $sub_list[$i]['bc_idx'], 
											'bc_subject' => $sub_list[$i]['bc_subject'], 
											'bc_contents' => str_replace("\r\n", "<br/>",htmlspecialchars_decode($sub_list[$i]['bc_contents'])) ,
											'add_data1' => $sub_list[$i]['add_data1'], 
											'add_data2' => $sub_list[$i]['add_data2'] 										
											);
			}
			
			echo json_encode($return_list_array);
			exit;

			break;	
		default:
			
			$args['board_idx']	= $board_config['board_idx'];

			$category_list = $C_Board->get_faq_category_list_Admin($args); 

			if($category_idx == "") $category_idx = $category_list[0]['category_idx'];
			break;
	} 

?>
<!doctype html>
<html lang="ko">
<head>
<? include_once FX_SITE."/std/inc/php/head.php" ?>
</head>
<body>
<form method="post" id="frm"> 
<input type="hidden" name="hid_category_idx" id="hid_category_idx" value="<?=$category_idx?>">
<input type="hidden" name="mode" id="mode">
<input type="hidden" name="hid_click_idx" id="hid_click_idx">
 <!-- wrap -->
 <div id="wrap">
	<!-- header -->
	<div id="header">
		<? include_once FX_SITE."/std/inc/php/header_sp00.php" ?>
	</div>
	<!-- header end -->
	<!-- container -->
	<div id="container">
		<div class="fs_article">
			<div class="user_page_container">
				<div class="cs_title">고객센터</div>
				<div class="cs_wrap">
					<div class="tabs_box_v3">
						<button type="button" class="white_bg H60 noBo" onclick="location.href='/std/service/faq.php'">FAQ</button>
						<button type="button" onclick="location.href='/std/board/board.php?board_code=1on1'">1:1 문의</button>
					</div>
					<div class="tabs_container">
						<div class="tabs_contents_v2">
							<div class="tabs_box_v4">
								<?
									for ($i=0; $i<count($category_list); $i++)
									{ 
										$str_default = "";
										if ($i == 0)
										{
											$str_default = "id='defaultOpen'";
										}
								?>
										<button type="button" class="faq_tabs type_btn_v2" onclick="openFind(event, 'tab_sublist', '<?=$category_list[$i]['category_idx']?>')" <?=$str_default?>><?=$category_list[$i]['category_name']?></button>
								
								<?	}
								?>

							</div>
							<div id="tab_sublist" class="tabs_contents_v3"></div>
						
						</div><!-- tabs_contents_v2 end -->
					</div><!-- tabs_container end -->
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
 </form>
 </body>
</html>
<!-- accordion script -->
<script type="text/javascript">
	$(document).ready(function(){ 
	});
</script>
<!-- tabs script -->
<script>

	function fnSelect(){
		$("#mode").val('R');
		var str_option = $("#frm").serialize();
		$.ajax({
			url: "faq.php",
			data: str_option,
			type: 'POST',
			dataType: 'json',
			traditional: true,
			timeout: 60000,
			error: function (request, status, error) {
				alert(error);
			},
			success: function (response) {
				fnMakeList(response);
			}
		});
	} 
	
	function fnMakeList(data)
	{
		$("#tab_sublist").html('');
		var idx = $("#hid_category_idx").val(); 
		
		var oHTML = "";  
		
		oHTML = "		<div class='accordion_wrap mgb20'>";
		oHTML = oHTML + "	<nav>";
		oHTML = oHTML + "		<ul class='accordion'>";

		
		var addCnt = data.length;
		for (var i = 0; i < addCnt; i++)
		{
			bc_idx		= data[i].bc_idx;
			bc_subject	= data[i].bc_subject;
			bc_contents = data[i].bc_contents;
			
			oHTML = oHTML + "		<li><a href='javascript:show("+bc_idx+")' id='btn_"+bc_idx+"' ><span></span>"+bc_subject+"</a>";
			oHTML = oHTML + "			<ul id='ul_"+bc_idx+"'><li>"+bc_contents.replace(/(?:\r\n|\r|\n)/g, '<br />')+"</li></ul>";
			oHTML = oHTML + "		</li>";

			if( i == 0)
			{
				$("#hid_click_idx").val(bc_idx);
			}
		}
		
		oHTML = oHTML + "		</ul>";
		oHTML = oHTML + "	</nav>";
		oHTML = oHTML + "</div>"; 
  
		$("#tab_sublist").html(oHTML);
	} 

	function show(idx){
		var pre_idx = $("#hid_click_idx").val();
		if(pre_idx != ""){
			$("#ul_"+pre_idx).slideUp(350);
			$("#btn_"+pre_idx).removeClass("open");
		}

		$("#ul_"+idx).slideDown(350);
		$("#btn_"+idx).addClass("open");
		$("#hid_click_idx").val(idx); 
	}

	function openFind(evt, findBox, category_idx) {
		$("#hid_category_idx").val(category_idx);

		fnSelect();

		// 모든 변수 선언
		var i, tabs_contents_v3, faq_tabs;
		// class = "tabs_contents_v3"인 모든 요소를 ​​가져 와서 숨깁니다.
		tabs_contents_v3 = document.getElementsByClassName("tabs_contents_v3");
		for (i = 0; i < tabs_contents_v3.length; i++) {
			tabs_contents_v3[i].style.display = "none";
		}
		// class = "faq_tabs"인 모든 요소를 ​​가져 와서 "active"클래스를 제거합니다.
		faq_tabs = document.getElementsByClassName("faq_tabs");
		for (i = 0; i < faq_tabs.length; i++) {
			faq_tabs[i].className = faq_tabs[i].className.replace(" active", "");
		}
		// 현재 탭 표시 및 탭을 연 단추에 "active"클래스 추가
		document.getElementById(findBox).style.display = "block";
		evt.currentTarget.className += " active";
	}
	// id = "defaultOpen"인 요소를 가져 와서 클릭합니다.
	document.getElementById("defaultOpen").click();
</script>
