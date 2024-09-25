// @depends u.js
// @depends mmb.js
function initAuth() {
	setListenersAuth();
}
function setListenersAuth() {
	var id = 'bShowAuthScreen4';
	e('bLogin').onclick = onClickLogin;
	
	
	e('bReset').onclick = onClickResetForm;
	e('bRegisterNow').onclick = onClickRegisterNow;
	id = 'bRegisterNowRE';
	if (e(id))
		e(id).onclick = onClickRegisterByEmailNow;
	
}

function onClickResetForm() {
	var data = _map('resetForm', 1),
		tokenName = 'reset_password_form[_token]';
	Rest[tokenName] = data[tokenName];
	Rest._token_name = tokenName;
	// showScreen('hWaitScreen');
	Rest._post(data, onSuccessReset, '/sp/public/reset', onFailSendReset);
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
		showSuccess(data.message + ' <a href="' + data.emailHostLink + '" target="_blank">Email</a>');
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

function checkMail(sEmail) {
    var reg = /^[\w\.]+[^\.]@[\w]+\.[\w]{2,4}/mig;
    if (reg.test(sEmail)) {
		return true;
	}
}

function onClickRegisterByEmailNow() {
	var data = _map('emailRegisterForm', 1),
		tokenName = 'tokenRE';
	if (!checkMail(v("emailRE"))) {
		showError(l("Email required"));
		return;
	}
	Rest[tokenName] = data[tokenName];
	Rest._token_name = tokenName;
	attr('im', 'src', root + '/i/clos48.png');
	showLoader();
	data.scheme = HttpQueryString.isSSL() ? '2' : '1';
	Rest._post(data, onSuccessRegisterByEmail, '/sp/public/checkmail', onFailSendRegisterByEmail);
}
function onSuccessRegisterByEmail(data) {
	if (!onFailSendRegisterByEmail(data)) {
		return;
	}
	if (data.success === true) {
		attr('im', 'src', roota2 + '/i/clos48.png');
		showLoader();
		location.reload();
		return;
	}
	if (data.sended) {
		showMessage('<a href="' 
			+ getEmailDomain(v('emailRE'))
			+ '" target="_blank">'
			+ l('Email') + '</a>'
			+ l(' with login link was sent'));
	} else {
		if (data.msg) {
			showError(data.msg);
		} else {
			showError(l('Unable sent email. Try later.'));
		}
		
	}
}
function onFailSendRegisterByEmail(data, responseText, info, xhr) {
	showScreen('hRegisterEScreen');
	if (data && String(data.sended) != "undefined" || (data && data.success)) {
		return true;
	}
	
	if (data && !data.success) {
		var errors = array_values(data.errors);
		if (data.message) {
			showError(data.message);
		} else if (data.errors && (errors instanceof Array) ) {
			showError(errors.join("\n"));
		}
	}
	
	return false;
}

function getEmailDomain(s) {
	var a = s.split('@'), d = a[1];
	switch (d) {
		case 'mail.ru':
			d = 'm.mail.ru'; 
			break;
	}
	return 'https://' + d;
}

function loginByMailhash() {
	var h = storage('mailhash');
	if (h) {
		attr('im', 'src', roota2 + '/i/clos48.png');
		showLoader();
		Rest._get(onSuccessLoginByMailhash, '/sp/public/loginmailink?hash=' + h, onFailLoginByMailhash);
		localStorage.removeItem('mailhash');
	}
}

function onSuccessLoginByMailhash(data) {
	if (data && data.success) {
		reload();
	}
}

function onFailLoginByMailhash(data, responseText, info, xhr) {
	attr('im', 'src', roota2 + '/i/u.png');
	showScreen("hRegisterEScreen");
}
