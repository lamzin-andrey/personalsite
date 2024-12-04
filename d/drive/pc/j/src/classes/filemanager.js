function FileManager() {}

FileManager.prototype.init = function() {
	var o = this;
	o.addContextMenuHtml();
	o.bookmarksManager = new Bookmarks();
	o.tabPanel = new TabPanel();
	o.addressPanel = new AddressPanel();
	o.sort = new Sort();
	window.app.sort = this.sort;
	o.fileHeader = new FileHeader();
	o.isActive = false;
	
	o.setMainMenu(); // TODO -- ?
	window.oPanel = new PanelPatch();
	window.dlgMgr = new DlgMgr(window.oPanel);
	
	
	e('tabItems').onscroll = function(){
		o.actualizeScrollX();
	}
	this.kbListener = new KBListener();
	AppEnv.init([this, this.onGetActualEnv], [this, this.onGetSavedEnv]);
}

FileManager.PRODUCT_LABEL = L("WebUSB - another gigabyte of yours in the cloud");

/**
 * @param {String} path
 * @param {Array} aExcludes - идентификатор(ы) элементов управлени€, дл€ которых не надо примен€ть setPath
*/
FileManager.prototype.setActivePath = function(path, aExcludes, fid) {
	var emitter = In(aExcludes), lastLoc = {}, st, i, SZ, b = [], 
		ap = [], apr = [];
	path = path.replace("/home/", "");
	//throw new Error("dbg")
	
	if (!emitter['addresspanel']) {
		this.tab.currentPath = path;
		this.addressPanel.setPath(path, fid);
	}
	if (!emitter['navbarPanelManager']) {
		this.tab.navbarPanelManager.setPath(path, fid);
	}
	if (!emitter['tab']) {
		this.tab.setPath(path, fid);
	}
	if (!emitter['bookmarksManager']) {
		this.bookmarksManager.setPath(path, fid);
	}
	if (!emitter['tabpanel']) {
		this.tabPanel.setPath(path);
	}
	
	st = fmgr.addressPanel.buttonAddress.stack;
	lastLoc.fid = fmgr.addressPanel.buttonAddress.currentId;
	//lastLoc.path = fmgr.addressPanel.buttonAddress.currentPath;
	ap = fmgr.addressPanel.buttonAddress.currentPath.split("/");
	SZ = sz(st);
	for (i = 0; i < SZ; i++) {
		b.push(st[i]);
		apr.push(ap[i]);
		if (st[i] == lastLoc.fid) {
			break;
		}
	}
	lastLoc.st = b;
	lastLoc.path = apr.join("/");
	
	storage("lastLoc", lastLoc);
}


FileManager.prototype.initActiveTab = function() {
	window.tab = this.tab = this.tabPanel.getActiveTab();
}

/**
 * ¬ызываетс€ когда получены последние данные об окружении AppEnv (USER, HOME и т. п)
*/
FileManager.prototype.onGetActualEnv = function() {
	var lastLoc, path, fid;
	if (!this.bookmarksManager.isRun()) {
		this.bookmarksManager.run();
		this.initActiveTab();
	} else {
		if (this.tab.getUser() != USER && USER != 'root') {
			this.bookmarksManager.setUser(USER);
			this.bookmarksManager.run();
			this.tab.setUser(USER);
			
			lastLoc = storage("lastLoc");
			path = '/home/' + USER;
			if (lastLoc) {
				fmgr.addressPanel.buttonAddress.currentId = lastLoc.st[sz(lastLoc.st) - 1];
				fmgr.addressPanel.buttonAddress.stack = mclone(lastLoc.st);
				path = lastLoc.path;
				fid = lastLoc.fid;
			}
			fmgr.setActivePath(path, [''], fid);
			
		}
	}
}


/**
 * ¬ызываетс€ когда получены предварительно сохраненные данные об окружении AppEnv (USER, HOME и т. п)
*/
FileManager.prototype.onGetSavedEnv = function() {
	this.bookmarksManager.setUser(AppEnv.config.USER);
	this.bookmarksManager.run();
	this.initActiveTab();
}

/**
 * TODO »змен€ет размеры кнопок "строки адреса"
 * TODO »змен€ет размеры табов
*/
FileManager.prototype.onResize = function() {
	this.setTabWidths();
	this.setAddressLineWidth();
	this.setSidebarScrollbar();
	//this.setSidebarScrollbar();
}
FileManager.prototype.setTabWidths = function() {
	if (!e('tabContentHeaderDate')) {
		return;
	}
	e('tabContentHeaderDate').style.width = null;
	e('tabContentHeaderDate').style.minWidth = null;
	
	e('tabContentHeaderFileName').style.width = null;
	e('tabContentHeaderFileName').style.minWidth = null;
	var o = this;
	this.setColWidth(null, null);
	//setTimeout(function(){
		o.corectTabWidth();
	//}, 10);
	
}

FileManager.prototype.setAddressLineWidth = function() {
	if (this.addressPanel) {
		this.addressPanel.resize();
	}
}

FileManager.prototype.corectTabWidth = function() {
	var minDateTabW = 126, correct = 23, s, delta,
		dateColWidth,
		nameColWidth;
	if (
		intval(e('tabContentHeaders').style.minWidth) > intval(e('tabContentHeadersWr').style.width)
	) {
		var x = intval(e('tabContentHeaders').style.minWidth) - intval(e('tabContentHeadersWr').style.width);
		
		// надо выбрать из даты как можно больше, но чтобы оно стало не меньше чем необходимо дл€ полного отображени€ даты.
		// 116, но возможно добавим.
		
		if (intval(e('tabContentHeaderDate').offsetWidth) - x > 116) {
			dateColWidth = (intval(e('tabContentHeaderDate').offsetWidth) - x);
			e('tabContentHeaderDate').style.width = dateColWidth + 'px';
			e('tabContentHeaderDate').style.minWidth = dateColWidth + 'px';
			this.setColWidth(nameColWidth, dateColWidth);
			return;
		}
		s = intval(e('tabContentHeaderDate').offsetWidth) - x;
		delta = minDateTabW - s;
		dateColWidth = minDateTabW;
		e('tabContentHeaderDate').style.width = minDateTabW + 'px';
		e('tabContentHeaderDate').style.minWidth = minDateTabW + 'px';
		x = delta + correct;
		
		// ќстальное выбираем из таба с именем
		x = e('tabContentHeaderFileName').offsetWidth - x;
		nameColWidth = x;
		if (nameColWidth < 100) {
			nameColWidth = 100;
		}
		e('tabContentHeaderFileName').style.width = x + 'px';
		e('tabContentHeaderFileName').style.minWidth = x + 'px';
		this.setColWidth(nameColWidth, dateColWidth);
	} else {
		if (screen.width == 1024) {
			nameColWidth = 355;
			dateColWidth = 126;
			e('tabContentHeaderDate').style.width = dateColWidth + 'px';
			e('tabContentHeaderDate').style.minWidth = dateColWidth + 'px';
		
			e('tabContentHeaderFileName').style.width = nameColWidth + 'px';
			e('tabContentHeaderFileName').style.minWidth = nameColWidth + 'px';
			this.setColWidth(nameColWidth, dateColWidth);
		}
	}
}

FileManager.prototype.setColWidth = function(nameColWidth, dateColWidth) {
	this.setColNameWidth(nameColWidth);
	this.setColDateWidth(dateColWidth);
	this.actualizeScrollX();
}

FileManager.prototype.setColNameWidth = function(nameColWidth) {
	var stl = '.tabContentItemNameMain {\
  width: {n}px!important;\
  max-width: {n}px!important;\
  min-width: {n}px!important;\
}',
	head, tagStyle;
	head = ee(document, 'head')[0];
	rm('styleNameCol');
	if (intval(nameColWidth) == 0) {
		return;
	}
	
	stl = stl.replace('{n}', nameColWidth);
	stl = stl.replace('{n}', nameColWidth);
	stl = stl.replace('{n}', nameColWidth);
   
    
    tagStyle = appendChild(head, 'style', stl, {
		"id": "styleNameCol"
	}, {});
}


FileManager.prototype.setColDateWidth = function(dateColWidth) {
	var stl = '.tabContentItemDate {\
	  width: {n}px!important;\
	  max-width: {n}px!important;\
	}',
	head, tagStyle;
	head = ee(document, 'head')[0];
	rm('styleDateCol');
	if (intval(dateColWidth) == 0) {
		return;
	}
	
	stl = stl.replace('{n}', dateColWidth);
	stl = stl.replace('{n}', dateColWidth);
   
    
    tagStyle = appendChild(head, 'style', stl, {
		"id": "styleDateCol"
	}, {});
}

FileManager.prototype.actualizeScrollX = function() {
	var x = e('tabItems').scrollLeft;
	if (x > 0) {
		x *= -1;
	}
	e('tabContentHeaders').style['margin-left'] = x + 'px';
}

/**
 * Set unconstant main menu items
*/
FileManager.prototype.setMainMenu = function() {
	app.addressPanel.showButtonAddress();
	
}


FileManager.prototype.setSidebarScrollbar = function() {
	var maxHeight = intval(e('sidebarWrapper').style.maxHeight) - 36, // 36 - height navbar
		devicesHeight = 0,
		bookmarksHeight = this.bookmarksManager.getHeight();
	if (devicesHeight + bookmarksHeight + (15 + 7 + 7) > maxHeight) {
		e('sbScroller').style.maxHeight = maxHeight + 'px';
		e('sbScroller').style.overflowY = 'scroll';
		resizetabItemsWidth();
	} else {
		e('sbScroller').style.overflowY = null;
		VERTICAL_SCROLL_COUNT = 1;
	}
}

FileManager.prototype.getCurrentLocale = function() {
	return "ru";
}

FileManager.prototype.addContextMenuHtml = function() {
	this.contextMenuContent = new ContextMenuContent();
	var el, html = this.contextMenuContent.getCatalogMenuHtml() + 
		
		this.contextMenuContent.getDefaultFileCm("cmImage") + 
		this.contextMenuContent.getDefaultFileCm("cmDocument") + 
		this.contextMenuContent.getDefaultFileCm("cmText") + 
		this.contextMenuContent.getArjItemMenuHtml() +
		this.contextMenuContent.getDefaultFileCm("cmWeb") + 
		this.contextMenuContent.getDefaultFileCm("cmVideo") + 
		this.contextMenuContent.getDefaultFileCm("cmAudio") + 
		this.contextMenuContent.getDefaultFileCm("cmDefault") + 
		'\
		<div id="cmEmptyCatalogArea" style="display:none">\
			<div class="contextMenu">\
				<div class="contextMenuItem" onclick="app.tab.onClickNewFolder()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/folder_new16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Create catalog") + '</div>\
					<div class="cf"></div>\
				</div>\
				\
				<div class="contextMenuItem" onclick="app.tab.onClickPaste()">\
					<div class="contextMenuItemIcon">\
						<img src="./i/cm/pst16.png">\
					</div>\
					<div class="contextMenuItemText">' + L("Paste") + '</div>\
					<div class="cf"></div>\
				</div>' +
				this.contextMenuContent.getUploadItemHtml() + 
				'\
			</div>\
		</div>\
		' + this.contextMenuContent.getBookmarkItemMenuHtml() + 
		'\
		<div id="cmUsbMenu" style="display:none">\
			' + this.contextMenuContent.getHtmlTabMenuHtml()  + '\
		</div>';
	//ee(document, 'body')[0].innerHTML += html;
	el = appendChild(ee(document, 'body')[0], 'div', html, {id: "ctxTplBlock"});
	//el.parentNode.parentNode.appendChild(el);
	ee(document, 'body')[0].appendChild(el);
}
