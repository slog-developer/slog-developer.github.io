	//퍼블 공통 스크립트
	window.onscroll = function() {myFunction()};

	var sub_tab = document.getElementById("sub_tab_form");
	var sub_tab_v2 = document.getElementById("nav_recruit_form");
	var sub_tab_v3 = document.getElementById("chat_wrap_form");
	var sticky = sub_tab.offsetTop;

	function myFunction() {
	  if (window.pageYOffset > sticky) {
		sub_tab.classList.add("sticky");
		sub_tab_v2.classList.add("sticky_v2");
		sub_tab_v3.classList.add("sticky_v3");
	  } else {
		sub_tab.classList.remove("sticky");
		sub_tab_v2.classList.remove("sticky_v2");
		sub_tab_v3.classList.remove("sticky_v3");
	  }
	}

	// one page rolling script
	var speed = 700;	// 스크롤 스피드 수치로 사용할 변수 
	
	//로직
	function scrolling(obj){
		if (!obj){	// 예외처리, 현재는 기능적으로 필요한 부분은 아님, 관례적 사용
			$('html, body').animate({scrollTop:0},speed);
		}else{
			var posTop = $(obj).offset().top -100;	// posTop = 매개변수로 들어온 컨텐츠 obj 의 offset().top - 네비게이션 높이
			$('html, body').animate({scrollTop:posTop}, speed )	// body의 스크롤이동 : posTop
		}
	};

	$("ul li a").click(function(){	// 네비게이션 클릭시
		var direction = $(this).attr("href");	// direction = 클릭한 요소의 href 속성
		scrolling( direction );	// direction 을 인자로 함수 실행
		return false;	// 본래 이벤트 방지 
	});

	// top button script
	var btn = $('.top_btn');

	$(window).scroll(function() {
	  if ($(window).scrollTop() > 300) {
		btn.addClass('show');
	  } else {
		btn.removeClass('show');
	  }
	});

	btn.on('click', function(e) {
	  e.preventDefault();
	  $('html, body').animate({scrollTop:0}, '300');
	});

	// add and remove form
	var max_fields      = 6; //maximum input boxes allowed
	var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
	var add_button      = $(".add_field_plus"); //Add button ID
	
	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		x++; //text box increment  
		if(x < max_fields){ //max input box allowed
			$(wrapper).append('<div class="disIB mgb20"><div class="sd_section"><h1 class="input_fields_tit" name="mytext[]"><input type="text" id="txt_business_point_title_'+x+'"  name="txt_business_point_title_'+x+'"  maxlength="40" placeholder="제목 입력(40 글자까지 입력가능)" /></h1><p class="input_fields_con mgt15" name="mytext[]"><textarea  id="txt_business_point_content_'+x+'"  name="txt_business_point_content_'+x+'" placeholder="최대 3줄까지 가능/ 200 글자까지 입력가능" rows="4" maxlength="200"></textarea></p></div><button class="remove_field add_field_remove"><i class="fas fa-minus"></i></button></div>'); //add input box
		}
		$( "#hid_areaCnt" ).val(x);
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;

		var cnt = $( "#hid_areaCnt" ).val();
		$( "#hid_areaCnt" ).val(cnt-1);
	});

	var keynum, allowedLines = 3;
	$('textarea').keydown(function(e, obj) {
        if(window.event)
            keynum = e.keyCode;
        else if (e.which)
            keynum = e.which;

        if (keynum == 13 && allowedLines <= $(this).val().split("\n").length)
            return false;
    });

    $('textarea').keyup(function(e, obj) {
        if (allowedLines < $(this).val().split("\n").length) {              
            lines = $(this).val().split("\n").slice(0, allowedLines);
            $(this).val( lines.join('\n') );
        }
 
        // Check overflow
        if ((this.clientHeight < this.scrollHeight)) {
			$(this).val($(this).val().substring(0,200));
        } 
    });