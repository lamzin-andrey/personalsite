function initOptions() {
	e('boLeave').onclick = onClickOptionLeave;
	e('boChangeLang').onclick = onClickOptionChangeLanguage;
	e('boChangeTheme').onclick = onClickOptionChangeTheme;
	e('boCloseOpts').onclick = onClickOptionExit;
	var E = e('boSortSetting');
	E.onclick = onClickOptionSortSetting;
}

function showOptions() {
	addClass('hBotMenu', 'hide');
	removeClass('hBotOptions', 'hide');
}
function hideOptions() {
	addClass('hBotMenu', 'hide');
	addClass('hBotOptions', 'hide');
}

function onClickOptionLeave() {
	var ref = storage('referrer');
	if (!ref || ref == location.href) {
		ref = 'https://google.com';
	}
	hideOptions();
	location.href = ref;
}

function onClickOptionChangeLanguage() {
	hideOptions();
	goURL(roota2 + '/clang/');
}

function onClickOptionChangeTheme() {
	location.href = roota2 + '/cv';
	hideOptions();
}

function onClickOptionExit() {
	hideOptions();
}
function onClickOptionSortSetting() {
	showScreen('sortMenu');
}
