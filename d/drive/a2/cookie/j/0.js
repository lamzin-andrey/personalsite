window.root = '/d/drive/a2/share';
window.br = window.backRoot = '/sp/public';
window.onload = gv;
function gv() {
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
	initApp();
}
function initApp() {
	var cLang = storage('lang');
	if (!cLang) {
		cLang = 'langEn';
		window.L = window[cLang];
		storage('lang', cLang);
		onChangeLang();
	} else {
		setLangImageOnly(cLang);
	}
	
	setListeners();
	onLoadA236();
	// getAuthState();
	addClass('hWaitScreen', 'hide');
	removeClass('hGLinkScreen', 'hide');

	startBgAnim();
}

function setListeners() {
	e('chLang').ontouchstart = onChangeLang;
	e('chLang').onclick = onChangeLang;
}

// ??
function onSubmit(evt) {
	if (window.submitOk) {
		return true;
	}
	evt.preventDefault();
	var ival = setInterval(function(){
		if (storage('lang') && e('lang').value) {
			clearInterval(ival);
			window.submitOk = true;
			e('cLangForm').submit();
		}
	}, 500);
	return false;
}

function onChangeLang(ev)
{
	// ev.stopImmediatePropagation();
	ev.preventDefault();
	var cLang = storage('lang');
	cLang = cLang ? cLang : 'langRu';
	
	if (cLang == 'langRu') {
		onClickChooseEn();
	} else {
		onClickChooseRu();
	}
}

function onClickChooseEn() {
	setLang('langEn', 'Ru');
	attr('bDwnl', 'src', W.root + '/i/dbtnen.png');
}

function onClickChooseRu() {
	setLang('langRu', 'En');
	attr('bDwnl', 'src', W.root + '/i/dbtn.png');
}

function setLangImageOnly(lng)
{
	var img = (lng == 'langEn' ? '/i/dbtnen.png' : '/i/dbtn.png');
	attr('bDwnl', 'src', W.root + img);
}

function setLang(lng, displayLng) {
	var s = e('hWaitGetLink').innerHTML.trim(), q,
		isErr = hasClass(e('hWaitGetLink'), 'errMsg');
	storage('lang', lng);
	e('chLang').innerHTML = displayLng;
	window.L = window[lng];
	setDOM();
	if (s) {
		q = l(s);
		if (q == s && isErr) {
			q = 'You have not access to this page';
		}
		if (q == s && !isErr) {
      if('langEn' == lng) {
        q = '<div>The Site uses cookies and similar methods to recognize visitors and remember preferences.</div>\
<div>The Site may also use them in the future to measure campaign effectiveness and analyze site traffic.</div>\
<div>By selecting "Accept", you agree to our and trusted third parties\' use of these methods.</div>';
      } else {
        q = L['Click download button'];
      }
			
		}
		e('hWaitGetLink').innerHTML = q;
	}
}

function getAuthState() {
	Rest._token = '';
	Rest._get(onSuccessGetAuthState, '/sp/public/dast.json', onFailGetAuthState);
}

function onSuccessGetAuthState(data) {
	try {
		if (!onFailGetAuthState(data)) {
			return;
		}
		// no auth
		e('_csrf_token').value = data.token;
		Rest._token = data.token;
		getLink();
	} catch(err) {
		alert(err);
	}
}

function getLink() {
	Rest._get(onSuccessGetDLink,
			br + '/drivegetlink.json?i=' + HttpQueryString._GET('i', 0),
			onFailGetDLink
	);
}

function onSuccessGetDLink(data){
	if (this.onFailGetDLink(data) && data.link) {
		//window.location.href = data.link;
		attr('bLink', 'href', data.link);
		show('dBtnWrap');
		removeClass('dBtnWrap', 'hide');
		e('hWaitGetLink').innerHTML = l('Click download button');
	}
}

function onFailGetDLink(data, responseText, info, xhr) {
	return defaultResponseError(data, responseText, info, xhr);
}

function showError(er)
{
	//alert(er); // TODO
	removeClass('hWaitGetLink', 'infoMsg');
	addClass('hWaitGetLink', 'errMsg');
	e('hWaitGetLink').innerHTML = l(er);
}

function onFailGetAuthState(data, responseText, info, xhr) {
	addClass('hWaitScreen', 'hide');
	removeClass('hGLinkScreen', 'hide');
	return defaultResponseError(data, responseText, info, xhr);
}

function defaultResponseError(data, responseText, info, xhr) {
	if (data && data.status == 'ok') {
		return true;
	}
	
	if (data && data.status == 'error') {
		if (data.error) {
			showError(data.error);
		} else if (data.errors && (data.errors instanceof Array) ) {
			showError(data.errors.join("\n"));
		}
	}
	
	return false;
}

function startBgAnim() {
	var dt = new Date(),
		H = parseInt(dt.getHours()),
		i = 'body',
		d = 'day',
		n = 'nig';
	
	if (H > 6 && H < 20) {
		removeClass(i, n);
		addClass(i, d);
	}
	if (H >= 20) {
		removeClass(i, d);
		addClass(i, n);
	}
	
	W.bgX = 0;
	W.directx = -1;
	W.directy = -1;
	W.xlim = 731;
	W.ylim = 341;
	W.cnt = 0;
	W.ai = setInterval(onTick, 1000 / 98);
}

function onTick() {
	var 
		b = 'background-',
		p = b + 'position-',
		k = p + 'x',
		q = p + 'y',
		i = 'body',
		x = parseFloat(stl(i, k)),
		y = parseFloat(stl(i, q)),
		dy, dx,
		src,
		wp, pr, bf;
	W.cnt++;
	
	if (W.cnt > 150) {
		src = attr('im2', 'src');
		if (src.indexOf('/u.png') != -1) {
			attr('im2', 'src', '/d/drive/a2/i/uu.png')
		} else if (src.indexOf('/uu.png') != -1) {
			attr('im2', 'src', '/d/drive/a2/i/u.png')
		}
		W.cnt = 0;
	}
	
	wp = getViewport();
	pr = getParams(wp.w, wp.h);
	
	W.xlim = pr.vlim - wp.w;
	W.ylim = pr.hlim - wp.h;
	
	if (wp.w > wp.h) {
		bf = pr.vlim;
		pr.vlim = 0;
		pr.hlim = bf;
		W.xlim = pr.vlim - wp.w;
		W.ylim = pr.hlim - wp.h;
	}
	
	
	
	if (wp.w > pr.vlim || wp.h > pr.hlim) {
		stl(i, b + 'size', 'cover');
		stl(i, b + 'repeat', 'no-repeat');
		stl(i, p + 'x', '0');
		stl(i, p + 'y', '0');
		return;
	} else {
		stl(i, b + 'size', pr.size);
		stl(i, b + 'repeat', pr.repeat);
	}
		
	x = isNaN(x) ? 0 : x;
	y = isNaN(y) ? 0 : y;
	dx = W.directx * 0.1;
	x += dx;
	if (W.directx == -1 && Math.abs(x) >= W.xlim) {
		x -= dx;
		W.directx = 1;
		stl(i, k, x + 'px');
		return;
	}
	if (W.directx == 1 && x >= 0) {
		x = 0;
		W.directx = -1;
		stl(i, k, x + 'px');
		return;
	}
	stl(i, k, x + 'px');
	
	
	dy = W.directy * 0.2;
	y += dy;
	if (wp.w <= wp.h) {
		bf = Math.abs(y);
	} else {
		bf = y;
	}
	
	if (W.directy == -1 && bf >= W.ylim) {
		y -= dy;
		W.directy = 1;
		stl(i, q, y + 'px');
		return;
	}
	
	if (W.directy == 1 && y >= 0) {
		y = 0;
		W.directy = -1;
		stl(i, q, y + 'px');
		return;
	}
	
	if (!pr.b) {
		stl(i, q, y + 'px');
	}
	
	
}


function getParams(w, h) {
	var r = {}, a = 'auto';
	r.hlim = 656;
	r.vlim = 1050;
	r.size = a;
	r.repeat = a;
	
	if (w >= 320) {
		r.hlim = 860;
		r.size = 'auto';
		r.repeat = 'no-repeat';
		r.b = 1;
		if (w < h && w > 390) {
			r.size = 'cover';
			if (!hasClass("hWaitGetLink", "b")) {
				removeClass("hWaitGetLink", "m");
				addClass("hWaitGetLink", "b");
			}
		} else if (w < h && w >= 240) {
			if (!hasClass("hWaitGetLink", "m")) {
				removeClass("hWaitGetLink", "b");
				addClass("hWaitGetLink", "m");
			}
		}
		if (w > h && w < 500) {
			removeClass("hWaitGetLink", "b");
			removeClass("hWaitGetLink", "m");
		} else if (w > h && w >= 500) {
			addClass("hWaitGetLink", "b");
		}
	}
	
	return r;
}

function onLoadA236() {
	if (nav.userAgent.toLowerCase().indexOf('android 2.3.6') == -1) {
		return;
	}
	d.body.style['min-height'] = '320px';
	// d.body.style['border'] = 'black 1px solid';
	var y = 1;
	w.scrollTo(0, y);
	setTimeout(function(){
		w.scrollTo(0, y);
	}, 200);
}
