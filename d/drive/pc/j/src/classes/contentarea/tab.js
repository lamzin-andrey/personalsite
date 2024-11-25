function Tab() {
	var o = this;
	this.cName = 'tabContentItem';
	this.username = '';
	this.navbarPanelManager = new NavbarPanel();
	this.addressPanel = new AddressPanel();
	this.listRenderer = new ListRenderer();
	this.sort = window.app.sort;
	this.list = [];
	this.hideList = [];
	this.showList = [];
	this.contentBlock = e('tabItems');
	this.statusBlock = e('statusText');
	this.statusLdrPlacer = e('statusLdrPlacer');
	this.listCount = 0;
	this.oSelectionItems = {};
	this.cutItems = [];
	this.copyPaste = new CopyPaste(this);
	
	/**
	 * @property {Array} selectionItems; Get item id: selectionItems[i].parentNode.id
	*/
}

Tab.prototype.setPath = function(path, fid) {
	var o = this,
		pathInfo = pathinfo(path);
	o.currentPath = path;
	o.list = [];
	o.hideList = [];
	o.showList = [];
	o.oSelectionItems = {};
	o.activeItem = null;
	o.cutItems = [];
	o.listCount = 0;
	o.listComplete = false;
	o.hideListComplete = false;
	o.currentFid = fid;
	o.setStatus(L('Load catalog data') + '. ' + L('Request') + '.', 1);
	
	if (path == "/wcard") {
		throw Error("dbg");
	}
	
	appSetTitle(pathInfo.basename + ' - ' + FileManager.PRODUCT_LABEL);
	
	if (!app.isActive) {
		return;
	}
		
	//TODO здесь внимательно, могут быть всякие штуки впоследствии
	/*if (o.skipRequestList && o.skipRequestHList) {
		o.showList = mclone(o.skipRequestList);
		o.hideList = mclone(o.skipRequestHList);
		o.list = o.showList;
		o.skipRequestList = 0;
		o.skipRequestHList = 0;
		o.hideListComplete = true;
		o.listComplete = true;
		o.setStatus(L('Load catalog data') + '. ' + L('Рендерим') + '.', 1);
		o.listCount = 2;
		o.renderByMode();
		return;
	}*/
	// TODO  m=?
	Rest2._get(o.onFileList, window.br + "/drivelist.json?c=" + fid + "&m=0", o.onFailGetList, o);
	
}

Tab.prototype.onFailGetList = function(status, responseText, info, xhr, readyState) {
	return defaultResponseError(0, responseText, info, xhr);
}

Tab.prototype.onFileList = function(data) {
	var o = this;
	if (!defaultResponseError(data)) {
		showError("tab.js onFileList TODO надо подумать, что тут написать");
		return;
	}
	this.setStatus(L('Load catalog data') + '. ' + L('Start build list') + '.', 1);
	o.list = o.buildList(data);	o.listComplete = true;
	o.setStatus(L('Load catalog data') + '. ' + L('Рендерим') + '.', 1);
	o.listCount = 2;
	o.renderByMode();
	onResize();
}

Tab.prototype.isHidden = function(s) {
	return (S(s).charAt(0) == '.');
}

// TODO ccut
Tab.prototype.redraw = function() {
	this.rebuildList('list');
	this.renderByMode();
}
// TODO cut
Tab.prototype.rebuildList = function(key) {
	var SZ , i, files = [], dirs = [],
		list = this[key];
	
	SZ = sz(list);
	console.log(list);
	for (i = 0; i < SZ; i++) {
		if (list[i].type == L("Catalog")) {
			dirs.push(mclone(list[i]));
		} else {
			files.push(mclone(list[i]));
		}
	} 
	this.sort.apply(files);
	this.sort.apply(dirs);
	SZ = sz(files);
	for (i = 0; i < SZ; i++) {
		dirs.push(files[i]);
	}
	this[key] = dirs;
}


Tab.prototype.buildList = function(data, calcDirSizes) {
	var lines = data.ls, i, buf, SZ = sz(lines), dirs = [], files = [],
		item, t, o = this, hFiles = [], hDirs = [];
	for (i = 0; i < SZ; i++) {
		item = o.createItem(lines[i]);
		if (item) {
			if (item.name == '.' || item.name == '..') {
				continue;
			}
			if (item.type != L('Catalog')) {
				files.push(item);
				if (o.isHidden(item.name)) {
					hFiles.push(item);
				}
			} else {
				if (calcDirSizes ) {
					item.sz = o.listRenderer.getHumanFilesize(item.rsz, 1, 3, false);
					item.rsz = item.sz;
					t = item.rsz;
					if (item.sz == 'NaN Байт') {
						item.sz = t;
					}
				}
				if (o.isHidden(item.name)) {
					hDirs.push(item);
				}
				dirs.push(item);
			}
		}
	}
	o.sort.apply(files);
	
	// TODO 2 cut Settings
	if (storage("noShowDir") == 1) {
		return files;
	}
	
	o.sort.apply(dirs);
	SZ = sz(files);
	for (i = 0; i < SZ; i++) {
		dirs.push(files[i]);
	}
	
	o.sort.apply(hDirs);
	SZ = sz(hFiles);
	for (i = 0; i < SZ; i++) {
		hDirs.push(hFiles[i]);
	}
	this.hideList = hDirs;
	
	return dirs;
}

Tab.prototype.unpackHexSz = function(n, skipHuman) {
	var a = String(n).split('g'), i, r, SZ;
	SZ = sz(a);
	for (i = 0; i < SZ - 1; i++) {
		a[i] = parseInt(a[i], 16);
	}
	
	if (!skipHuman) {
		if (SZ > 2) {
			r = TextFormatU.money(S(a[0])) + ',' + a[1] + ' ' + a[2];
		} else {
			r = TextFormatU.money(S(a[0])) + ' ' + a[1];
		}
	} else {
		r = parseFloat(S(a[0]) + '.0');
		if (SZ > 2) {
			r = parseFloat(S(a[0]) + '.' + (a[1] ? a[1] : '0'));
			switch(a[2]) {
				case "Kb":
					r *= 1024;
					break;
				case "Mb":
					r *= 1024*1024;
					break;
				case "Gb":
					r *= 1024*1024*1024;
					break;
			}
			r = round(r);
		}
	}
	
	return r;
}
	
Tab.prototype.getClickedItem = function(id) {
	var i, SZ;
	id = id.replace('f', '');
	return this.list[id];
}

Tab.prototype.renderByMode = function() {
	var o = this, list = o.list, i, SZ = sz(list), item, s, block;
	o.oSelectionItems = {};
	
	if (o.listCount != 2) {
		return;
	}
	
	if (1 === intval(storage('hMode'))) {
		o.showList = JSON.parse(JSON.stringify(o.list));
		o.list = JSON.parse(JSON.stringify(o.hideList));
		list = o.list;
		SZ = sz(list);
	}
	this.listRenderer.run(SZ, o, list, 0);
}
Tab.prototype.onClickItem = function(evt) {
	var trg = ctrg(evt),
		ct = new Date().getTime(),
		item,
		path,
		cmd,
		slot,
		i, targetModel;
	
	this.setSelection(evt);
	
	if (ct - this.clicktime > 50 && ct - this.clicktime < 400 && trg.id == this.currentTargetId) {
		this.openAction(trg.id, attr(trg.firstChild, "data-d"));
	} else {
		targetModel = this.getClickedItem(trg.id);
		if (targetModel) {
			this.setStatus('«' + targetModel.name + '» (' + targetModel.sz + ') ' + targetModel.type);
		}
	}
	this.clicktime = ct;
	this.currentTargetId = trg.id;
}

Tab.prototype.selectAll = function() {
	var i, SZ = sz(this.list), itemId, tabContentItem;
	this.oSelectionItems = {};
	for (i = 0; i < SZ; i++) {
		itemId = 'f' + i;
		this.oSelectionItems[itemId] = 1;
		tabContentItem = cs(itemId, this.cName)[0];
		if (tabContentItem) {
			addClass(tabContentItem, 'active');
		}
	}
}
Tab.prototype.createItem = function(s) {
	var item = {
			name: '',
			sz:'',
			rsz:0,
			type:'',
			o:'',
			g:'',
			mt:'',
			nSubdirs: 0,
			i:'',
			src: s,
			id: 0
		},
		i, buf, a, typeData, o = this;
	
	item.name = s.name;
	
	item.rsz = o.unpackHexSz(s.s, 1);
	item.id = s.i;
	if (s.type == 'c') {
		item.type = L('Catalog');
		item.i = window.root + '/i/folder32.png';
		item.cmId = 'cmCatalog';
	} else {
		typeData = Types.get(this.currentPath + '/' + item.name);
		item.type = typeData.t;
		item.i = typeData.i;
		item.cmId = typeData.c;
	}
	
	item.sz = o.unpackHexSz(s.s);
	
	item.o = USER;
	item.g = USER;
	
	
	//item.mt = a[5] + ' ' + a[6].split('.')[0];
	item.mt = SqzDatetime.desqzDatetime(s.ct, 1);
	ut = SqzDatetime.desqzDatetime(s.ut, 1);
	item.mt = item.mt < ut ? ut : item.mt;
	item.nSubdirs = s.qc;
	
	return item;
}

Tab.prototype.onContextMenu = function(targetId, event) {
	var activeItem = e(targetId);
	if (activeItem) {
		activeItem = cs(activeItem, this.cName)[0];
		if (activeItem) {
			this.setSelection({currentTarget:activeItem.parentNode, isRight:true}, false);
		}
	}
}

Tab.prototype.setUser = function(s) {
	this.username = s;
}

Tab.prototype.getUser = function(s) {
	return this.username;
}

// TODO почистить
Tab.prototype.openAction = function(id, fid) {
	var item, path,	pathInfo;
	
	item = this.getClickedItem(id);
	path = this.currentPath + '/' + item.name;
	pathInfo = pathinfo(path)
	if (item.type == L('Catalog')) {
		app.setActivePath(path, [''], fid);
	}
}

Tab.prototype.onClickOpenWebNewTab = function() {
	var item, path;
	item = this.getClickedItem(currentCmTargetId);
	path = this.currentPath + '/' + item.name;
	app.tabPanel.addTabItem(path, TabPanelItem.TYPE_HTML);
	app.tab.setPath(path);
}

Tab.prototype.onClickOpen = function() {
	var i = window.currentCmTargetId;
	this.openAction(i, attr(e(i).firstChild, "data-d"));
}
Tab.prototype.onClickOpenNewTab = function() {
	var i = window.currentCmTargetId, 
		n = this.toI(i),
		cid,
		st;
	if (n) {
		st = mclone(app.addressPanel.buttonAddress.stack);
		cid = attr(e(i).firstChild, "data-d");
		app.tabPanel.addTabItem(this.currentPath + '/' + this.list[n].name, 1, cid, st);
		app.setActivePath(this.currentPath + '/' + this.list[n].name, ["tabpanel"], cid);
	}
}

Tab.prototype.getFid = function(n) {
	var i, o = this;
	n = !isU(n) ? n : window.currentCmTargetId;
	if (isU(n)) {
		for (n in o.oSelectionItems) {
			break;
		}
	}
	return intval(this.list[this.toI(n)].id);
}

Tab.prototype.onClickDownload = function() {
	var o = this, n = o.getFid(),
		url = `${br}/drivegetlink.json?i=${n}`;
	Rest2._get(o.onSuccessGetDLink, url, o.onFailGetDLink, o);
}

Tab.prototype.onSuccessGetDLink = function(data) {
	if (this.onFailGetDLink(data)) {
		window.location.href = data.link;
	}
}

Tab.prototype.onFailGetDLink = function(data, responseText, info, xhr) {
	return defaultResponseError(data, responseText, info, xhr);
},



Tab.prototype.onClickNewFolder = function() {
	this.newFolderAction();
}


Tab.prototype.newFolderAction = function() {
	this.newItemAction(L("New catalog"), L("Enter catalog name"));
}
// TODO only folders
Tab.prototype.newItemAction = function(newName, label) {
	var slot, cmd, o = this, i, SZ;
	newName = prompt(label, newName);
	if (newName) {
		SZ = sz(o.list);
		for (i = 0; i < SZ; i++) {
			if (o.list[i].name == newName) {
				alert(L("File or folder already exists"));
				return;
			}
		}
		shortName = newName;
		
		/*jexec(slot, function(){
			o.onCreateNewItem(shortName, isDir);
		}, DevNull, function(err){alert(err)});*/
		// TODO request
		Rest2._post({name: newName, c: o.currentFid}, (data) => {
			o.onCreateNewItem(newName, 1, data);
		}, `${br}/driveaddcatalog.json`, o.onFailCreateNewItem, o);
	}
}

Tab.prototype.getNewName = function(newName) {
	var n = 0, next = this.currentPath + '/' + newName;
	
	while (FS.fileExists(next)) {
		n++;
		next = this.currentPath + '/' + newName + " (" + n + ')';
	}
	
	return next;
}


Tab.prototype.onClickCut = function() {
	this.copyPaste.cutAction(currentCmTargetId);
}
Tab.prototype.onClickPaste = function() {
	this.copyPaste.pasteAction();
}

Tab.prototype.onClickProps = function() {
	this.showPropsDlg(0);	
}
Tab.prototype.onClickSharing = function() {
	this.showPropsDlg(1);
}
Tab.prototype.showPropsDlg = function(bm) {
	var k, o;
	fmgr.dlgProp ? (delete fmgr.dlgProp) : 0;
	k = fmgr.dlgProp = new PropsDlg();
	o = this.list[this.toI(currentCmTargetId)];
	k = dlgMgr.create(k.h(o.src, o.i, o.id), k);
	FPC.init(bm);
	AddFileUser.init();
	if (bm == 1) {
		fmgr.dlgProp.showLdrScr();
	} else {
		fmgr.dlgProp.showShared();
	}
	dlgMgr.center(k);
	fmgr.kbListener.activeArea = KBListener.AREA_PROPS_DLG;
}


Tab.prototype.onClickUpload = function(evt) {
	upload.onSelectFile(evt);
}

Tab.prototype.onClickRename = function() {
	var 
		currentCmTargetId,
		o = this,
		idx,
		srcName,
		pathInfo,
		shortName,
		newName,
		cmId,
		item;

	if (!currentCmTargetId) {
		currentCmTargetId = o.activeItem.parentNode.id;
	}
	if (!currentCmTargetId) {
		var firstId = firstKey(o.oSelectionItems);
		if (firstId) {
			currentCmTargetId = firstId;
		}
	}
	
	if (!currentCmTargetId) {
		currentCmTargetId = window.currentCmTargetId;
	}
	if (!currentCmTargetId) {
		return;
	}
	idx = currentCmTargetId.replace('f', '');
	srcName = o.currentPath + '/' + o.list[idx].name;
	pathInfo = pathinfo(srcName);
	shortName = pathInfo.basename;
	
	
	setTimeout(() => {
		newName = prompt(L("Enter new name"), shortName)
		if (newName) {
			Rest2._post({
					i: o.getFid(idx),
					s: newName,
					c: o.currentFid,
					t: (o.list[idx].src.type == "c" ? 'c' : 'f')
				},
				DevNull, `${br}/drivern.json`, defaultResponseError, o
			);
			
			cmId = attr(currentCmTargetId, 'data-cmid');
			if (cmId) {
				item = o.list[idx];
				item.name = newName
				item.src.name = newName
				o.listRenderer.updateItem(idx, item);
			}
		}
	}, 10);
		
	
}


Tab.prototype.onClickAddBookmark = function() {
	var idx, srcName, pathInfo, shortName, o = this,
		trg, fid,
		stack = mclone(app.addressPanel.buttonAddress.stack);
	trg = e(window.currentCmTargetId);
	fid = attr(trg.firstChild, "data-d");
	if (!sz(stack)) {
		stack.push(0);
	}
	idx = currentCmTargetId.replace('f', '');
	srcName = o.currentPath + '/' + o.list[idx].name;
	pathInfo = pathinfo(srcName);
	shortName = pathInfo.basename;
	app.bookmarksManager.addNewBm(srcName, shortName, fid, stack);
}



Tab.prototype.onClickRemove = function() {
	var id,
		path,
		msg,
		sp = " ",
		o = this,
		ival,
		path,
		i, SZ, keys, parentNode, deletedKeys = [],
		isMult = 0;
	i = W.currentCmTargetId;
	if (count(o.oSelectionItems) > 1) {
		msg = L("Are you sure you want to permanently delete files") + "?";
		isMult = 1;
	} else if (count(o.oSelectionItems) == 1) {
		id = o.getFid();
		if (isU(i)) {
			for (i in o.oSelectionItems) {
				break;
			}
		}
		msg = L("Are you sure you want to permanently delete file") + sp + '"' + o.list[o.toI(i)].name + sp + "\"?";
	} else {
		return;
	}
 		
	if (confirm(msg)) {
		keys = array_keys(o.oSelectionItems);
		if (isMult) {
			o.removeItems(keys);
		} else {
			o.removeOneItem(id, e(i), i);
		}
	}
}
Tab.prototype.removeOneItem = function(i, node, idx) {
	var o = this, t, n = o.toI(idx);
	o.removingNodes = o.removingNodes ? o.removingNodes : [];
	o.removingNodes.push(mclone(o.list[o.toI(idx)].src));
	t = o.list[n].src.type;
	o.list.splice(n, 1);
	rm(node);
	o.failRmMsg = L("Error remove file");
	if (t != 'c') {
		Rest2._post({i}, o.onFinishRemove, `${br}/driverm.json`, o.onErrorRemove, o);
	} else {
		Rest2._get(o.onListForRemove, `${br}/driveremid.json?i=${i}`, o.onErrorRemove, o);
	}
}
Tab.prototype.onListForRemove = function(data) {
	var r = defaultResponseError(data), o = this;
	if (r) {
		Rest2._post({list:data.list.join(',')}, o.onFinishRemove, `${br}/drivermrf.json`, o.onErrorRemove, o);
	}
}
Tab.prototype.onFinishRemove = function(data) {
	var o = this;
	o.removingNodes = [];
}
Tab.prototype.onErrorRemove = function(data) {
	var o = this, i, SZ, a = o.removingNodes;
	a = a ? a : [];
	SZ = sz(a);
	for (i = 0; i < SZ; i++) {
		o.list.push(o.createItem(a[i]));
		o.redraw();
	}
	showError(o.failRmMsg);
	o.removingNodes = [];
}



Tab.prototype.removeItems = function(ls) {
	var o = this, i, SZ = sz(ls), b = {}, list2 = [], c = [];
	o.removingNodes = o.removingNodes ? o.removingNodes : [];
	for (i = 0; i < SZ; i++) {
		o.removingNodes.push(mclone(o.list[o.toI(ls[i])].src));
		//o.list.splice(o.toI(ls[i]), 1);
		b[o.toI(ls[i])] = 1;
	}
	SZ = sz(o.list);
	for (i = 0; i < SZ; i++) {
		if (b[i] != 1) {
			list2.push(o.list[i]);
		} else {
			rm("f" + i);
			c.push((o.list[i].src.type == "c" ? "f" : "fi") + o.getFid(i));
		}
	}
	o.list = list2;
	o.failRmMsg = L("Error remove files");
	Rest2._post({t:o.currentFid, ls:c.join(',')}, o.onFinishRemove, `${br}/drivermls.json`, o.onErrorRemove, o);
}

Tab.prototype.setStatus = function(s, showLoader) {
	var ldr = '';
	if (showLoader) {
		ldr = '<img src="' + root + '/i/ld/s.gif">';
		if (this.lastLoader != ldr) {
			this.statusLdrPlacer.innerHTML = ldr;
			this.lastLoader == ldr;
		}
	} else {
		this.statusLdrPlacer.innerHTML = ldr;
		this.lastLoader == ldr;
	}
	
	this.statusBlock.innerHTML = s;
}

Tab.prototype.tpl = function() {
	return '<div class="tabContentItem {active}" title="{name} id=f{id}" data-d="{did}">\
						<div class="tabContentItemNameMain fl">\
							<div class="tabContentItemIcon fl">\
								<img class="imgTabContentItemIcon" src="{img}" onerror="app.tab.onErrLoadPreview({id})">\
							</div>\
							<div class="tabContentItemName fl">{name}</div>\
							<div class="cf"></div>\
						</div>\
						\
						<div class="tabContentItemSize fl">\
							<div class="tabContentItemName">{sz}</div>\
						</div>\
						\
						<div class="tabContentItemType fl" title="{type}">\
							<div class="tabContentItemName" >{type}</div>\
						</div>\
						\
						<div class="tabContentItemDate fl">\
							<div class="tabContentItemName">{mt}</div>\
						</div>\
						<div class="cf"></div>\
					</div> <!-- /tabContentItem -->\
					<div class="cf"></div>';
}

Tab.prototype.setSelection = function(evt, needClearSelection) {
	needClearSelection = String(needClearSelection) == 'undefined' || needClearSelection === true  ? true : false;
	var i, trg = ctrg(evt), cname = this.cName, lastId, nextId, obj, buf;
	
	if (!evt.ctrlKey && !evt.shiftKey) {
		this.activeItem = cs(trg, cname)[0];
		if (!needClearSelection) {
			needClearSelection = false;
			if (this.oSelectionItems[trg.id] && !evt.isRight) {
				needClearSelection = true;
			}
		}
		if (needClearSelection) {
			this.clearSelections();
		}
		
		
		this.oSelectionItems[trg.id] = 1;
		addClass(this.activeItem, 'active');
	} else if (evt.ctrlKey) {
		this.activeItem = cs(trg, cname)[0];
		if (hasClass(this.activeItem, 'active')) {
			removeClass(this.activeItem, 'active');
			
			this.oSelectionItems[trg.id] = 0;
			delete this.oSelectionItems[trg.id];
			this.activeItem = null;
		} else {
			addClass(this.activeItem, 'active');
			this.oSelectionItems[trg.id] = 1;
		}
		
	} else if (evt.shiftKey) {
		lastId = -1;
		if (this.activeItem) {
			lastId = this.toI(this.activeItem.parentNode.id);
			
			for (i in this.oSelectionItems) {
				buf = parseInt(this.toI(i));
				if (buf < parseInt(lastId)) {
					lastId = buf;
				}
			}
		}
		this.activeItem = cs(trg, cname)[0];
		nextId = -1;
		if (this.activeItem) {
			nextId = this.toI(this.activeItem.parentNode.id);
		}
		
		
		if (nextId <= lastId) {
			buf = lastId;
			lastId = nextId;
			nextId = buf;
		
			for (i in this.oSelectionItems) {
				buf = parseInt(this.toI(i));
				if (buf > parseInt(nextId)) {
					nextId = buf;
				}
			}
		}
		
		this.clearSelections();
		
		if (lastId == -1 || nextId == -1) {
			
			this.oSelectionItems[trg.id] = 1;
			addClass(this.activeItem, 'active');
		} else if (lastId < nextId) {
			for (i = lastId; i <= nextId; i++) {
				this.oSelectionItems['f' + i] = 1;
				obj = e('f' + i);
				if (obj) {
					obj = cs(obj, cname)[0];
					if (obj) {
						
						addClass(obj, 'active');
					}
				}
			}
		}
	}
	this.normalizeSelectionItems();
}
Tab.prototype.toI = function(s) {
	return String(s).replace(/\D/mig, '');
}

Tab.prototype.normalizeSelectionItems = function() {
	
}

Tab.prototype.onKeyDown = function(evt) {
	var pathInfo, idData;
	if (evt.keyCode == 40) {
		this.onPushArrowDown(evt);
		return;
	}
	if (evt.keyCode == 38) {
		if (evt.altKey) {
			pathInfo = pathinfo(this.currentPath);
			if (pathInfo.dirname) {
				app.setActivePath(pathInfo.dirname, ['']);
			} else {
				app.setActivePath('/', ['']);
			}
			return;
		} else {
			this.onPushArrowUp(evt);
			return;
		}
		
	}
	if (evt.keyCode == 13) {
		idData = this.getActiveItemId();
		this.openAction(idData.domId, idData.fid);
	}
	console.log(evt.keyCode);
	if (!this.isFilterBoxShown()
		&& evt.keyCode != 27 
		&& evt.keyCode != 13
		&& evt.keyCode != 18
		&& evt.keyCode != 16
		&& evt.keyCode != 46
		&& evt.keyCode != 9
		&& evt.keyCode != 8
		&& !evt.ctrlKey
		&& !evt.altKey
		&& app.isActive
	) {
		this.showFilterBox(MW.getLastKeyChar());
		this.processFilterBoxInput();
	} 
}
Tab.prototype.onPushArrowDown = function(evt) {
	//evt.preventDefault();
	var id, o = this;
	id = this.getActiveItemId().domId;
	if (!id) {
		id = 'f0';
		this.setSelection({
			currentTarget: e(id),
			shiftKey: evt.shiftKey
		}, !evt.shiftKey);
	
		this.scrollToItem(id);
		return true;
	}
	id = this.getNextId(id);
	if (!id) {
		return true;
	}
	//this.scrollToItem(id, true);
	if (e(id)) {
		this.setSelection({
			currentTarget: e(id),
			shiftKey: evt.shiftKey
		}, !evt.shiftKey);
	}
	
	o.contentBlock.scrollBy(0, ListRenderer.ONE_ITEM_HEIGHT);
	return true;
}

Tab.prototype.onPushArrowUp = function(evt) {
	var id, o = this;
	id = this.getActiveItemId().domId;
	if (!id) {
		id = 'f0';
		this.setSelection({
			currentTarget: e(id),
			shiftKey: evt.shiftKey
		}, !evt.shiftKey);
		// this.scrollToItem(id);
		return;
	}
	id = this.getPrevId(id);
	if (!id) {
		return;
	}
	if (!evt.shiftKey) {
		//this.scrollToItem(id);
	}
	if (e(id)) {
		this.setSelection({
			currentTarget: e(id),
			shiftKey: evt.shiftKey
		}, !evt.shiftKey);
	}
	o.contentBlock.scrollBy(0, -1*ListRenderer.ONE_ITEM_HEIGHT);
}
Tab.prototype.getActiveItemId = function() {
	var r = {};
	if(!this.activeItem) {
		r.domId = '';
		r.fid = '';
		return r;
	}
	console.log("Tab.prototype.getActiveItemId:", this.activeItem);
	r.domId = this.toI(this.activeItem.parentNode.id);
	r.fid = intval(attr(this.activeItem, "data-d"));
	return r;
}

Tab.prototype.getNextId = function(id) {
	id = this.toI(id);
	id++;
	if (id < sz(this.list)) {
		return ('f' + id);
	}
	
	return '';
}

Tab.prototype.getPrevId = function(id) {
	id = this.toI(id);
	id--;
	
	if (id < 0) {
		id = 0;
	}
	
	return ('f' + id);
}

Tab.prototype.scrollToItem = function(id, toBtm) {
	id = this.toI(id);
	this.contentBlock.scrollTo(0, id * ListRenderer.ONE_ITEM_HEIGHT);
}

Tab.prototype.isFilterBoxShown = function() {
	if (e('hFilterBox')) {
		return true;
	}
	return false;
}

Tab.prototype.showFilterBox = function(ch) {
	var o = this, filterBox, input;
	filterBox = appendChild(body(), 'div', o.getFilterBoxHtml(ch), {"id": "hFilterBox", "style" : o.getFilterBoxStyle()});
	input = e('hFilterBoxInput');
	input.onkeydown = function(evt) {
		if (evt.keyCode in In(27, 40, 38)) {
			evt.preventDefault();
			rm("hFilterBoxInput");
			rm("hFilterBox");
			return;
		}
		app.filterBoxDeadTime = time() + 5;
		setTimeout(function(){
			o.processFilterBoxInput();
		}, 100);
	}
	input.focus();
	app.filterBoxDeadTime = time() + 5;
	this.filterBoxInterval = setInterval(function(){
		if (time() > app.filterBoxDeadTime) {
			rm('hFilterBoxInput');
			rm('hFilterBox');
			clearInterval(o.filterBoxInterval);
		}
	}, 1000);
}

Tab.prototype.getFilterBoxHtml = function(ch) {
	var s = '<input style="height:32px; border: 2px solid #8ba8df; font-size:13px; border-radius:2px;" value="' + ch + '" id="hFilterBoxInput">';
	return s;
}

Tab.prototype.getFilterBoxStyle = function() {
	var s = 'position:fixed; right:2px; bottom:4px;';
	return s;
}

Tab.prototype.processFilterBoxInput = function() {
	var input = input = e('hFilterBoxInput'), s, i, SZ = sz(this.list), item, id;
	if (!input) {
		return;
	}
	s = input.value;
	if (!s) {
		return;
	}
	s = input.value;
	for (i = 0; i < SZ; i++) {
		item = this.list[i];
		if (item.name.indexOf(s) == 0) {
			id = 'f' + i;
			this.scrollToItem(id);
			if (e(id)) {
				this.setSelection({
					currentTarget: e(id)
				}, true);
			}
			break;
		}
	}
}

Tab.prototype.setTabItem = function(tabItem) {
	this.tabItem = tabItem;
}


Tab.prototype.onFailCreateNewItem = function(data, responseText, info, xhr) {
	return defaultResponseError(data, responseText, info, xhr);
}
Tab.prototype.onCreateNewItem = function(name, isDir, data) {
	var item, typeData, idx = 0, o = this;
	if (!o.onFailCreateNewItem(data)) {
		return;
	}
	// item = mclone(this.list[0]);
	item = item ? item : {};

	if (isDir) {
		item.type = L('Catalog');
		item.i = root + '/i/folder32.png';
		item.cmId = 'cmCatalog';
	}
	
	item.g = 0;
	item.mt = date('Y-m-d H:i:s');
	item.nSubdirs = 0;
	item.name = name;
	item.o = window.USER;
	item.rsz = 0;
	item.sz = '0';
	item.src = data;
	item.id = data.i;
	
	o.list.push(item);
	o.redraw();
	
	/*o.contentBlock.innerHTML = '';
	if (sz(o.list) > 0) {
		if (window.currentCmTargetId) {
			idx = o.toI(window.currentCmTargetId);
			idx--;
			idx = idx > 0 ? idx : 0;
		}
		o.list.splice(idx, 0, item);
	} else {
		o.list.push(item);
	}*/
	
	/*o.createdItemName = name;
	o.listRenderer.run(sz(o.list), o, o.list, intval(o.getFirstItemId()));
	o.createdItemName = name;*/
	
}



Tab.prototype.selectItemByIdx = function(createdItemFound) {
	var newFoundedActiveItem;
	this.createdItemName = '';
	this.scrollToItem('f' + createdItemFound);
	window.currentCmTargetId = 'f' + createdItemFound;
	newFoundedActiveItem = cs('f' + createdItemFound, this.cName)[0];
	if (newFoundedActiveItem) {
		this.activeItem = newFoundedActiveItem;
		this.setSelection({currentTarget:this.activeItem.parentNode}, false);
	}
}

Tab.prototype.getScrollY = function() {
	// return this.contentBlock.scrollTop;
	return intval(this.toI(this.getFirstItemId()));
}
Tab.prototype.getFirstItem = function() {
	var o = cs(this.contentBlock, this.cName)[0];
	if (o) {
		return o.parentNode;
	}
}
Tab.prototype.getFirstItemId = function() {
	var o = this.getFirstItem();
	if (o) {
		return o.id;
	}
	return '';
}

Tab.prototype.getLastItem = function() {
	var ls = cs(this.contentBlock, this.cName),
		o = ls[sz(ls) - 1];
	if (o) {
		return o.parentNode;
	}
}
Tab.prototype.getLastItemId = function() {
	var o = this.getLastItem();
	if (o) {
		return o.id;
	}
	return '';
}

Tab.prototype.setScrollY = function(y) {
	// this.contentBlock.scrollTop = y;
	
	this.scrollToItem(y);
	if (e('f' + y)) {
		this.selectItemByIdx(y);
	}
}


Tab.prototype.clearSelections = function() {
	var i, buf, cname = this.cName;
	for (i in this.oSelectionItems) {
		buf = cs(i, cname);
		if (buf) {
			buf = buf[0];
			removeClass(buf, 'active');
		}
	}
	this.oSelectionItems = {};
}

Tab.prototype.onErrLoadPreview = function(i) {
	
	var s, cI;
		s = root + "/i/mi/unknown32.png";
		cI = this.listRenderer.getCurrentIcon(i);
		if (cI != s) {
			this.listRenderer.setCurrentIcon(i, s);
		}
		
	
}

