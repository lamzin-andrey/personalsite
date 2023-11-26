/**
 * @depends micron.js
*/
function NightSwitcher(){
	var o = this;
	if (o.isNight()) {
		o.lightOff();
	}
}
var P = NightSwitcher.prototype;
P.lightOff = function() {
	addClass(bod(), 'night');
}

P.isNight = function() {
	
	var dt = new Date(), h = parseInt(dt.getHours());
	if (h >= 21 && h < 6) {
		return true;
	}
}

window.addEventListener('load', function() {
	window.nightSwitcher = new NightSwitcher();
}, true);

