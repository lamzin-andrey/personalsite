class Bookmarks extends AbstractList{
	constructor() {
		super();
		this.username = '';
		this.itemIdPrefix = 'bm';
		this.list = [];
		this.is_run = false;
	}

	setTitle(s) {
		v(this.titleBlock, s);
	}

	setUser(s) {
		this.username = s;
	}

	isRun() {
		return this.is_run;
	}
	
	run() {
		// console.log('Bookmarks run');
		let locale, title, user = this.username;
		if (!user && window.USER) {
			user = window.USER;
		}
		
		if (!user) {
			return;
		}
		this.init('bookmarksBlock', L('Bookmarks'));
		
		locale = this.getLocale(user);
		title = L('Bookmarks');
		this.createList(locale, user);
		try {
			this.render();
			this.is_run = true;
		} catch (err) {
			alert(err);
		}
	}

	onClick(event) {
		let j, i, SZ, trg = ctrg(event),
			o = this,
			n = str_replace(o.itemIdPrefix, '', trg.id);
		SZ = sz(o.list[n].stack);
		j = sz(o.list[n].stack) - 1;
		if (o.list[n].fid == o.list[n].stack[j]) {
			j--;
		}
		for (i = 0; i < SZ; i++) {
			if (o.list[n].fid == o.list[n].stack[i]) {
				j = i - 1;
				j = j < 0 ? 0 : j;
				break;
			}
		}
		app.addressPanel.buttonAddress.currentId = o.list[n].stack[j];
		app.addressPanel.buttonAddress.stack = mclone(o.list[n].stack);
		app.setActivePath(o.list[n].path, ["bookmarksManager"], o.list[n].fid);
	}


	createList(locale, user) {
		let i, SZ = 0, st, fid, userBookmarks = [];
		this.list = [];
		this.addItem(user, '', locale, '', '', 'cmBmSysMenu');
		
		userBookmarks = this.readUserBookmarks();
		// alert(JSON.stringify(userBookmarks));
		if (userBookmarks) {
			SZ = sz(userBookmarks);
		}
		for (i = 0; i < SZ; i++) {
			fid = userBookmarks[i].fid;
			st = mclone(userBookmarks[i].stack);
			this.addItem(user, userBookmarks[i].path.trim(), locale, userBookmarks[i].displayName, 'cmBmMenu', 0, fid, st);
		}
		
	}
	

	addItem(user, name, locale, displayName, userCmId, sysCmId, fid, stack) {
		let item = {
				displayName : '',
				icon: root + '/i/folder32.png',
				path: '',
				stack: []
			}, 
			srcName = name;
		if (!name) {
			item.displayName = user;
			item.icon = root + '/i/usb32.png';
			item.path = user;
			item.stack = [];
			item.fid = 0;
			item.stack.push(0);
			if (sysCmId) {
				item.cmId = sysCmId;
			}
		} else {
			name = this.getLocaleFolderName(name, locale);
			item.displayName = name;
			item.path = '/home/' + name;
			item.fid = fid;
			item.stack = stack;
			
			if (srcName.indexOf(USER) == 0) {
				item.path = srcName.trim();
				if (userCmId) {
					item.cmId = userCmId;
				}
			} else if (sysCmId) {
				item.cmId = sysCmId;
			}
			if (displayName) {
				item.displayName = displayName;
			}
		}
		
		if (item.path != '') {
			if (name) {
				item.icon = this.getIconByName(srcName);
			}
			this.list.push(item);
		}
	}

	getIconByName(name) {
		switch (name) {
			case 'Desktop':
				return root + '/i/desktop32.png';
			case 'Documents':
				return root + '/i/documents32.png';
			case 'Music':
				return root + '/i/music32.png';
			case 'Images':
				return root + '/i/images32.png';
			case 'Videos':
				return root + '/i/videos32.png';
			case 'Downloads':
				return root + '/i/downArrow32.png';
		}
		return root + '/i/folder32.png';
	}

	getLocaleFolderName(name, locale) {
		let pathInfo;
		if ('ru' != locale) {
			return name;
		}
		switch (name) {
			case 'Desktop':
				return 'Рабочий стол';
			case 'Documents':
				return 'Документы';
			case 'Music':
				return 'Музыка';
			case 'Images':
				return 'Изображения';
			case 'Videos':
				return 'Видео';
			case 'Downloads':
				return 'Загрузки';
		}
		pathInfo = pathinfo(name);
		return pathInfo.basename;
		
	}

	/**
	 * Поддерживаем только ru и en
	*/
	getLocale(user) {
		if (storage("lang") == "langRu") {
			return "ru";
		}
		
		return "en";
	}

	addNewBm(path, name, fid, stack) {
		let uaBm;
		uaBm = this.readUserBookmarks();
		uaBm.splice(0, 0, {
			path:path,
			name: name,
			displayName: name,
			fid:fid,
			stack, stack
		});
		this.saveBookmarks(uaBm);
		Rest._post({ls: json_encode(uaBm)}, DevNull, `${br}/wisbmark.json`, DevNull);
	}

	readUserBookmarks() {
		let bookmarksKey,
			uaBmData,
			uaBm, 
			bookmarks = this.getUserBookmarks();
		if (!window.USER) {
			return;
		}
		uaBmData = bookmarks[USER] || {};
		bookmarksKey = uaBmData['k'] || 'def';
		uaBm = uaBmData['L'] || {};
		uaBm = uaBm[bookmarksKey] || [];
		return uaBm;
	}

	writeUserBookmarks(list, newKey) {
		let bookmarksKey,
			uaBmData,
			uaBm,
			bookmarks = this.getUserBookmarks();
		if (!window.USER) {
			return;
		}
		uaBmData = bookmarks[USER] || {};
		bookmarksKey = uaBmData['k'] || 'def';
		uaBm = uaBmData['L'] || {};
		if (newKey) {
			bookmarksKey = newKey;
		}
		uaBm[bookmarksKey] = list;
		uaBmData['L'] = uaBm;
		bookmarks[USER] = uaBmData;
		this.setUserBookmarks(bookmarks);
	}

	saveBookmarks(uaBm) {
		this.writeUserBookmarks(uaBm);
		this.createList(this.getLocale(USER), USER);
		this.render();
		app.setSidebarScrollbar();
	}

	onClickRename() {
		let id = 'bm' + currentCmTargetId, i, data = this.list[currentCmTargetId], SZ, ls, newName;
		if (!e(id) || !data) {
			return;
		}
		ls = this.readUserBookmarks();
		SZ = sz(ls);
		for (i = 0; i < SZ; i++) {
			if (ls[i].displayName == data.displayName && ls[i].path == data.path) {
				newName = prompt(L("Enter new name"), data.displayName);
				if (newName) {
					ls[i].displayName = newName;
					this.saveBookmarks(ls);
					Rest._post({ls: json_encode(ls)}, DevNull, `${br}/wisbmark.json`, DevNull);
				}
				break;
			}
		}
	}

	onClickRemove() {
		let id = 'bm' + currentCmTargetId, i, data = this.list[currentCmTargetId], SZ, ls, newName;
		if (!e(id) || !data) {
			return;
		}
		ls = this.readUserBookmarks();
		SZ = sz(ls);
		for (i = 0; i < SZ; i++) {
			if (ls[i].displayName == data.displayName && ls[i].path == data.path) {
				if (confirm(L("Are you sure remove bookmark") + " " + data.displayName + '?')) {
					ls.splice(i, 1);
					this.saveBookmarks(ls);
					Rest._post({ls: json_encode(ls)}, DevNull, `${br}/wisbmark.json`, DevNull);
				}
				break;
			}
		}
	}

	onClickUp() {
		var id = 'bm' + currentCmTargetId, i, data = this.list[currentCmTargetId], SZ, ls, buf;
		if (!e(id) || !data) {
			return;
		}
		ls = this.readUserBookmarks();
		SZ = sz(ls);
		for (i = 0; i < SZ; i++) {
			if (ls[i].displayName == data.displayName && ls[i].path == data.path) {
				if (i - 1 > -1) {
					buf = ls[i - 1];
					ls[i - 1] = ls[i];
					ls[i] = buf;
					this.saveBookmarks(ls);
					Rest._post({ls: json_encode(ls)}, DevNull, `${br}/wisbmark.json`, DevNull);
				}
				break;
			}
		}
	}

	onClickDown() {
		let id = 'bm' + currentCmTargetId, i, data = this.list[currentCmTargetId], SZ, ls, buf;
		if (!e(id) || !data) {
			return;
		}
		ls = this.readUserBookmarks();
		SZ = sz(ls);
		for (i = 0; i < SZ; i++) {
			if (ls[i].displayName == data.displayName && ls[i].path == data.path) {
				if (i + 1 < SZ) {
					buf = ls[i + 1];
					ls[i + 1] = ls[i];
					ls[i] = buf;
					this.saveBookmarks(ls);
					Rest._post({ls: json_encode(ls)}, DevNull, `${br}/wisbmark.json`, DevNull);
				}
				break;
			}
		}
	}

	onClickOpen() {
		let id = 'bm' + currentCmTargetId, i, data = this.list[currentCmTargetId], SZ, ls, buf;
		if (!e(id) || !data) {
			return;
		}
		app.setActivePath(data.path, ['']);
	}

	onClickOpenInTerm() {
		//TODO remove me!
	}

	// ============= IMPORT / EXPORT Bmarks
	onClickExportBookmarks() {
		let activePath = this.getBookmarksCollectionDirectory(),
			newPath, c, fs = new LandFileInputFS(), activeData;
		c = '{}';
		activeData = storage(activePath);
		if (activeData) {
			c = activeData;
		}
		fs.writefile(date('Y.m.d') + ".json", c);
	}

	async onClickImportBookmarks(evt) {
		let activePath = this.getBookmarksCollectionDirectory(),
			newPath, c, fs;
		fs = new LandFileInputFS();
		c = await fs.readfile(/*newPath*/ctrg(evt));
		storage(activePath, c);
		this.createList(this.getLocale(USER), USER);
		this.render();
		app.setSidebarScrollbar();
	}

	getUserBookmarks() {
		let activePath = this.getBookmarksCollectionDirectory(),
			newPath, c;
		c = storage(activePath);
		c = c ? c : {};
		return c;
	}

	setUserBookmarks(bookmarks) {
		let activePath = this.getBookmarksCollectionDirectory(),
			activeBmFileName;
		storage(activePath, bookmarks);
	}

	getBookmarksCollectionDirectory() {
		return "bmActivePath";
	}
}
	q("devices");
