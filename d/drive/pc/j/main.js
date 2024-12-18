window.SCROLL_LINE_HEIGHT = 18;
window.VERTICAL_SCROLL_COUNT = 1;
function main() {
	window.AppStartTime = time();
	
	var lang = storage("lang");
	if (lang != 'langRu' && lang != 'langEn') {
		lang = 'ru';
	}
	lang = strtolower(lang.replace("lang", ""));
	
	if (lang == 'ru' || lang == 'en') {
		onClickChangeLang(lang);
	}
	
	window.app = new FileManager();
	window.app.init();
	window.fmgr = app;
	
	if (lang == 'ru' || lang == 'en') {
		onClickChangeLang(lang);
	}
	
	initApp();
	
	
	window.onresize = onResize;
	window.onkeydown = onKeyUp;
	onResize();
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
			break;
			case 81:	//Q
			onClickExitMenu();
			break;
		}
		
		if (72 == evt.keyCode) {
			onClickChangeHideMode();
		}
		if (86 == evt.keyCode) {
			onPaste();
		}
		if (65 == evt.keyCode) {
			evt.preventDefault();
			app.tab.selectAll();
			return;
		}
		if (88 == evt.keyCode) {
			app.tab.onClickCut();
		}
		
		if (evt.keyCode in In(84) || evt.code == 'KeyT') {
			evt.preventDefault();
			evt.stopImmediatePropagation();
			app.tabPanel.addTabItem(app.tab.currentPath, 1, app.tab.currentFid, mclone(app.addressPanel.buttonAddress.stack));
			return;
		}
		
    }
    // After press Enter in Confirm dialog '||' not work
    if (46 == evt.keyCode) {
		onDelete();
	}
	
	if (113 == evt.keyCode) {
		app.tab.onClickRename();
	}
	
	app.kbListener.onKeyDown(evt);
	
}

function fixUp(evt) {
	evt.preventDefault();
	evt.stopImmediatePropagation();
	return false;
}


function onClickChangeHideMode() {
	var mode = intval(storage('hMode')), text;
	if (1 === mode) {
		mode = 0;
		text = L('Show hidden files Ctrl+H');
	} else {
		mode = 1;
		text = L('Hide hidden files Ctrl+H');
	}
	storage('hMode', mode);
	
	if (app && app.tab) {
		app.tab.redraw();
	}
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
	var s, cn = "tabContentHeaderName";
	if ('ru' == lang) {
		jaqedLang = langRu;
		storage('lang', 'langRu');
	} else {
		jaqedLang = langEn;
		storage('lang', 'langEn');
	}
	
	if (e("ctxTplBlock") && fmgr) {
		rm("ctxTplBlock");
		fmgr.addContextMenuHtml();
		if (fmgr.bookmarksManager) {
			fmgr.bookmarksManager.setTitle(L("Bookmarks"));
		}
		
		v(cs("tabContentHeaderFileName", cn)[0], L("ColNameH"));
		v(cs("tabContentHeaderSize", cn)[0], L("ColSizeH"));
		v(cs("tabContentHeaderType", cn)[0], L("ColTypeH"));
		v(cs("tabContentHeaderDate", cn)[0], L("ColDateH"));
		
		v("hUAName", L("hUAName"));
		v("hPPName", L("hPPName"));
		v("hAAName", L("hAAName"));
		v("hFootAA", L("hAAName"));
		v("hFootUA", L("hUAName"));
		v("hFootPP", L("hPPName"));
		v("hFootHelp", L("hFootHelp"));
		setLocale();
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

function log(s) {}

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


