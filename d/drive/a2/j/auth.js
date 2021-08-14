// @depends u.js
// @depends mmb.js
function initAuth() {
	setListenersAuth();
}
function setListenersAuth() {
	e('bLogin').onclick = onClickLogin;
	e('bShowRegisterScreen').onclick = onClickShowRegister;
	e('bShowResetScreen').onclick = onClickShowReset;
	e('bShowAuthScreen').onclick = onClickShowAuth;
	e('bShowAuthScreen2').onclick = onClickShowAuth;
	e('bReset').onclick = onClickResetForm;
	e('bRegisterNow').onclick = onClickRegisterNow;
}

function onClickResetForm() {
	var data = _map('resetForm', 1),
		tokenName = 'reset_password_form[_token]';
	Rest[tokenName] = data[tokenName];
	Rest._token_name = tokenName;
	// showScreen('hWaitScreen');
	Rest._post(data, onSuccessReset, '/sp/public/reset', onFailSendReset);
}

function onClickShowRegister() {
	showScreen('hRegisterScreen');
}

function onClickShowAuth() {
	showScreen('hAuthScreen');
}
function onClickShowReset() {
	showScreen('hResetScreen');
}


function onClickRegisterNow() {
	var data = _map('registerForm', 1),
		tokenName = 'register_form[_token]';
	Rest[tokenName] = data[tokenName];
	Rest._token_name = tokenName;
	// showScreen('hWaitScreen');
	Rest._post(data, onSuccessRegister, '/sp/public/register', onFailSendRegister);
}

function onSuccessReset(data) {
	if (!onFailSendReset(data)) {
		return;
	}
	if (data.success === true) {
		showScreen('hAuthScreen');
		showSuccess(data.message + '<a href="' + data.emailHostLink + '" target="_blank">Email</a>');
	}
}

function onSuccessRegister(data) {
	if (!onFailSendRegister(data)) {
		return;
	}
	if (data.success === true) {
		showScreen('hAuthScreen');
		showSuccess(l('Registration success, we can login'));
	}
}

function onClickLogin() {
	var data = _map('loginForm', 1);
	Rest._csrf_token = data._csrf_token;
	Rest._token_name = '_csrf_token';
	showScreen('hWaitScreen');
	Rest._post(data, onSuccessLogin, '/sp/public/login_check', onFailSendLogin);
}

function onSuccessLogin(data) {
	if (!onFailSendLogin(data)) {
		return;
	}
	if (data.success === true) {
		location.reload();
		//showScreen('hCatalogScreen');
		//mainMenuBackPush();
	}
}

function onFailSendLogin(data, responseText, info, xhr) {
	showScreen('hAuthScreen');
	return authDefaultResponseError(data, responseText, info, xhr);
}

function onFailSendRegister(data, responseText, info, xhr) {
	showScreen('hRegisterScreen');
	return authDefaultResponseError(data, responseText, info, xhr);
}

function onFailSendReset(data, responseText, info, xhr) {
	e('reset_password_form[_token]').value = data.token;
	showScreen('hResetScreen');
	return authDefaultResponseError(data, responseText, info, xhr);
}

function authDefaultResponseError(data, responseText, info, xhr) {
	if (data && data.success == true) {
		return true;
	}
	
	if (data && data.success == false) {
		var errors = array_values(data.errors);
		if (data.message) {
			showError(data.message);
		} else if (data.errors && (errors instanceof Array) ) {
			showError(errors.join("\n"));
		}
	}
	
	return false;
}
