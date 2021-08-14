// @depends mmb.js
function mainMenuShow() {
	removeClass('hBotMenu', 'hide');
	mainMenuBackPush();
}

function initMainMenu() {
	alert('Start init mm');
	e('bmUpload').onclick = onClickMainMenuUpload;
	e('bmAddCatalog').onclick = onClickMainAddCatalog;
	e('bmRemove').onclick = onClickMainRemove;
	e('bmViewType').onclick = onClickMainViewType;
	e('bmSort').onclick = onClickMainSort;
	e('bmOptions').onclick = onClickMainOptions;
	alert('End init mm');
}

function onClickMainAddCatalog(evt) {
	alert('ICall');
	try {
		evt.preventDefault();
		addClass('hBotMenu', 'hide');
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
		fileList.addCatalog(data.name, data.i);
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
