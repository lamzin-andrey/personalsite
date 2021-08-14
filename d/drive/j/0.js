window.onload = initDirect;
function initDirect() {
	// styling
	var o = getViewport(),
		h = Math.round(o.h / 2) - 64,
		ua = navigator.userAgent.toLowerCase(),
		savedTheme = storage('savedTheme');
	savedTheme = savedTheme ? savedTheme : 'a2';
	stl('im', 'margin-top', h + 'px');
	
	// choose app
	if (!sz(savedTheme) && ua.indexOf('android 2.3') != -1) {
		savedTheme = 'a2';
	} else {
		alert('Unknown theme!');
	}
	
	if (savedTheme) {
		location = '/d/drive/' + savedTheme + '/';
	}
}
	
