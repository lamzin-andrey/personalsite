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
	var y = 1;
	w.scrollTo(0, y);
	setTimeout(function(){
		w.scrollTo(0, y);
	}, 200);
	
}

function mainMenuBackShowDialog() {
	var v = storage('bmenualert');
	if (1 != v) {
		showScreen('backMenuInfo');
		setDOM();
	} else {
		hideLoader();
	}
}
