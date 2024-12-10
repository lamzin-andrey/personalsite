window.root = '/d/drive/a2/cv';
window.br = window.backRoot = '/sp/public';
window.onload = cv;
function cv() {
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
	initApp();
}
function initApp() {
	onLoadA236();
	addClass('hWaitScreen', 'hide');
	showActiveTheme();
	removeClass('hCVersScreen', 'hide');
	initLang();
}

function showActiveTheme() {
	var t = storage('savedTheme'),
		ct,
		aB = e('active'),
		ot = e('other');
	t = t ? t : 'a2';
	t = e(t);
	if (!t) {
		t = e('a2');
	}
	ct = cs(aB, 'card')[0];
	if (ct) {
		ot.appendChild(ct);
	}
	aB.appendChild(t);
	removeClass(ot, 'h');
}


function chooseV(s) {
	var dt = new Date();
		storage('savedTheme', s);
		goURL('/d/drive/?r=' + Math.random() + dt.getTime());
}

function goURL(url) {
	var prefix = '', s = HttpQueryString.SSLP;
	if (HttpQueryString.isSSL()) {
		url = url.replace('http://', s);
		if (url.charAt(0) == '/') {
			url = s + HttpQueryString.host() + url;
		}
	}
	
	gto(url);
}

function onLoadA236() {
	if (nav.userAgent.toLowerCase().indexOf('android 2.3.6') == -1) {
		return;
	}
	d.body.style['min-height'] = '400px';
	// d.body.style['border'] = 'black 1px solid';
	var y = 1;
	w.scrollTo(0, y);
	setTimeout(function(){
		w.scrollTo(0, y);
	}, 200);
}

function initLang() {
	var ru = e('bChooseRu'),
		en = e('bChooseEn');
	ru.onclick = ru.ontouchstart = onClickChooseRu;
	en.onclick = en.ontouchstart = onClickChooseEn;
}
function onClickChooseEn() {
	setLang('langEn');
}
function onClickChooseRu() {
	setLang('langRu');
}

function setLang(lng) {
	storage('lang', lng);
	// e('chLang').innerHTML = displayLng;
	window.L = window[lng];
	setDOM();
}
