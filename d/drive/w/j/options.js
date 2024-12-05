function initOptions() {
	e('boChangeLang').onclick = onClickOptionChangeLanguage;
	e('boChangeTheme').onclick = onClickOptionChangeTheme;
	e('boCloseOpts').onclick = onClickOptionExit;
	e('boLogout').onclick = onClickOptionLogout;
	e('boAbout').onclick = onClickOptionAbout;
	var E = e('boSortSetting');
	E.onclick = onClickOptionSortSetting;
}

function showOptions() {
	addClass(MENU_ID, 'hide');
	removeClass('hBotOptions', 'hide');
}
function hideOptions() {
	addClass(MENU_ID, 'hide');
	addClass('hBotOptions', 'hide');
}

function onClickOptionChangeLanguage() {
	hideOptions();
	goURL(roota2 + '/clang/');
}

function onClickOptionChangeTheme() {
	hideOptions();
	goURL(roota2 + '/cv/');
}

function onClickOptionExit() {
	hideOptions();
}

function onClickOptionSortSetting() {
	showScreen('sortMenu');
}

function onClickOptionLogout() {
	var lang = storage('lang'),
		ssl = storage('ssl'),
		updateState = storage('updateState'),
		vers = storage('vers');
	localStorage.clear();
	storage('lang', lang); 
	storage('ssl', ssl);
	storage('updateState', updateState)
	storage('vers', vers);
	location.href = window.backRoot + '/logout?rd=/d/drive/';
}

function onClickOptionAbout() {
	goURL(roota2 + "/promo/");
}
