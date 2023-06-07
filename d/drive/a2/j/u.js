window.root = '/d/drive/a2';
window.roota2 = '/d/drive/a2';
window.br = window.backRoot = '/sp/public';
window.path = '/wcard';
window.currentDir = 0;
window.parentDir = 0;
window.homeDir = 0;
window.selectMode = 0;
window.selectedItems = {};
window.onload = initApp;
function initApp() {
	// styling
	var o = getViewport(),
		h = Math.round(o.h / 2) - 64;
	stl('im', 'margin-top', h + 'px');
	
	initAuth();
	initMainMenu();
	initMainMenuBack();
	fileList.initUpButton(e('bUp'));
	fileList.initHomeButton(e('bHome'));
	initOptions();
	
	upload.init();
	window.uploadAnimation = new LoaderAnim('imgUp', 1, 5, 'dompb', -41, 100);
	
	e('hAlertScreen').onclick = onClickShowSuccessBtn;
	
	setRadioBehaviorForCboxes(['chSortByNameAsc', 'chSortByNameDesc', 'chSortBySizeAsc', 'chSortBySizeDesc', 'chSortByModifyTimeAsc', 'chSortByModifyTimeDesc']);
	sortSettingDlgInit();
	
	getAuthState();
	onLoadA236();
	alert("U3");
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
		if (!data.auth) {
			showScreen('hAuthScreen');
			e('register_form[_token]').value = data.token_reg;
			e('reset_password_form[_token]').value = data.token_res;
			return;
		}
		window.fileList.loadCurrentDir();
		mainMenuBackPush();
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

function showMessage(s) {
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
	e('hAlertMessage').innerHTML = s;
	showScreen('hAlertScreen');
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

function setUpButtonDisable(bUp) {
	bUp.style['background-image'] = "url('./a2/i/up_button_bg_d.png')";
	bUp.style['color'] = '#546575';
}

function setUpButtonEnable(bUp) {
	bUp.style['background-image'] = null;
	bUp.style['color'] = null;
}

function setHomeButtonDisable(bH) {
	bH.style['background-image'] = "url('./a2/i/home-bg-d.png')";
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
	}, 1000);
}
