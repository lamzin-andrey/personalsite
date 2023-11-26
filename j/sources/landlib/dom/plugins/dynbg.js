/**
 * @depends micron.js
*/
function DymanicBodyBg(){
	var o = this;
	if (!o.isNight()) {
		o.setActualBg();
	}
}
var P = DymanicBodyBg.prototype;
P.setActualBg = function() {
	var type = '-sml', w = getViewport().w,
		endMiddle = 1000;
	if (w >= 320 && w < endMiddle) {
		type = '-m';
	} else if (w > endMiddle) {
		type = '';
	}
	stl(bod(), 'background-image', "url('/i/bg" + type + '.jpg\')');
}

P.isNight = function() {
	if (window.nightSwitcher && window.nightSwitcher.isNight()) {
		return true;
	}
}


window.addEventListener('load', function() {
	window.dynbg = new DymanicBodyBg();
}, true);
