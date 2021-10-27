// @depends mmb.js
function mainMenuShow() {
	if (sz(selectedItems) > 0) {
		removeClass('bmPaste', 'menu_paste');
		removeClass('bmRemove', 'menu_paste');
	} else {
		addClass('bmPaste', 'menu_paste');
		addClass('bmRemove', 'menu_paste');
	}
	showScreen('hBotMenuScr');
	removeClass('hBotMenu', 'hide');
	mainMenuBackPush();
}

function mainMenuHide() {
	addClass('hBotMenu', 'hide');
	hideLoader();
}

function initMainMenu() {
	e('bmUpload').onclick = onClickMainMenuUpload;
	e('bmAddCatalog').onclick = onClickMainAddCatalog;
	e('bmRemove').onclick = onClickMainRemove;
	e('bmSelectMode').onclick = onClickMainSelectMode;
	e('bmPaste').onclick = onClickMainPaste;
	e('bmOptions').onclick = onClickMainOptions;
}

function onClickMainAddCatalog(evt) {
	try {
		evt.preventDefault();
		mainMenuHide();
		var s = prompt(l('Enter catalog name'), l('New catalog'));
		if (!s) {
			return;
		}
		Rest._token = e('_csrf_token').value;
		showLoader();
		Rest._post({name: s, c: currentDir}, onSuccessAddCatalog, window.br + '/driveaddcatalog.json', onFailAddCatalog);
	} catch(ex) {
		alert(ex);
	}
}
function onFailAddCatalog(data, responseText, info, xhr) {
	hideLoader();
	return defaultResponseError(data, responseText, info, xhr);
}
function onSuccessAddCatalog(data) {
	if (!onFailAddCatalog(data)) {
		return;
	}
	try {
		fileList.addCatalog(data.name, data.i, data.type);
	} catch(err) {
		alert(err);
	}
}



function onClickMainMenuUpload() {
	mainMenuHide();
	showScreen('hUpScreen');
}
function onClickMainRemove(evt) {
	evt.preventDefault();
	mainMenuHide();
	if (!sz(selectedItems)) {
		return;
	}
	
	Rest._token = e('_csrf_token').value;
	showLoader();
	// create list
	var ls = [], i;
	for (i in selectedItems) {
		ls.push(i);
	}
	Rest._post({ls: ls, t: currentDir}, onSuccessMoveFiles, 
		window.br + '/drivermls.json', 
		onFailAddCatalog
	);
}
function onClickMainSelectMode() {
	mainMenuHide();
	selectMode = selectMode ? 0 : 1;
	if (!selectMode) {
		selectedItems = {};
	}
	var ls = storage('f' + currentDir),
		bc = path.replace('/wcard', '');
	fileList.renderCurrentDir(ls, bc);
}
function onClickMainPaste(evt) {
	evt.preventDefault();
	mainMenuHide();
	if (!sz(selectedItems)) {
		return;
	}
	
	
	Rest._token = e('_csrf_token').value;
	showLoader();
	// create list
	var ls = [], i;
	for (i in selectedItems) {
		ls.push(i);
	}
	Rest._post({ls: ls, t: currentDir}, onSuccessMoveFiles, 
		window.br + '/drivemv.json', 
		onFailAddCatalog
	);
}

function onSuccessMoveFiles(data) {
	if (!onFailAddCatalog(data)) {
		return;
	}
	fileList.moveFiles(data);
}


function onClickMainOptions() {
	mainMenuHide();
	showOptions();
}
