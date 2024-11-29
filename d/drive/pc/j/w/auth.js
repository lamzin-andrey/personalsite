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

function onClickResetForm() {
	var data = _map('resetForm', 1),
		tokenName = 'reset_password_form[_token]';
	Rest[tokenName] = data[tokenName];
	Rest._token_name = tokenName;
	hideBallons();
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
	var u = v("_username");
	if (!onFailSendLogin(data)) {
		return;
	}
	if (data.success === true) {
		storage("username", u);
		patchUsername(u);
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
	// TODO showScreen('hResetScreen');
	// return authDefaultResponseError(data, responseText, info, xhr);
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
	var msg = data.msg, u = v("emailRE");
	if (!onFailSendRegisterByEmail(data)) {
		return;
	}
	if (data.success === true) {
		storage("username", u);
		patchUsername(u);
		showFileManager();
		
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

function patchUsername(u) {
	var ctab, i = "tb2", bm0 = "bm0";
	window.USER = u;
	if (!e(bm0) || !e(i)) {
		return;
	}
	v(ee("bm0", "span")[0], u);
	ctab = ee(i, "span")[0];
	if (v(ctab) == "wusb") {
		v(ctab, u);
		attr(i, "title", u);
	}
}

function onSuccessLoginByMailhash(data) {
	if (data && data.success) {
		storage("username", "wusb");
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
	app.isActive = true;
	hideLoader();
}
