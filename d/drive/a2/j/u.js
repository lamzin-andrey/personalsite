window.onload = initApp;
function initApp() {
	// styling
	var o = getViewport(),
		h = Math.round(o.h / 2) - 64;
	stl('im', 'margin-top', h + 'px');
	
	initAuth();
	
	getAuthState();
}

function getAuthState() {
	Rest._token = '';
	Rest._get(onSuccessGetAuthState, '/sp/public/dast.json', onFailGetAuthState);
}
function onSuccessGetAuthState(data) {
	if (!onFailGetAuthState(data)) {
		return;
	}
	// no auth
	if (!data.auth) {
		showScreen('hAuthScreen');
		e('_csrf_token').value = data.token;
		e('register_form[_token]').value = data.token_reg;
		e('reset_password_form[_token]').value = data.token_res;
		return;
	}
	showScreen('hCatalogScreen');
}

function showScreen(showScreenId) {
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

function showSuccess(s) {
	alert(s);
}