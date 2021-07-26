// @depends mmenu/menu.js
function initMainMenuBack() {
	window.mainMenuBackPushFlag = false;
}

function mainMenuBackPush() {
	if (!window.mainMenuBackPushFlag) {
		window.mainMenuBackPushFlag = true;
		history.pushState(null, null, window.root + "#d");
		window.onpopstate=onMainMenuBackButton;
	}
}

function onMainMenuBackButton() {
	window.mainMenuBackPushFlag = false;
	mainMenuShow();
	
}
