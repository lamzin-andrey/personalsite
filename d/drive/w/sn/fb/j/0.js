window.root = '/d/drive/fd';
window.roota2 = '/d/drive/a2';
window.br = window.backRoot = '/sp/public';
window.onload = initApp;
function initApp() {
	window.cacheClient = new CacheSw();
	// check ssl from LS
	if (intval(storage('ssl')) === 2 && !HttpQueryString.isSSL()) {
		var s = location.href;
		s = s.replace('http://', 'https://');
		location = s;
		return;
	}
	
	// styling
	var o = getViewport(),
		h = Math.round(o.h / 2) - 64;
	stl('im', 'margin-top', h + 'px');
	getAuthState();
	onLoadA236();
}

function getAuthState() {
	Rest._token = '';
	Rest._get(onSuccessGetAuthState, '/sp/public/dast.json', onFailGetAuthState);
}
// TODO on success redirect to /d/drive/
function onSuccessGetAuthState(data) {
	try {
		if (!onFailGetAuthState(data)) {
			// TODO go home
			return;
		}
		// no auth
		e('_csrf_token').value = data.token;
		Rest._token = data.token;
		if (!data.auth) {
			initAuth();// TODO get hash and send it. In onSuccess
			e('register_form[_token]').value = data.token_reg;
			e('reset_password_form[_token]').value = data.token_res;
			return;
		}
		
		// TODO go home
		
	} catch(err) {
		alert(err);
	}
}


function showScreen(showScreenId) {
	window.prevScreen = window.currentScreen;
	window.currentScreen = showScreenId;
	var ls = cs(D, 'screen'), i, sZ = sz(ls);
	for (i = 0; i < sZ; i++) {
		addClass(e(ls[i]), 'd-none');
	}
	removeClass(e(showScreenId), 'd-none');
}


function onFailGetAuthState(data, responseText, info, xhr) {
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

function showError(s) {
	alert(s);
}

function  _map(id, read) {
	var $obj, obj, i,
		data = {}, ls, sZ;
	ls = ee(id, 'input');
	sZ = sz(ls);
	for (i = 0; i < sZ; i++) {
		data[ls[i].id] = 1;
	}
	
	ls = ee(id, 'textarea');
	sZ = sz(ls);
	for (i = 0; i < sZ; i++) {
		data[ls[i].id] = 1;
	}
	
	
	for (i in data) {
		$obj = e(i);
		obj = $obj;
		if (obj) {
			if (obj.tagName == 'INPUT' || obj.tagName == 'TEXTAREA') {
				if (!read) {
					obj.value = data[i];
				} else {
					if (obj.type == 'checkbox') {
						data[i] = obj.checked;
					} else {
						data[i] = obj.value;
					}
				}
			} else {
				if (!read) {
					if (obj.type == 'checkbox') {
						var v = data[i] == 'false' ? false: data[i];
						v = v ? true : false;
						obj.checked = v;
					} else {
						obj.innerText = data[i];
					}
				} else {
					data[i] = obj.innerText;
				}
			}
		}
	}
	
	return data;
}


function onClickShowSuccessBtn() {
	var s = window.prevScreen;
	if (s && e(s)) {
		showScreen(s);
	} else {
		hideLoader();
	}
}

function showLoader() {
	showScreen('hWaitScreen');
}
function hideLoader(screenId) {
	screenId = screenId ? screenId : 'hCatalogScreen';
	showScreen(screenId);
}

function onLoadA236() {
	if (nav.userAgent.toLowerCase().indexOf('android 2.3.6') == -1) {
		return;
	}
	d.body.style['min-height'] = '400px';
	var y = 1;
	w.scrollTo(0, y);
	setTimeout(function(){
		w.scrollTo(0, y);
	}, 1000);
}

function goURL(url) {
	var prefix = '';
	if (HttpQueryString.isSSL()) {
		url = url.replace('http://', 'https://');
		if (url.charAt(0) == '/') {
			url = HttpQueryString.SSLP + HttpQueryString.host() + url;
		}
	}
	
	location.href = url;
}
