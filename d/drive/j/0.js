window.onload = checkVers;
function checkVers() {
	w.br = "/sp/public";
	VersionReq.get(w, onV);
}
function onV(v) {
	var s, h = "http://", H = HttpQueryString;
	if ($_GET['v'] != v) {
		s = H.setVariable(location.href, 'v', v);
		if (H.isSSL()) {
			if (s.indexOf(h) == 0) {
				s = s.replace(h, H.SSLP);
			} else if (s.indexOf(H.SSLP) != 0) {
				s = H.SSLP + H.host() + s;
			}
		}
		gto(s);
		return;
	}
	initDirect();
}
function initDirect() {
	var url = HttpQueryString.SSLP + HttpQueryString.host() + '/d/drive/i/u.png';
	e('im').onload = function() {onCheckSsl(true)};
	e('im').onerror = function() {onCheckSsl(false)};
	attr(e('im'), 'src', url);
}
function onCheckSsl(isSSLSupport) {
	var mailhash = '';
	storage('referrer', document.referrer);
	storage('ssl', (isSSLSupport ? 2 : 1));
	mailhash = HttpQueryString._GET('mailhash');
	if (mailhash) {
		storage('mailhash', mailhash);
	}
	if (!isSSLSupport) {
		e('im').onload = function(){};
		attr(e('im'), 'src', '/d/drive/i/u.png');
	}
	// styling
	var o = getViewport(),
		h = Math.round(o.h / 2) - 64,
		ua = navigator.userAgent.toLowerCase(),
		savedTheme = storage('savedTheme'),
		lang,
		vw;
	savedTheme = savedTheme ? savedTheme : '';
	stl('im', 'margin-top', h + 'px');
	
	// choose app
	if (!sz(savedTheme) && ~ua.indexOf('android 2.3')) {
		savedTheme = 'a2';
		if (~ua.indexOf('gt-s6102')) {
			savedTheme = 'a2/gts6102';
		}
	} else {
		// check FormData
		if (window.FormData) {
			savedTheme = savedTheme ? savedTheme : 'fd';
		}
		vw = getViewport();
		if (vw.w > 799) {
			savedTheme = 'w';
			if (window.t && window.LAE && window.LST) {
				savedTheme = 'pc';
			}
		}
		savedTheme = savedTheme ? savedTheme : 'a2';
		// alert('Unknown theme!');
	}
	var hostPrefix = '', s;
	if (isSSLSupport) {
		hostPrefix = HttpQueryString.SSLP + HttpQueryString.host();
	}
	
	if (savedTheme) {
		storage('savedTheme', savedTheme);
		// If it share link, locate to share page
		if (
			HttpQueryString._GET('action', '') == 'share'
			&& HttpQueryString._GET('i', 0) > 0
		   )
		{
			location = hostPrefix + '/d/drive/a2/share/?i=' + HttpQueryString._GET('i', 0);
			return;
		}
		
		// If it link to promo, locate to share page
		if (
			HttpQueryString._GET('action', '') == 'promo'
		   )
		{
			location = hostPrefix + '/d/drive/a2/promo/';
			return;
		}
		
		// If language not selected, redirect to choose lang
		lang = storage('lang');
		if (lang != 'langRu' && lang != 'langEn') {
			location = hostPrefix + '/d/drive/a2/clang/';
			return;
		}
		s = hostPrefix + '/d/drive/' + savedTheme + '/';
		gto(HttpQueryString.setVariable(s, 'v', $_GET['v']));
		return;
	}
	
	alert('Unknown theme!'); // TODO redirect to choose themes page
}
	
