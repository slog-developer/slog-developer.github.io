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
				<div class="mypage_title">포인트</div>
				<div class="mypage_wrap">
					<div class="tabs_box_v3">
						<button type="button" class="idpw_tabs white_bg noBo" onclick="location.href='#'">포인트 적립</button>
						<button type="button" class="idpw_tabs" onclick="location.href='#'">기프티콘 교환하기(예정)</button>
					</div>
					<div class="tabs_container">
						<div class="page_contents">
							<div id="point_list" class="tabs_contents_v2">
								<div class="point_wrap">
									<div class="fs_input_v4 mgb20 W450">
										<span>보유포인트</span>
										<span>|</span>
										<input type="text" name="userName" class="fs_input_text white_bg" value="1,000,000p" disabled />
										<button type="button" class="certi_box floatR">포인트내역</button>
									</div>
									<div class="point_list_table">
										<table class="table_list mgb20">
											<colgroup>
												<col width="10%" />
												<col width="70%"/>
												<col width="20%"/>
											</colgroup>
											<thead>
												<tr>
													<th class="tC">구분</th>
													<th>내용</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="tC">일일</td>
													<td>일일 로그인(30p)</td>
													<td class="tC"><button type="button" class="certi_box_v2">포인트받기</button></td>
												</tr>
												<tr>
													<td class="tC">주간</td>
													<td>연속 7일 로그인(300p)</td>
													<td class="tC"><button type="button" class="certi_box_v2">포인트받기</button></td>
												</tr>
												<tr>
													<td class="tC">기타</td>
													<td>회원가입 완료(500p)</td>
													<td class="tC">지급 완료</td>
												</tr>
												<tr>
													<td class="tC">기타</td>
													<td>관심기업 3개 이상 설정(1,000p)</td>
													<td class="tC">지급 완료</td>
												</tr>
												<tr>
													<td class="tC">기타</td>
													<td>투자 완료 3건 달성(10,000p)</td>
													<td class="tC">미달성</td>
												</tr>
												<tr>
													<td class="tC">기타</td>
													<td>투자 성공 시 투자금의 0.5%</td>
													<td class="tC">달성 시 자동지급</td>
												</tr>												
											</tbody>
										</table>
									</div><!-- point_list_table end -->
								</div><!-- point_wrap end -->
							</div><!-- point_list end -->
						</div><!-- page_contents end -->
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
 </body>
</html>
<script type="text/javascript">
	;(function($){
		$.fn.extend({
			//Plugin Code Start here
			customFileinput: function(options){
				
				//Set Default setting for plugin
				var defaults = {                
					customboxclass : 'customfile'
				};
				
				//Define Default option using extend 
				var options = $.extend(defaults, options);
				
				var fileinput = $(this).find('input[type=file]').css({"position":"absolute"});
				fileinput.addClass(options.fileinputclass); //adding Class in to File input
				fileinput.css({opacity:0}); // Hide File input
				
				//create Element for custom design
				var customfile = $('<div class="'+options.customboxclass+'"><div class="innersec"></div></div>').css({"position":"relative"}); //custome file wrapper
				var cfilefield = $('<span class="cust-field"></span>'); //custome file field
				var cfilebutton = $('<span class="cust-btn"></span>').text(options.buttontext); //custome file button
				
				//Adding file name In Custome design
				fileinput.bind('change', function(){
					var filename = $(this).val().split(/\\/).pop();
					$(this).next().next(cfilefield).text(filename); 
				})
				
				//Implement HTML object For Custom Design
				fileinput.wrap(customfile);
				fileinput.after(cfilebutton);
				fileinput.next(cfilebutton).after(cfilefield);
				var movebox = customfile.attr('class');
				
				//Follow Mouse in custom file block area region
				
				$("."+movebox).bind('mouseleave', function(e){
				}).width(options.customboxwidth);
				
			}
		});	
	})(jQuery);
</script>

