function initOptions() {
	e('boLeave').onclick = onClickOptionLeave;
	e('boChangeLang').onclick = onClickOptionChangeLanguage;
	e('boChangeTheme').onclick = onClickOptionChangeTheme;
	e('boCloseOpts').onclick = onClickOptionExit;
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
	location.href = roota2 + '/clang';
}

function onClickOptionChangeTheme() {
	location.href = root; // Это не догма, мути что хочешь
	hideOptions();
}

function onClickOptionExit() {
	hideOptions();
}
