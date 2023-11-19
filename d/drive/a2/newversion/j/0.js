window.root = '/d/drive/a2/newversion';
window.br = window.backRoot = '/sp/public';
window.onload = initApp;
function initApp() {
	var cLang = storage('lang');
	if (!cLang) {
		cLang = 'langEn';
		window.L = window[cLang];
		storage('lang', cLang);
		onChangeLang();
	}
	
	setListeners();
	onLoadA236();
	addClass('hWaitScreen', 'hide');
	removeClass('hGLinkScreen', 'hide');
	setVers();
	setInterval(setVers, 800);
	startAnim();
	setTimeout(gotoRoot, 3 * 1000);
}

function gotoRoot() {
	location = '/d/drive/?v=' + $_GET['v'];
}

function setVers() {
	var v = $_GET['v'];
	e('hDisplayVersion').innerHTML = ' ' + v;
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
	ev.stopImmediatePropagation();
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
}

function onClickChooseRu() {
	setLang('langRu', 'En');
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
        q = L['hWaitGetLink'];
      } else {
        q = L['hWaitGetLink'];
      }
			
		}
		e('hWaitGetLink').innerHTML = q;
	}
}


function showError(er)
{
	//alert(er); // TODO
	removeClass('hWaitGetLink', 'infoMsg');
	addClass('hWaitGetLink', 'errMsg');
	e('hWaitGetLink').innerHTML = l(er);
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

function startAnim() {
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
	
	setInterval(onTick, 1400);
}

function onTick() {
	var src;
	
	W.cnt++;
	// if (W.cnt > 150) {
		src = attr('im2', 'src');
		if (src.indexOf('/u.png') != -1) {
			attr('im2', 'src', '/d/drive/a2/i/uu.png')
		} else if (src.indexOf('/uu.png') != -1) {
			attr('im2', 'src', '/d/drive/a2/i/u.png')
		}
		W.cnt = 0;
	//}
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
