// @depends u.js
function initAuth() {
	setListenersAuth();
}
function setListenersAuth() {
	var id = 'bShowAuthScreen4', k = 'onkeypress';
	
	e('bLogin').onclick = onClickLogin;
	e('_username')[k] = onClickLogin;
	e('_password')[k] = onClickLogin;
	
	e('bReset').onclick = onClickResetForm;
	e('reset_password_form[email]')[k] = onClickResetForm;
	
	e('bRegisterNow').onclick = onClickRegisterNow;
	e("register_form[name]")[k] = onClickRegisterNow;
	e("register_form[surname]")[k] = onClickRegisterNow;
	e("register_form[email]")[k] = onClickRegisterNow;
	e("register_form[username]")[k] = onClickRegisterNow;
	e("register_form[passwordRaw]")[k] = onClickRegisterNow;
	e("register_form[passwordRepeat]")[k] = onClickRegisterNow;
	
	id = 'bRegisterNowRE';
	if (e(id)) {
		e(id).onclick = onClickRegisterByEmailNow;
		e('emailRE')[k] = onClickRegisterByEmailNow;
	}
	e('agreeRE').onchange = onChangeIAgree;
	e('agreeCC').onchange = onChangeIAgreeCC;
	e('emailRE').oninput = hideBallons;
	e('emailRE').onfocus = hideBallons;
	e('_username').oninput = hideBallons;
	e('_username').onfocus = hideBallons;
	e('_password').oninput = hideBallons;
	e('_password').onfocus = hideBallons;
	e('register_form[name]').oninput = hideBallons;
	e('register_form[name]').onfocus = hideBallons;
	e('register_form[surname]').oninput = hideBallons;
	e('register_form[surname]').onfocus = hideBallons;
	e('register_form[email]').oninput = hideBallons;
	e('register_form[email]').onfocus = hideBallons;
	e('register_form[username]').oninput = hideBallons;
	e('register_form[username]').onfocus = hideBallons;
	e('register_form[passwordRaw]').oninput = hideBallons;
	e('register_form[passwordRaw]').onfocus = hideBallons;
	e('register_form[passwordRepeat]').oninput = hideBallons;
	e('register_form[passwordRepeat]').onfocus = hideBallons;
	e('register_form[agree]').onchange = onChangeIAgree;
	e('register_form[agreeCC]').onchange = onChangeIAgreeCC;
	e('reset_password_form[email]').onfocus = hideBallons;
	e('reset_password_form[email]').oninput = hideBallons;
}

function onChangeIAgree(evt) {
	var t = evt.currentTarget, st = t.checked, k = 'lbaloon', c = 'rbaloon',
		y = 78, x = 204;
	if (t.id == 'agreeRE') {
		c = k;
		x = 263;
		y = -54;
	}
	if (!st) {
		st = "You must agree to the terms of use";
		showBalloonError(L(st), x, y, c);
	} else {
		hideBallons()
	}
}

onChangeIAgreeCC

function onChangeIAgreeCC(evt) {
	var t = evt.currentTarget, st = t.checked, k = 'lbaloon', c = 'rbaloon',
		y = 78, x = 204;
	if (t.id == 'agreeCC') {
		c = k;
		x = 263;
		y = -54;
	}
	if (!st) {
		st = "You must accept all cookies for use this site";
		showBalloonError(L(st), x, y, c);
	} else {
		hideBallons()
	}
}

function onClickResetForm(ev) {
	var data,
		tokenName = 'reset_password_form[_token]',
		v, i = "reset_password_form[email]";
	if (ev.keyCode && ev.keyCode != 13) {
		return;
	}
	data = _map('resetForm', 1);
	v = data[i].trim();
	if (!v) {
		showResetError({message: L("Value does not may be blank.")});
		return;
	}
	if (!checkMail(v)) {
		showResetError({message: L("This value is not a valid email address.")});
		return;
	}
	Rest[tokenName] = data[tokenName];
	Rest._token_name = tokenName;
	hideBallons();
	authDisForm("resetForm", 1);
	Rest._post(data, onSuccessReset, '/sp/public/reset', onFailSendReset);
}

function onClickRegisterNow(evt) {
	var data,
		tokenName = 'register_form[_token]';
	if (evt.keyCode && evt.keyCode != 13) {
		return;
	}
	data = _map('registerForm', 1);
	if (!data["register_form[name]"].trim()
	  ||!data["register_form[surname]"].trim()
	  ||!data["register_form[email]"].trim()
	  ||!data["register_form[username]"].trim()
	  ||!data["register_form[passwordRaw]"].trim()
	  ||!data["register_form[passwordRepeat]"].trim()
	) {
		showRegisterError({errors:{name: L("All values required.")}});
		return;
	}
	Rest[tokenName] = data[tokenName];
	Rest._token_name = tokenName;
	authDisForm("registerForm", 1);
	Rest._post(data, onSuccessRegister, '/sp/public/register', onFailSendRegister);
}

function onSuccessReset(d) {
	var s = "hLinkSendedPhraseStartRs";
	if (!onFailSendReset(d)) {
		return;
	}
	if (d.success === true) {
		hide("reset_password_form[email]");
		v(s, L(s));
		attr("resetLinkMailboxRs", "href", getEmailDomain(v("reset_password_form[email]")));
		show("hSendedEmailRs");
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

function onClickLogin(evt) {
	var data;
	if (evt.keyCode && evt.keyCode != 13) {
		return;
	}
	data = _map('loginForm', 1);
	if (!data._username.trim() && ! data._password.trim()) {
		showLoginError({message: L("Value does not may be blank.")});
		return;
	}
	Rest._csrf_token = data._csrf_token;
	Rest._token_name = '_csrf_token';
	// showScreen('hWaitScreen');
	disableRegFormButtons();
	Rest._post(data, onSuccessLogin, '/sp/public/login_check', onFailSendLogin);
}

function onSuccessLogin(data) {
	var u = v("_username");
	if (!onFailSendLogin(data)) {
		return;
	}
	if (data.success === true) {
		storage("username", u);
		showFileManager();
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
	authDisForm("resetForm", 0);
	return showResetError(data, responseText, info, xhr);
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

function onClickRegisterByEmailNow(evt) {
	var fid = 'emailRegisterForm', data = _map(fid, 1),
		tokenName = 'tokenRE';
	if (evt.keyCode && evt.keyCode != 13) {
		return;
	}
	
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
	var msg = data.msg, u = v("emailRE"), s;
	if (!onFailSendRegisterByEmail(data)) {
		return;
	}
	if (data.success === true) {
		storage("username", u);
		showFileManager();
		
		return;
	}
	if (data.sended) {
		s = "hLinkSendedPhraseStart";
		v(s, L(s));
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
		case 'yandex.ru':
		case 'ya.ru':
		case 'narod.ru':
			d = 'mail.yandex.ru'; 
			break;
	}
	return 'https://' + d;
}

function loginByMailhash() {
	var h = storage('mailhash');
	if (h) {
		localStorage.removeItem('mailhash');
		Rest._get(onSuccessLoginByMailhash, '/sp/public/loginmailink?hash=' + h, onFailLoginByMailhash);
	}
}



function onSuccessLoginByMailhash(d) {
	if (d && d.success) {
		storage("username", d.username); // TODO
		reload();
	}
}

function onFailLoginByMailhash(data, responseText, info, xhr) {
	showError(L("Link expired"));
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
	stl(j, 'left', ((txtX + intval(tdx)) + 'px'));
	v(j, L(s));
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
	if (firstFieldName in In(["name", "surname", "email", "agree", "agreeCC"])) {
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
		case "agreeCC":
			y = 88;
			break;
	}
	
	if (~v.indexOf('narod.ru')) {
		y -= 150;
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


function showResetError(data, responseText, info, xhr) {
	var firstFieldName, v, i;
	if (data && data.success == true) {
		return true;
	}
	v = data.message;
	if (v) {
		showBalloonError(v, 132, -42, 'rsbaloon');
	}
}

function authDisForm(f, v) {
	disForm(f, v);
	hideBallons();
}

function hideBallons()
{
	hide('lbaloon');
	hide('lpbaloon');
	hide('rbaloon');
	hide('rsbaloon');
}

function showFileManager() {
	showScreen("hCatalogScreen");
	fmgr.isActive = true;
	hideLoader();
}
