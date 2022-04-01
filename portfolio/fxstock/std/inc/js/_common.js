
	/*Timer*/
	var j$ = jQuery;
	var UserJoinTimer = new (function() {
		var j$countdown,
			j$form, // Form used to change the countdown time
			incrementTime = 1000,
			currentTime = 180; 
			
			updateTimer = function() {
				j$countdown.html(formatTime(currentTime));
				$('#countdown1').html(formatTime(currentTime));	

				if (currentTime == 100 || currentTime < 0 ) { //1초 100=>1초
					$("#countdown1").css("display","none");
				}
				
				currentTime -= incrementTime / 10;

				if (currentTime < -currentTime*100) { 
					UserJoinTimer.timerComplete(1);
				}
			};
			this.timerComplete = function(flag) {
				if(flag == 1)
				{
					alert("인증시간이 만료되었습니다.");
					location.reload();
				}
			};
			this.init = function() {
				j$countdown = j$('#countdown');
				UserJoinTimer.Timer = j$.timer(updateTimer, incrementTime, true);
				UserJoinTimer.resetCountdown();
			};
			this.resetCountdown = function() {
				var newTime = 180 * 100;
				if (newTime > 0) {currentTime = newTime;} 
			}; 
	});	

	var count = 0,
		timer = j$.timer(function() {
			count++;
		});
	timer.set({ time : 1000, autostart : true });

	function pad(number, length) {
		var str = '' + number;
		while (str.length < length) {str = '0' + str;}
		return str;
	}
	function formatTime(time) {
		var min = parseInt(time / 6000),
			sec = parseInt(time / 100) - (min * 60),
			hundredths = pad(time - (sec * 100) - (min * 6000), 2);
		return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) ;
	}


	/*email & sms auth code send*/   
	var _authCode = {
		SendSMS:function (email, hp, msg_code, isJoin) {
			//isJoin -> 1: 회원가입
			var url		= "/std/inc/php/auth_ajax.php";
			var	params	= "rsq_type=R&msg_code="+msg_code+"&auth_target_compare="+hp+"&auth_type=phone&flag=1&mem_id="+email+"&isJoin="+isJoin;

			var rtn  = false;
			$.ajax({
				type:"post",
				url:url,
				data:params,
				dataType: "json",
				async:false,
				success:function(args)
				{
					if(args.result == "success"){
						alert(args.msg);
						rtn  = true;
						
					}else{
						alert(args.msg);
					}
				},
				error:function(e)
				{
					alert(e.responseText);
				}
			});
			return rtn;
		},
		CheckCode_SMS:function (email, hp, auth_num, isJoin) {
			//isJoin -> 1: 회원가입
			var url		= "/std/inc/php/auth_ajax.php";
			var	params	= "rsq_type=C&isJoin="+isJoin+"&auth_num="+auth_num+"&auth_target_compare="+hp+"&auth_type=phone&mem_id="+email;
			var rtn  = false;

			$.ajax({
				type:"post",
				url:url,
				data:params,
				dataType: "json",
				async:false,
				success:function(args)
				{
					if(args.result == "success"){
						alert(args.msg);
						rtn  = true;
						
					}else{
						alert(args.msg);
					}
				},
				error:function(e)
				{
					alert(e.responseText);
				}
			});
			
			return rtn;
		},
		SendEmail:function (email, msg_code) {			
			var url		= "/std/inc/php/auth_ajax.php";
			var	params	= "rsq_type=R&msg_code=6&auth_type=email&auth_target_compare=" + email;

			$.ajax({
				type:"post",
				url:url,
				data:params,
				dataType: "json",
				async:false,
				success:function(args)
				{
					if(args.result == "success"){
						alert(args.msg);
						
					}else{
						alert(args.msg);
					}
				},
				error:function(e)
				{
					alert(e.responseText);
				}
			});
		},
		CheckCode_Email:function (email, auth_code, isJoin) {
			//isJoin -> 1: 회원가입
			var url		= "/std/inc/php/auth_ajax.php";
			var	params	= "rsq_type=C&auth_type=email&isJoin="+isJoin+"&auth_target_compare=" + email + "&auth_num=" + auth_code;
			var rtn  = false;

			$.ajax({
				type:"post",
				url:url,
				data:params,
				dataType: "json",
				async:false,
				success:function(args)
				{
					if(args.result == "success"){						
						alert(args.msg);
						rtn  = true;
					}else{
						alert(args.msg);
					}
				},
				error:function(e)
				{
					alert(e.responseText);
				}
			});

			return rtn; 
		}
	}

	/*login, regist check */
	var variousChecked = {
		/* 패스워드 체크 8~16자이여야하고 아이디가 포함되어서는 안되고 같은 글자가 4자이상이면 false */
		PasswordChecked:function (passStr1, passStr2) {
			var regexp1 = /(\w)\1\1\1/;
			
			var pattern1 = /[0-9]/;	// 숫자
			var pattern2 = /[a-zA-Z]/;	// 문자
			var pattern3 = /[~!@#$%^&*()_+|<>?:{}]/;	// 특수문자
			
			if (passStr1 != passStr2) {
				alert("비밀번호가 일치하지 않습니다."); 
				return false;
			} 
			else if (passStr1.length < 8 || passStr1.length > 20) {
				alert("8자 이상 20자 이하 이여야 합니다."); 
				return false;
			} else if (passStr1.match(regexp1) != null) {
				alert("비밀번호에 같은 문자를 4번 이상 사용하실 수 없습니다."); 
				return false;
			} else if(!pattern1.test(passStr1) || !pattern2.test(passStr1) || !pattern3.test(passStr1)) {
				alert("비밀번호는 영문+숫자+특수문자를 조합하여 8자리 이상으로 생성하셔야 합니다."); 
				return false;
			} 
			else {
				return true;
			}
		}
		/* 주민번호 체크 */
		,JuminNumber:function (number1, number2) {
			var re = /^[a-zA-Z0-9]{4,12}$/ // 아이디와 패스워드가 적합한지 검사할 정규식
			var re2 = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;
 
			var arrNum1 = new Array(); // 주민번호 앞자리숫자 6개를 담을 배열
			var arrNum2 = new Array(); // 주민번호 뒷자리숫자 7개를 담을 배열

			// -------------- 주민번호 -------------

			for (var i=0; i<number1.length; i++) {
				arrNum1[i] = number1.charAt(i);
			} 

			for (var i=0; i<number2.length; i++) {
				arrNum2[i] = number2.charAt(i);
			} 

			var tempSum=0;

			for (var i=0; i<number1.length; i++) {
				tempSum += arrNum1[i] * (2+i);
			} 

			for (var i=0; i<number2.length-1; i++) {
				if(i>=2) {
					tempSum += arrNum2[i] * i;
				}
				else {
					tempSum += arrNum2[i] * (8+i);
				}
			} 

			if((11-(tempSum%11))%10!=arrNum2[6]) {
				alert("올바른 주민번호가 아닙니다."); 
				return false;
			}else{
				return true;
			}
		} 
		,BusinessNumber:function (str) {
			// 사업자번호 오류검증
			var num = new Array();
				num[0] = 1;
				num[1] = 3;
				num[2] = 7;
				num[3] = 1;
				num[4] = 3;
				num[5] = 7;
				num[6] = 1;
				num[7] = 3;
				num[8] = 5;
			var totalNumber = 0;
			var _num        = 0;
			for (i = 0; i < str.length-1; i++) {
				_num = parseInt(str.charAt(i)) * num[i];
				_num = "" + _num;
				if (i < 8) {
					totalNumber = totalNumber + parseInt(_num.charAt(_num.length-1));
				} else {
					totalNumber = (_num.charAt(_num.length-2) == "") ? totalNumber + 0 : totalNumber + parseInt(_num.charAt(_num.length-2));
					totalNumber = totalNumber + parseInt(_num.charAt(_num.length-1));
				}
			}
			totalNumber = totalNumber + parseInt(str.charAt(str.length-1));
			var num1    = str.substring(0,3);
			var num2    = str.substring(3,5);
			var num3    = str.substring(5,10);
			if (str == "") {
				alert("사업자번호를 입력하세요-1");
				return false;
			} else if (num1.length != 3 || num2.length != 2 || num3.length != 5) {
				alert("유효하지 않은 사업자 번호입니다-2");
				return false;
			} else if (totalNumber%10 != 0) {
				alert("유효하지 않은 사업자 번호입니다-3");
				return false;
			} else {
				return true;
			}
		}
		,johapNumber:function (str) {
			// 조합 고유번호 오류검증 공식
			if (str == "") {
				alert("조합 고유번호를 입력하세요.");
				return false;
			} else if (str.length != 10) {
				alert("유효하지 않은 조합 고유번호입니다-1");
				return false;
			}
			else
			{
				return true;
			}
		}
		,CorporationNumber:function (str) {
			// 법인번호 오류검증 공식
			if (str == "") {
				alert("법인번호를 입력하세요.");
				return false;
			} else if (str.length != 13) {
				alert("유효하지 않은 법인 번호입니다-1");
				return false;
			} else {

				var url		= "/std/join/_Proc/join_id_Chk.php";
				var	params	= "search_type=corporationNumber&txt_Corporation_Number=" + str;
				var rtn  = false;
				$.ajax({
					type:"post",
					url:url,
					data:params,
					dataType: "json",
					async:false,
					success:function(args)
					{
						if(args.chkCnt == 0){
							rtn =  true;
						}
						else
						{
							alert("이미 등록되 있는 법인번호입니다.");
							$("#txt_Corporation_Number").focus();
						} 
					},
					error:function(e)
					{
						alert(e.responseText);
					}
				}); 

				return rtn;  
			}
		}
	}

	/* 이메일 체크 */
	String.prototype.emailCheck = function() {
		var str = this.toString();
		return /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/i.test(str);
	}
	
	// URL Check
	var re_url = /^(https?:\/\/)?([a-z\d\.-]+)\.([a-z\.]{2,6})([\/\w\.-]*)*\/?$/; 
	
	String.prototype.trim = function() { // 양쪽여백 제거
		var str = this.toString();
		return this.replace(/^\|\s|$/g, '');
	}

	String.prototype.rtrim = function() { // 오른쪽여백 제거
		var str = this.toString();
		return this.replace(/^\s+/, '');
	}

	String.prototype.ltrim = function() { // 왼쪽여백 제거
		var str = this.toString();
		return this.replace(/\s+$/, '');
	}

	// 숫자만 입력되도록 함 
	 $("input:text[numberOnly]").on("keyup", function() {
		$(this).val($(this).val().replace(/[^0-9]/g,""));
	}); 

	// 숫자 콤마 만 입력되도록 함 
	 $("input:text[numberOnly_Comma]").on("keyup", function() {
		$(this).val(comma(uncomma($(this).val().replace(/[^0-9]/g,"")))); 
	});  

	//콤마찍기
    function comma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }
 
    //콤마풀기
    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
	
	//날짜 차이 
	function datedif_call(sdd, edd)
	{
		var ar1 = sdd.split('-');
		var ar2 = edd.split('-');
		var da1 = new Date(ar1[0], ar1[1], ar1[2]);
		var da2 = new Date(ar2[0], ar2[1], ar2[2]);
		var dif = da2 - da1;
		var cDay = 24 * 60 * 60 * 1000;// 시 * 분 * 초 * 밀리세컨

		return parseInt(dif/cDay);
	}

	//배너
	function fnSelect_banner(){
		$("#mode").val('banner');
		var str_option = $("#frm").serialize();
		$.ajax({
			url: "/std/common/api/index_api.php",
			data: str_option,
			type: 'POST',
			dataType: 'json',
			traditional: true,
			timeout: 60000,
			error: function (request, status, error) {
			},
			success: function (response) {
				
				var addCnt = response.length;
				for (var i = 0; i < addCnt; i++)
				{
					
					var banner_img	=  response[i].banner_img;  
					var banner_url	=  response[i].banner_url; 

					var str_li = "<li class='swiper-slide'><a href='"+banner_url+"' target='_blank'><img src='"+banner_img+"'></a></li> ";
					$("#ul_banner").append(str_li);  
				} 

				setTimeout(function () { swiper.update();  }, 500);
				
			}
		});
	}
	
	//투자 총 금액
	function fnFunding_invest(funding_idx)
	{
		var url		= "/std/common/api/index_api.php";
		var	params	= "mode=invest_info&funding_idx="+funding_idx;
		var rtn  = "0";
		$.ajax({
			type:"post",
			url:url,
			data:params,
			dataType: "json",
			async:false,
			success:function(args)
			{
				rtn =  args.invest_pot;
			},
			error:function(e)
			{
				alert(e.responseText);
			}
		}); 
		
		return rtn;  
	}

	
	function fn_CreateCellArr(row, cnt){
		var arr = new Array();
		for (var i = 0; i < cnt; i++)
		{
			arr.push(row.insertCell(i));
		}
		return arr;
	}

	function fn_RemoveList(obj){
		var tableObj = document.getElementById(obj);
		var tbody = tableObj.tBodies[0];
		var row = null;

		for (var i = 0; i < tbody.rows.length; i++)
		{
			tbody.deleteRow(i);
			i--;
		}
	}

	//paging tag 생성
	function set_paging(display_cnt, page, total_cnt, page_length, onChangeFnc){	
		$('#pagination li').remove();
		if(total_cnt/display_cnt>1){
			$('#pagination').paging({
				length:page_length,
				//current:$(page_obj).val(),
				//current:page,
				current:$('#frm input[name=page]').val(),
				max:Math.ceil(total_cnt/display_cnt),
				onclick:onChangeFnc
			});
		}
		return false;
	}

	//2018-11-09 jpark : define a phrase
	var division_type		= {1:"개인 투자자", 2:"법인 투자자", 3:"발행 기업"};
	var corporation_type	= {1:"투자 법인", 2:"투자 조합"};
	var issue_Type			= {1:"주식형", 2:"채권형"};
	var funding_status		= {1:"신청완료", 2:"내부심사", 3:"심사중", 4:"심사완료", 5:"신청거절"};
	var funding_type_level	= {0:"Seed", 1:"Sereis A", 2:"Sereis B", 3:"Sereis C"};