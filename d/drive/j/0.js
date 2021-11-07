window.onload = initDirect;
function initDirect() {
	Rest._token = 'free';
	var url = HttpQueryString.SSLP + HttpQueryString.host() + '/0.html';
	Rest._get(
		function() {onCheckSsl(true)},
		url,
		function() {onCheckSsl(false)},
	);
}
function onCheckSsl(isSSLSupport) {
	storage('referrer', document.referrer);
	storage('ssl', (isSSLSupport ? 2 : 1));
	// styling
	var o = getViewport(),
		h = Math.round(o.h / 2) - 64,
		ua = navigator.userAgent.toLowerCase(),
		savedTheme = storage('savedTheme'),
		lang;
	savedTheme = savedTheme ? savedTheme : '';
	stl('im', 'margin-top', h + 'px');
	
	// choose app
	if (!sz(savedTheme) && ua.indexOf('android 2.3') != -1) {
		savedTheme = 'a2';
		if (ua.indexOf('gt-s6102') != -1) {
			savedTheme = 'a2/gts6102';
		}
	} else {
		// check FormData
		if (FormData) {
			savedTheme = 'fd';
		}
		savedTheme = savedTheme ? savedTheme : 'a2';
		// alert('Unknown theme!');
	}
	var hostPrefix = '';
	if (isSSLSupport) {
		hostPrefix = HttpQueryString.SSLP + HttpQueryString.host();
	}
	
	if (savedTheme) {
		// If language not selected, redirect to choose lang
		lang = storage('lang');
		if (lang != 'langRu' && lang != 'langEn') {
			location = hostPrefix + '/d/drive/a2/clang';
			return;
		}
		location = hostPrefix + '/d/drive/' + savedTheme + '/';
		return;
	}
	
	alert('Unknown theme!'); // TODO redirect to choose themes page
}
	
