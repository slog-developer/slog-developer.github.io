<?
	include_once getenv("DOCUMENT_ROOT")."/_common.php";
	include_once FX_PATH."/CLASS/Member.lib.php";

	$C_member = new Member;

	if(!$C_member->check_login($user_info)) {	//로그인 체크
		execJava("", "", "location.href='/std/account/login.php?returnUrl=".urlencode($_SERVER['REQUEST_URI'])."';");
	}

	$tab_click_3 = " white_bg noBo";
 
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
		<? include_once FX_SITE."/std/inc/php/header_sp00.php" ?>
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
							<div id="point_list" class="tabs_contents_v2">
								<div class="point_wrap">
									<div class="fs_input_v4 mgb20 W450">
										<span>보유포인트</span>
										<span>|</span>
										<input type="text" name="userName" class="fs_input_text white_bg" value="1,000,000p" disabled />
										<button type="button" class="certi_box floatR">포인트 적립하기</button>
									</div>
									<div class="point_list_table">
										<table class="table_list mgb20">
											<colgroup>
												<col width="15%" />
												<col width="55%"/>
												<col width="15%"/>
												<col width="15%"/>
											</colgroup>
											<thead>
												<tr>
													<th class="tC">구분</th>
													<th>내용</th>
													<th>포인트</th>
													<th>날짜</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="tC">적립</td>
													<td>일일 로그인(30p)</td>
													<td>30</td>
													<td>2018-08-30</td>
												</tr>
												<tr>
													<td class="tC">사용</td>
													<td>기프티콘 구매</td>
													<td>10,000</td>
													<td>2018-08-30</td>
												</tr>
												<tr>
													<td class="tC">사용</td>
													<td>연속 7일 로그인</td>
													<td>300</td>
													<td>2018-08-30</td>
												</tr>
												<tr>
													<td class="tC">적립</td>
													<td>투자 완료 3건 달성(10,000p)</td>
													<td>10,000</td>
													<td>2018-08-30</td>
												</tr>
												<tr>
													<td class="tC">적립</td>
													<td>일일 로그인(30p)</td>
													<td>30</td>
													<td>2018-08-29</td>
												</tr>
												<tr>
													<td class="tC">사용</td>
													<td>기프티콘 구매</td>
													<td>500</td>
													<td>2018-08-28</td>
												</tr>
												<tr>
													<td class="tC">사용</td>
													<td>기프티콘 구매</td>
													<td>1,000</td>
													<td>2018-08-25</td>
												</tr>
												<tr>
													<td class="tC">적립</td>
													<td>투자 성공</td>
													<td>5,000</td>
													<td>2018-08-25</td>
												</tr>
												<tr>
													<td class="tC">적립</td>
													<td>관심기업3개 이상 설정(1,000p)</td>
													<td>1,000</td>
													<td>2018-08-24</td>
												</tr>
												<tr>
													<td class="tC">사용</td>
													<td>회원가입 완료(500p)</td>
													<td>500(1,000p)</td>
													<td>2018-08-24</td>
												</tr>
											</tbody>
										</table>
										<div class="pagination">
											<ul class="page_num">
												<li>
													<a href='javascript:void(0);' class="page_btn pre_v2"></a>
													<a href='javascript:void(0);' class="page_btn pre_v1"></a>
												</li>											
												<li><a href="#" class='pgnt_hover'>1</a></li>
												<li><a href="#" class='pgnt_hover'>2</a></li>
												<li><a href="#" class='pgnt_hover'>3</a></li>
												<li><a href="#" class='pgnt_hover'>4</a></li>
												<li><a href="#" class='pgnt_hover'>5</a></li>
												<li><a href="#" class='pgnt_hover'>6</a></li>
												<li><a href="#" class='pgnt_hover'>7</a></li>
												<li><a href="#" class='pgnt_hover'>8</a></li>
												<li><a href="#" class='pgnt_hover'>9</a></li>
												<li><a href="#" class='pgnt_hover'>10</a></li>
												<li>
													<a href='javascript:void(0);' class="page_btn next_v1"></a>
													<a href='javascript:void(0);' class="page_btn next_v2"></a>
												</li>
											</ul>
										</div>
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