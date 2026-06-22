/**
 * @depends micron.js
 * @depends php.js
*/
function NightSwitcher(){
	var o = this;
	if (o.isNight()) {
		o.lightOff();
	} else {
		o.lightOff(1);
	}
}
var P = NightSwitcher.prototype;
P.lightOff = function(v) {
	v ? removeClass(bod(), 'night') : addClass(bod(), 'night');
}

P.isNight = function() {
	
	var h = date('H'), M = +date('m'), d = +date('d');
	
	if (M == 2 && d >= 12 && (h >= 18 || h < 7)) {
		return true;
	}
	
	if (M == 3 && d >= 22 && (h >= 19 || h < 6)) {
		return true;
	}
	
	if (M == 4 && d < 22 && (h >= 19 || h < 6)) {
		return true;
	}
	
	if (M == 4 && d >= 22 && (h >= 20 || h < 5)) {
		return true;
	}
	
	if (h >= 21 || h < 6) {
		return true;
	}
}

window.addEventListener('load', function() {
	window.nightSwitcher = new NightSwitcher();
}, true);

