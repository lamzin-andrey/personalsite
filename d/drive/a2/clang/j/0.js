window.root = '/d/drive/a2/clang';
window.br = window.backRoot = '/sp/public';
window.onload = initApp;
function initApp() {
	setListeners();
	onLoadA236();
	getAuthState();
	
}
function setListeners() {
	e('bRu').onclick=onClickChooseRu;
	e('bEn').onclick=onClickChooseEn;
	attr('cLangForm', 'action', br + '/wusbsetlang/');
	e('cLangForm').onsubmit=onSubmit;
}
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
function onClickChooseEn() {
	storage('lang', 'langEn');
	e('lang').value = 'en';
}
function onClickChooseRu() {
	storage('lang', 'langRu');
	e('lang').value = 'ru';
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
	} catch(err) {
		alert(err);
	}
}

function onFailGetAuthState(data, responseText, info, xhr) {
	addClass('hWaitScreen', 'hide');
	removeClass('hCLangScreen', 'hide');
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
