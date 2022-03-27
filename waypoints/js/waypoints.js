
var $dipper1 = $('.dipper1');
var $dipper2 = $('.dipper2');
var $dipper3 = $('.dipper3');
var $dipper4 = $('.dipper4');

$dipper1.waypoint(function (direction) {
	if (direction == 'down'){
		$dipper1.addClass('js-dipper-animation');
	} else {
		$dipper1.removeClass('js-dipper-animation');
	}
}, { offset: '50%'});

$dipper2.waypoint(function (direction) {
	if (direction == 'down'){
		$dipper2.addClass('js-dipper-animation');
	} else {
		$dipper2.removeClass('js-dipper-animation');
	}
}, { offset: '50%'});

$dipper3.waypoint(function (direction) {
	if (direction == 'down'){
		$dipper3.addClass('js-dipper-animation');
	} else {
		$dipper3.removeClass('js-dipper-animation');
	}
}, { offset: '50%'});

$dipper4.waypoint(function (direction) {
	if (direction == 'down'){
		$dipper4.addClass('js-dipper-animation');
	} else {
		$dipper4.removeClass('js-dipper-animation');
	}
}, { offset: '50%'});
