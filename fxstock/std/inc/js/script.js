

$(document).ready(function(){
	/* .fs_lnb 마우스 오버 시 */
	$(".fs_gnb>ul>li").mouseover(function(){
		$(this).children(".arrow_box").stop().fadeIn("fast");
	});
	$(".fs_gnb>ul>li").mouseleave(function(){
		$(this).children(".arrow_box").stop().fadeOut("fast");
	});

	/* fs_lnb_user_info 마우스 오버 시 */
	$(".login_lnb>li").mouseover(function(){
		$(this).children(".arrow_box").stop().fadeIn("fast");
	});
	$(".login_lnb>li").mouseleave(function(){
		$(this).children(".arrow_box").stop().fadeOut("fast");
	});

	/*  */
	$(".recruit_tit>p>button").mouseover(function(){
		$(this).children(".arrow_box_v2").stop().fadeIn("fast");
	});
	$(".recruit_tit>p>button").mouseleave(function(){
		$(this).children(".arrow_box_v2").stop().fadeOut("fast");
	});

	
	/* cardbox의 버튼 클릭 시 즐겨찾기아이콘 색상 변경 */
	var isCss = false;
    $("#star_icon").click(function(){
		
		if(!isCss)
		{
			isCss = true;
		   $(".tac_mark button i").eq(0).css("color","#f000ff");
		}
		else
		{
			isCss = false;
			$(".tac_mark button i").eq(0).css("color","#a0a0a0");
		}
    });
}); 
