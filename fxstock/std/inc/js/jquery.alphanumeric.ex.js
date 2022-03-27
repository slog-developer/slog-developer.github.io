/*
-------------------------------------------------------------
2018-03-20 jPark : jquery.alphanumeric.js  기능 확장
-------------------------------------------------------------

추가된 기능
1. 붙여넣기(ctrl+v, 컨텍스트메뉴) 시에 입력 불가 문자는 제거되고 입력 가능한 텍스트만 붙여넣기됩니다.
  ex) 숫자만 입력 받는 텍스트박스에 클립보드에 “123 “ 으로 공백문자가 포함된 문자를 붙여넣기할 경우 “123” 만 텍스트박스에 들어갑니다.

2. 한글의 경우 key가 눌렸을 때 제거되진 않지만, Blur 이벤트시에 한번더 체크하여 한글(또는 유니코드문자) 이상의 문자가 포함된 텍스트박스의 경우 한글(또는 유니코드문자)이 제거 됩니다.
  ex) 텍스트박스에 “ad한글bd” 라고 들어 있을 경우 “adbd” 만 남습니다.

3. 소수점 허용 간편화
   - 정수만 : $("#TextBox1").numeric();
   - 소수점 둘째자리까지 : $("#TextBox1").numeric({ isdecimal:true });
   - 소수점 넷짜자리까지 : $("#TextBox1").numeric({ isdecimal:true, decimalplaces:4 });
   - 음수포함 정수만 : $("#TextBox1").numeric({ negative: true }); 

   -> isdecimal 옵션은 true, decimalplaces 옵션에 허용 자릿수(디폴트:2)
   -> negative 의 default는 false, ‘?‘ 를 여러 개 입력하는 것은 허용되나 blur 이벤트에서 숫자로 볼수 없을 경우 내용을 날려버린다.
*/
(function ($) {
    $.fn.alphanumeric = function (p) {

        function input_decimal(clibText) {
            var preInputData = input.val();

            //if (preInputData.indexOf('.') > -1 && clibText.indexOf('.') > -1)
            //    return false;

            var selection = getCaretPosition(input[0]);
            var selStartPosition = selection.start, selEndPosition = selection.end;

            //if ('selectionStart' in input[0]) {
            //    selStartPosition = input[0].selectionStart;
            //    selEndPosition = input[0].selectionEnd;
            //}
            //else {
                //selection = getCaretPosition(input[0]);
                //selStartPosition = selection.start;
                //selEndPosition = selection.end;
            //}

            //if ('selectionStart' in input[0]) {
                //var selStartPosition = input[0].selectionStart;
                //var selEndPosition = input[0].selectionEnd;

                var sliceText = preInputData.slice(0, selStartPosition) + clibText + preInputData.slice(selEndPosition);
                var arrayPN = sliceText.split('.');

                if (arrayPN.length > 1 && arrayPN[1].length > options.decimalplaces)
                    return false;

                if (arrayPN.length > 2)
                    return false;
            //}

            return true;
        }

        function padding_right(s, c, n) {
            if (!s || !c || s.length >= n) {
                return s;
            }
            var max = (n - s.length) / c.length;
            for (var i = 0; i < max; i++) {
                s += c;
            }
            return s;
        }

        function getCaretPosition(inputBox) {
            if ("selectionStart" in inputBox) {
                return {
                    start: inputBox.selectionStart,
                    end: inputBox.selectionEnd
                }
            }

            //and now, the blinkered IE way
            var bookmark = document.selection.createRange().getBookmark()
            var selection = inputBox.createTextRange()
            selection.moveToBookmark(bookmark)

            var before = inputBox.createTextRange()
            before.collapse(true)
            before.setEndPoint("EndToStart", selection)

            var beforeLength = before.text.length
            var selLength = selection.text.length

            return {
                start: beforeLength,
                end: beforeLength + selLength
            }
        }
        //function setCaretPosition(ctrl, pos) {
        //    return (function () {
        //        if (ctrl.setSelectionRange) {
        //            ctrl.focus();
        //            ctrl.setSelectionRange(pos, pos);
        //        }
        //        else if (ctrl.createTextRange) {
        //            var range = ctrl.createTextRange();
        //            range.collapse(true);
        //            range.moveEnd('character', pos);
        //            range.moveStart('character', pos);
        //            range.select();
        //        }
        //    });
        //}

        function setCaretPosition(obj, position) {
            if (obj.selectionStart || obj.selectionStart == '0') {
                obj.selectionStart = position;
                obj.selectionEnd = position;
                obj.focus();
            } else if (obj.setSelectionRange) {
                obj.focus();
                obj.setSelectionRange(position, position);
            } else if (obj.createTextRange) {
                var range = obj.createTextRange();
                range.move("character", position);
                range.select();
            } else if (window.getSelection) {

                s = window.getSelection();
                var r1 = document.createRange();

                var walker = document.createTreeWalker(obj, NodeFilter.SHOW_ELEMENT, null, false);
                var p = position;
                var n = obj;

                while (walker.nextNode()) {
                    n = walker.currentNode;
                    if (p > n.value.length) {
                        p -= n.value.length;
                    }
                    else break;
                }
                n = n.firstChild;
                r1.setStart(n, p);
                r1.setEnd(n, p);

                s.removeAllRanges();
                s.addRange(r1);

            } else if (document.selection) {
                var r1 = document.body.createTextRange();
                r1.moveToElementText(obj);
                r1.setEndPoint("EndToEnd", r1);
                r1.moveStart('character', position);
                r1.moveEnd('character', position - obj.innerText.length);
                r1.select();
            }
        }

        if (navigator.userAgent.indexOf("Chrome") > -1) {
            //??
        } else {
            $(this).css("ime-mode", "disabled");
        }

        var input = $(this),
            az = "abcdefghijklmnopqrstuvwxyz",
            options = $.extend({
                ichars: '!@#$%^&*()+=[]\\\';,/{}|":<>?~`.- _',
                nchars: '',
                allow: '',
                isdecimal: false,
                decimalplaces: 2,
                negative: false
            }, p),
            s = options.allow.split(''),
            i = 0,
            ch,
            regex;
        
        for (i; i < s.length; i++) {
            if (options.ichars.indexOf(s[i]) != -1) {
                s[i] = '\\' + s[i];
            }
        }

        if (options.nocaps) {
            options.nchars += az.toUpperCase();
        }
        if (options.allcaps) {
            options.nchars += az;
        }

        if (options.decimalplaces < 0)
            options.decimalplaces = 0;

        options.allow = s.join('|');

        regex = new RegExp(options.allow, 'gi');
        ch = (options.ichars + options.nchars).replace(regex, '');

        input.bind('paste', function (e) {

            var cliptext = "", tmpClip = "";
            var clp = (e.originalEvent || e).clipboardData;

            if (clp === undefined || clp === null)
                cliptext = window.clipboardData.getData("text") || "";
            else
                cliptext = clp.getData("text/plain") || "";

            for (var m = 0; m < cliptext.length; m++) {
                if (ch.indexOf(cliptext.charAt(m)) == -1)
                    tmpClip += cliptext.charAt(m);
            }

            if (options.isdecimal && !input_decimal(tmpClip)) {
                e.preventDefault();
                return;
            }
            
            if (cliptext != tmpClip && tmpClip != "") {

                var selection = getCaretPosition(input[0]);
                var selStartPosition = selection.start;
                var selEndPosition = selection.end;
                
                //if ('selectionStart' in input[0]) {
                //    selStartPosition = input[0].selectionStart;
                //    selEndPosition = input[0].selectionEnd;
                //}
                //else {
                //    selection = getCaretPosition(input[0]);
                //    selStartPosition = selection.start;
                //    selEndPosition = selection.end;
                //}

                var preInputData = input.val();
                var crrCursor = (preInputData.slice(0, selStartPosition) + tmpClip).length;

                input.val(preInputData.slice(0, selStartPosition) + tmpClip + preInputData.slice(selEndPosition));
                
                //if (selection == null) {
                //    input[0].selectionStart = crrCursor;
                //    input[0].selectionEnd = crrCursor;
                //}
                //else {
                try{
                    setCaretPosition(input[0], crrCursor);
                }
                catch(e) {}
                //}
            }

            e.preventDefault();
        });
        
        input.keypress(function (e) {
            var key = String.fromCharCode(!e.charCode ? e.which : e.charCode);

            if (ch.indexOf(key) != -1 && !e.ctrlKey) {
                e.preventDefault();
            }

            if (options.isdecimal && !input_decimal(String.fromCharCode(e.charCode))) {
                    e.preventDefault();
            }
        });

        input.blur(function () {
            var value = input.val(),
                tempVal = "";

            for (var k = 0; k < value.length; k++) {
                if ((ch.indexOf(value.charAt(k)) != -1) || (escape(value.charAt(k)).length > 4)) {
                }
                else {
                    tempVal += value.charAt(k);
                }
            }
            if (value != tempVal) input.val(tempVal);
            
            if (options.isdecimal) {
                if (isNaN(input.val())) {
                    input.val('');
                    return false;
                }
                if (input.val() != "") {
                    var seedDigit = padding_right('1', '0', options.decimalplaces + 1);
                    var temp = Math.round(Number(input.val()) * seedDigit) / seedDigit;

                    if (temp != input.val())
                        input.val(temp);
                }
            }

            return false;
        });

        return input;
    };

    $.fn.numeric = function (p) {
        var az = 'abcdefghijklmnopqrstuvwxyz',
            aZ = az.toUpperCase();

        if (typeof(p) != "undefined") {
            if (typeof(p.isdecimal) != "undefined" && p.isdecimal) {
                if (typeof(p.allow) != "undefined")
                    p.allow += ".";
                else
                    p.allow = ".";
            }
            if (typeof (p.negative) != "undefined" && p.negative) {
                if (typeof(p.negative) != "undefined")
                    p.allow += "-";
                else
                    p.allow = "-";
            }
        }

        return this.each(function () {
            $(this).alphanumeric($.extend({ nchars: az + aZ }, p));
        });
    };

    $.fn.alpha = function (p) {
        var nm = '1234567890';
        return this.each(function () {
            $(this).alphanumeric($.extend({ nchars: nm }, p));
        });
    };
})(jQuery);