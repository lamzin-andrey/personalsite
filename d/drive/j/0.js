window.onload = initDirect;
function initDirect() {
	// styling
	var o = getViewport(),
		h = Math.round(o.h / 2) - 64,
		ua = navigator.userAgent.toLowerCase(),
		savedTheme = storage('savedTheme'),
		lang;
	savedTheme = savedTheme ? savedTheme : 'a2';
	stl('im', 'margin-top', h + 'px');
	
	// choose app
	if (!sz(savedTheme) && ua.indexOf('android 2.3') != -1) {
		savedTheme = 'a2';
	} else {
		// alert('Unknown theme!');
	}
	
	if (savedTheme) {
		// If language not selected, redirect to chjoose lang
		lang = storage('lang');
		if (!(lang in In(['ru', 'en']))) {
			location = '/d/drive/' + savedTheme + '/clang';
			return;
		}
		
		location = '/d/drive/' + savedTheme + '/';
		return;
	}
	
	alert('Unknown theme!'); // TODO redirect to choose themes page
}
	
