window.root = '/d/drive/pc';
window.roota2 = '/d/drive/a2';
window.br = window.backRoot = '/sp/public';
window.path = '/wcard';
window.currentDir = 0;
window.parentDir = 0;
window.homeDir = 0;
window.selectMode = 0;
window.selectedItems = {};
function initApp() {
	//TODO ubcomment me window.cacheClient = new CacheSw();
	// check ssl from LS
	if (intval(storage('ssl')) === 2 && !HttpQueryString.isSSL()) {
		var s = location.href;
		s = s.replace('http://', 'https://');
		location = s;
		return;
	}
	
	// styling
	
	initAuth();
	upload.init();
	getAuthState();
}

function getAuthState() {
	Rest._token = '';
	Rest._get(onSuccessGetAuthState, br + '/dast.json', onFailGetAuthState);
}

function onSuccessGetAuthState(data) {
	var bm0, bm;
	if (!onFailGetAuthState(data)) {
		return;
	}
	// no auth
	e('_csrf_token').value = data.token;
	Rest._token = data.token;
	if (!data.auth) {
		clearInterval(window.bootSe2d.mainInterval);
		clearInterval(window.bootSe2d.app.logoRtIval);
		showScreen('hRegisterEScreen');
		e('register_form[_token]').value = data.token_reg;
		e('reset_password_form[_token]').value = data.token_res;
		e('tokenRE').value = data.token_reg;
		loginByMailhash();// TODO а здесь как будет?
		return;
	}
	
	storage("tsz", data.t);
	storage("username", data.u);
	window.tsz = data.t;
	hideLoader();
	
}

function onSuccessGetAuthStateLite(d) {
	if (d.auth) {
		Rest2._setToken(d.token, "_token");
	}
}

function gts() {
	return window.tsz ||  storage("tsz");
}

function showMessage(s) {
	v('hAlertMessage', s);
	showScreen('hAlertScreen');
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
	
	if (!data) {
		try {
			data = JSON.parse(responseText);
		}catch(err) {
			;
		}
	}
	
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
	
	showError(defErr());
	return false;
}

function defErr() {
	return L("Restart application and try again.");
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

function showSuccess(s) {
	alert(s);
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
	var path, lastLoc, ex = [""], st, fid = 0;
	screenId = screenId ? screenId : 'hCatalogScreen';
	showScreen(screenId);
	app.isActive = 1;
	// TODO
	window.USER = window.USER ? window.USER : storage("username");
	path = "/home/" + USER;
	
	lastLoc = storage("lastLoc");
	if (lastLoc && fmgr) {
		lastLoc.st = mclone(lastLoc.st);
		lastLoc.st.pop();
		fmgr.addressPanel.buttonAddress.stack = lastLoc.st;
		fmgr.addressPanel.buttonAddress.currentId = lastLoc.st[sz(lastLoc.st) - 1];
		fmgr.tab.currentFid = fmgr.addressPanel.buttonAddress.currentId;
		path = lastLoc.path;
		fid = lastLoc.fid;
		ex = [""];
	}
	fmgr.setActivePath(path, ex, fid);
	
	clearInterval(window.bootSe2d.mainInterval);
	clearInterval(window.bootSe2d.app.logoRtIval);
}

function setUpButtonDisable(bUp) {
	bUp.style['background-image'] = "url('../a2/i/up_button_bg_d.png')";
	bUp.style['color'] = '#546575';
}

function setUpButtonEnable(bUp) {
	bUp.style['background-image'] = null;
	bUp.style['color'] = null;
}

function setHomeButtonDisable(bH) {
	bH.style['background-image'] = "url('../a2/i/home-bg-d.png')";
	bH.style['color'] = '#546575';
}

function setHomeButtonEnable(bH) {
	bH.style['background-image'] = null;
	bH.style['color'] = null;
}

function showInputDlg(legend, s) {
	s = prompt(legend, s);
	if (s && (window.onClickInputDlgOk instanceof Function)) {
		window.onClickInputDlgOk({inputStr:s});
	} else if (window.onClickInputDlgCancel instanceof Function){
		window.onClickInputDlgCancel();
	}
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

// To micron
function disForm(id, disabled) {
	disFormHlp(id, disabled, 'input');
	disFormHlp(id, disabled, 'button');
	disFormHlp(id, disabled, 'textarea');
	disFormHlp(id, disabled, 'select');
}
function disFormHlp(id, disabled, tag) {
	var ls = ee(id, tag), i, SZ = sz(ls), m = "disabled", s = (disabled ? m : "");
	for (i = 0; i < SZ; i++) {
		if (s) {
			attr(ls[i], s, s);
		} else {
			ls[i].removeAttribute(m);
		}
	}
}

function DevNull(){}
