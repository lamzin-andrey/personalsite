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
	e('agreeRE').onchange = onChangeIAgree;
	
}

function onChangeIAgree(evt) {
	var st = evt.currentTarget.checked;
	if (!st) {
		st = "You must agree to the terms of use";
		showBalloonError(L(st), 263, -54);
	} else {
		hide('lbaloon');
	}
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
	authDisForm("registerForm", 1);
	Rest._post(data, onSuccessRegister, '/sp/public/register', onFailSendRegister);
}

function onSuccessReset(data) {
	if (!onFailSendReset(data)) {
		return;
	}
	if (data.success === true) {//TODO надо сделать красиво 
		//showScreen('hAuthScreen');
		//showSuccess(data.message + ' <a href="' + data.emailHostLink + '" target="_blank">Email</a>');
		//RegScreenAnim.switchregisterForm('registerForm', 'loginForm');
	}
}

function onSuccessRegister(data) {
	var id = "lsucess";
	if (!onFailSendRegister(data)) {
		return;
	}
	if (data.success === true) {
		//showSuccess(l('Registration success, we can login'));
		v(id, L('Registration success, we can login'));
		show(id);
		v("_username", v("register_form[username]"));
		RegScreenAnim.switchregisterForm('registerForm', 'loginForm');
	}
}

function onClickLogin() {
	var data = _map('loginForm', 1);
	console.log(data);
	Rest._csrf_token = data._csrf_token;
	Rest._token_name = '_csrf_token';
	// showScreen('hWaitScreen');
	disableRegFormButtons();
	Rest._post(data, onSuccessLogin, '/sp/public/login_check', onFailSendLogin);
}

function onSuccessLogin(data) {
	if (!onFailSendLogin(data)) {
		return;
	}
	if (data.success === true) {
		showScreen('hCatalogScreen');
	}
}

function onFailSendLogin(data, responseText, info, xhr) {
	enableRegFormButtons();
	// return authDefaultResponseError(data, responseText, info, xhr);
	return showLoginError(data, responseText, info, xhr);
}

function onFailSendRegister(data, responseText, info, xhr) {
	authDisForm("registerForm", 0);
	// showScreen('hRegisterScreen');
	//return authDefaultResponseError(data, responseText, info, xhr); TODO в итоге станет не нужным
	return showRegisterError(data, responseText, info, xhr);
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
	var fid = 'emailRegisterForm', data = _map(fid, 1),
		tokenName = 'tokenRE';
	if (!checkMail(v("emailRE"))) {
		showBalloonError(L("Email required"), 182, -74);
		return;
	}
	Rest[tokenName] = data[tokenName];
	Rest._token_name = tokenName;
	authDisForm(fid, 1);
	data.scheme = HttpQueryString.isSSL() ? '2' : '1';
	Rest._post(data, onSuccessRegisterByEmail, '/sp/public/checkmail', onFailSendRegisterByEmail);
}
function onSuccessRegisterByEmail(data) {
	var msg = data.msg;
	if (!onFailSendRegisterByEmail(data)) {
		return;
	}
	if (data.success === true) {
		showScreen('hCatalogScreen');
		return;
	}
	if (data.sended) {
		attr('resetLinkMailbox', 'href', getEmailDomain(v('emailRE')));
		hide('emailRE');
		show('hSendedEmail', 'flex');
	} else {
		if (msg) {
			showBalloonError(msg, 263, -54);
		} else {
			showError(L('Unable sent email. Try later.'));
		}
		
	}
}
function onFailSendRegisterByEmail(data, responseText, info, xhr) {
	disForm("emailRegisterForm", 0);
	if (data && String(data.sended) != "undefined" || (data && data.success)) {
		return true;
	}
	
	if (data && !data.success) {
		var errors = array_values(data.errors);
		if (data.message) {
			alert(data.message);
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
			d = 'light.mail.ru'; 
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

function disableRegFormButtons()
{
	disForm('loginForm', 1);
}

function enableRegFormButtons()
{
	disForm('loginForm', 0);
}

function showBalloonError(s, x, y, id, tdx) {
	var i = (id ? id : 'lbaloon'), j = cs(e(i), 'hMsg')[0], txtX = 65;
	tdx = tdx ? tdx : 0;
	stl(i, 'left', (x + 'px'));
	stl(i, 'top', (y + 'px'));
	//stl(j, 'font-size', ('11px'));
	stl(j, 'left', ((txtX + intval(tdx)) + 'px'));
	v(j, s);
	show(i);
}

function showRegisterError(data, responseText, info, xhr) {
	var firstFieldName, v, i, x = 403, y = 403;
	if (data && data.success == true) {
		return true;
	}
	if (data.errors) {
		for (firstFieldName in data.errors) {
			v = data.errors[firstFieldName];
			break;
		}
	}
	
	i = "register_form[" + firstFieldName + "]";
	if (firstFieldName in In(["name", "surname", "email", "agree"])) {
		x = 204;
	}
	switch(firstFieldName) {
		case "name":
		case "username":
			y = -40;
			break;
		case "surname":
		case "passwordRaw":
			y = 0;
			break;
		case "email":
		case "passwordRepeat":
			y = 39;
			break;
		case "agree":
			y = 78;
			break;
	}
	if (e(i)) {
		showBalloonError(v, x, y, 'rbaloon', -7);
	}
}

function showLoginError(data, responseText, info, xhr) {
	var firstFieldName, v, i, x = 403, y = 403;
	if (data && data.success == true) {
		return true;
	}
	v = data.message;
	if (v) {
		showBalloonError(v, 132, -71, 'lpbaloon');
	}
}

function authDisForm(f, v) {
	disForm(f, v);
	hide('lbaloon');
	hide('lpbaloon');
	hide('rbaloon');
	hide('rsbaloon');
}
