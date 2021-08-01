// @depends mmb.js
function mainMenuShow() {
	removeClass('hBotMenu', 'hide');
	mainMenuBackPush();
}

function initMainMenu() {
	e('bmUpload').onclick = onClickMainMenuUpload;
	e('bmAddCatalog').onclick = onClickMainAddCatalog;
	e('bmRemove').onclick = onClickMainRemove;
	e('bmViewType').onclick = onClickMainViewType;
	e('bmSort').onclick = onClickMainSort;
	e('bmOptions').onclick = onClickMainOptions;
}

function onClickMainAddCatalog(evt) {
	evt.preventDefault();
	addClass('hBotMenu', 'hide');
	var s = prompt(l('Enter catalog name'), l('New catalog'));
	Rest._token = e('_csrf_token').value;
	Rest._post({name: s}, onSuccessAddCatalog, window.br + '/driveaddcatalog.json', onFailAddCatalog);
}
function onFailAddCatalog(data, responseText, info, xhr) {
	return defaultResponseError(data, responseText, info, xhr);
}
function onSuccessAddCatalog(data) {
	if (!onFailAddCatalog(data)) {
		return;
	}
	try {
		fileList.addCatalog(data.name, /*data.current*/'/');
	} catch(err) {
		alert(err);
	}
}



function onClickMainMenuUpload() {
}


function onClickMainRemove() {
}
function onClickMainViewType() {
}
function onClickMainSort() {
}

function onClickMainOptions() {
	addClass('hBotMenu', 'hide');
}
