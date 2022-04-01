
//카드 애니메이션
var $wrap = $(".move-image"),
	cursor = $(".cursor"),
	fmouseX = 0,
	fmouseY = 0,
	mouseX = 0,
	mouseY = 0,
	angleX = 0,
	angleY = 0,
	friction = 1/6;

$(document).mousemove(function(e){
	mouseX = Math.max(-100, Math.min(100, $(document).width()/2 - e.pageX));
	mouseY = Math.max(-100, Math.min(100, $(document).height()/2 - e.pageY));

	angleX = (12 * mouseX) / 100;
	angleY = (12 * mouseY) / 100;

	// console.log('mouseX ='+ mouseX);
	// console.log('mouseY ='+ mouseY);
	$(".cursor").css("left", e.pageX-15).css("top", e.pageY-15);

});

function animate(){
	fmouseX += (angleX - fmouseX) * friction;
	fmouseY += (angleY - fmouseY) * friction;

	$wrap.css({'transform': 'translate(-50%, -50%) perspective(600px) rotatex('+ fmouseY +'deg) rotateY('+ -fmouseX +'deg)'});

	window.requestAnimationFrame(animate);
};
animate()
