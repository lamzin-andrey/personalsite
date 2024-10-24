window.SCROLL_LINE_HEIGHT = 18;
window.VERTICAL_SCROLL_COUNT = 1;
function main() {
	window.AppStartTime = time();
	try {
		var lang = Settings.get('currentLang');
		if (lang != 'ru' && lang != 'en') {
			lang = 'ru';
		}
	} catch (err) {
		alert(err);
	}
	Qt.setWindowIconImage(Qt.appDir() + '/i/folder32.png');
	Qt.maximize();
	
	
	
	//try {
		window.app = new FileManager();
		window.app.init();
	/*} catch(err) {
		alert(err);
	}/**/
	initApp();
	
	if (lang == 'ru' || lang == 'en') {
		onClickChangeLang(lang);
	}
	/*setTimeout( function(){
		showScreen("fmgr");
	}, 1000);*/
	
	try {
		window.onresize = onResize;
		window.onkeydown = onKeyUp;
		onResize();
	} catch(err) {
		//TODO 
		console.log(err);
	}
	//showScreen("fmgr");
}

function showScreen(id) {
	var ls = cs(d, "scr"), i, SZ = sz(ls);
	for (i = 0; i < SZ; i++) {
		hide(ls[i]);
	}
	show(id);
}

function onResize() {
	var contentTopAreaH, 
		vp = getViewport(),
		vpH = vp.h,
		vpW = vp.w,
		dY = 48,
		tabsContainer;
	// for table
	e('contentArea').style.height = (vpH - 0) + 'px';
	e('contentArea').style.maxHeight = (vpH - dY) + 'px';
	
	e('sidebarWrapper').style.height = (vpH - 0) + 'px';
	e('sidebarWrapper').style.maxHeight = (vpH - dY - 1) + 'px';
	
	setTimeout(function() {
		e('contentArea').style.height = (vpH - 0) + 'px';
		e('contentArea').style['max-height'] = (vpH - dY) + 'px';
		
		e('sidebarWrapper').style.height = (vpH - 0) + 'px';
		e('sidebarWrapper').style.maxHeight = (vpH - dY) + 'px';
		app.onResize();
	}, 1000);
	
	// for items
	
	tabsContainer = e('tabsContainer');
	
	contentTopAreaH = (tabsContainer ? tabsContainer.offsetHeight : 0) + e('addressContainer').offsetHeight;
	if (e('tabItems')) {
		e('tabItems').style.height = (vpH - contentTopAreaH - 1.7*dY) + 'px';
		e('tabItems').style["max-height"] = (vpH - contentTopAreaH - 1.7*dY) + 'px';
		// resizeTabItemsWidth();
		e('tabItems').style.height = (e('contentArea').offsetHeight - contentTopAreaH - 0.7*dY) + 'px';
		e('tabItems').style["max-height"] = (e('contentArea').offsetHeight - contentTopAreaH - 0.7*dY) + 'px';
	}
	app.onResize();
	
}

function onKeyUp(evt) {
    if (evt.ctrlKey) {
		switch(evt.keyCode) {
			case 79:	//O
			// onClickChangeEnv();
			break;
			case 81:	//Q
			onClickExitMenu();
			break;
		}
		
		if (72 == evt.keyCode || 1056 == MW.getLastKeyCode()) {
			onClickChangeHideMode();
		}
		if (67 == evt.keyCode || 1057 == MW.getLastKeyCode()) {
			onCopy();
		}
		if (86 == evt.keyCode || 1052 == MW.getLastKeyCode()) {
			onPaste();
		}
		if (65 == evt.keyCode || 1060 == MW.getLastKeyCode()) {
			evt.preventDefault();
			app.tab.selectAll();
			return;
		}
		if (88 == evt.keyCode || 1063 == MW.getLastKeyCode()) {
			app.tab.onClickCut();
		}
		if (76 == evt.keyCode || 1044 == MW.getLastKeyCode()) {
			onClickDisplayPath();
		}
		if (84 == evt.keyCode || 1045 == MW.getLastKeyCode()) {
			app.tabPanel.addTabItem( app.tab.currentPath );
		}
		
    }
    // After press Enter in Confirm dialog '||' not work
    if (46 == evt.keyCode && 16777223 == MW.getLastKeyCode()) {
		onDelete();
	}
	
	if (113 == evt.keyCode) {
		app.tab.onClickRename();
	}
	
	app.kbListener.onKeyDown(evt);
	
}

function onClickExitMenu() {
	Qt.quit();
}

function onClickChangeHideMode() {
	var mode = intval(Settings.get('hMode')), text;
	if (1 === mode) {
		mode = 0;
		text = L('Show hidden files Ctrl+H');
	} else {
		mode = 1;
		text = L('Hide hidden files Ctrl+H');
	}
	Settings.set('hMode', mode);
	
	if (app && app.tab) {
		app.tab.setPath(app.tab.currentPath);
		Qt.renameMenuItem(1, 0, text);
	}
}

function onClickNoShowCatalogs() {
	var sName = "noShowDir", mode = intval(Settings.get(sName)), text;
	if (1 === mode) {
		mode = 0;
		text = L("No show catalogs");
	} else {
		mode = 1;
		text = L("Show catalogs");
	}
	Settings.set(sName, mode);
	
	if (app && app.tab) {
		app.tab.setPath(app.tab.currentPath);
		Qt.renameMenuItem(1, 3, text);
	}
}

function onClickChangeAddressMode() {
	var mode = intval(Settings.get('addressLineMode')), text;
	if (1 === mode) {
		mode = 0;
		text = L('Display address line');
	} else {
		mode = 1;
		text = L('Display address as row buttons');
	}
	Settings.set('addressLineMode', mode);
	
	if (app && app.addressPanel) {
		if (1 === mode) {
			app.addressPanel.showTextAddress();
		} else {
			app.addressPanel.showButtonAddress();
		}
		Qt.renameMenuItem(1, 1, text);
	}
}

function onClickDisplayPath(){
	app.addressPanel.showTextAddressShort();
}

function onCopy() {
	app.tab.onClickCopy();
}
function onPaste() {
	app.tab.onClickPaste();
}
function onDelete() {
	try {
		app.tab.onClickRemove();
	} catch(err) {
		alert('main.js onDelete: ' + err);
	}
}

function onClickAbout() {
	alert('Version 3.1.1 pre-release');
}

function onClickCreateFileMenu() {
	app.tab.newFileAction();
}

function onClickCreateFolderMenu() {
	app.tab.newFolderAction();
}

function onClickNewWindowMenu() {
	app.openNewWindow();
}

function onClickSelectEn() {
	onClickChangeLang('en');
}

function onClickSelectRu() {
	onClickChangeLang('ru');
}

function onClickChangeLang(lang) {
	var s;
	try {
		console.log("here may be In itSearch...");
		//Search.init();
	} catch(err) {
		alert(err);
	}
	
	if ('ru' == lang) {
		storage('lang', 'langRu');
		//jaqedLang = langRu;
	} else {
		//jaqedLang = langEn;
		storage('lang', 'langEn');
	}
}

function getScrollLineHeight() {
	return SCROLL_LINE_HEIGHT * VERTICAL_SCROLL_COUNT;
}

function resizetabItemsWidth() {
	var vpW = getViewport().w;
	e('tabItems').style.width = (vpW - e('sidebarWrapper').offsetWidth - getScrollLineHeight()) + 'px';
	// items headers
	//e('tabContentHeaders').style.width = (vpW - e('sidebarWrapper').offsetWidth - getScrollLineHeight()) + 'px';
	e('tabContentHeadersWr').style.width = (vpW - e('sidebarWrapper').offsetWidth - getScrollLineHeight()) + 'px';
	
	e('tabContentHeaders').style.minWidth = (
		e('tabContentHeaderDate').offsetWidth
		+ e('tabContentHeaderType').offsetWidth
		+ e('tabContentHeaderSize').offsetWidth
		+ e('tabContentHeaderFileName').offsetWidth
	) + 'px';
}

function log(s) {
	var c = FS.readfile(App.dir() + '/log.log');
	c += date('H:i:s') + ' ' + s + "\n";
	FS.writefile(App.dir() + '/log.log', c)
}

function mclone(o) {
	return JSON.parse( JSON.stringify(o) );
}

function body(o) {
	return ee(document, 'body')[0];
}


function appSetTitle(s) {
	ee(document, 'title')[0].innerHTML = s;
}

window.addEventListener("load",  main);


