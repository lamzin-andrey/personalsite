window.fileListItemCmenu = {
	dirMenuItems: [
		l('Remove'),
		l('Rename'),
		l('Move')
	],
	fileMediaMenuItems: [
		l('Remove'),
		l('Rename'),
		l('Download'),
		l('Move')
	],
	fileTextMenuItems: [
		l('Remove'),
		l('Rename'),
		l('Download'),
		l('Move')
	],
	fileUnknownMenuItems: [
		l('Remove'),
		l('Rename'),
		l('Download'),
		l('Move')
	],
	/**
	 * @description Build and show folder or file context menu
	*/
	buildAndShowMenu:function(id){
		var isDir = id.indexOf('fi') != 0,
			item,
			menuItems = this.fileUnknownMenuItems;
		this.cmMenuOpenItemId = id.replace('fi', '').replace('f', '');
		this.cmMenuOpenItemType = 'c';
		if (!isDir) {
			this.cmMenuOpenItemType = 'f';
		}
		item = this.findItem();
		if (!item) {
			showError(l('Unable find file info, reload page and try again'));
			return;
		}
		switch (item.type) {
			case 'c':
				menuItems = this.dirMenuItems;
				break;
			case 'm':
				menuItems = this.fileMediaMenuItems;
				break;
			case 't':
				menuItems = this.fileTextMenuItems;
				break;
		}
		
		this.clear();
		this.render(menuItems, item);
		
		showScreen('hCatItemMenu');
	},
	
	findItem:function() {
		var id = this.cmMenuOpenItemId,
			ls = storage('f' + currentDir),
			i, sZ = sz(ls), item;
		for (i = 0; i < sZ; i++) {
			item = ls[i];
			if (item.i == id) {
				return item;
			}
		}
		
		return null;
	},
	
	clear:function() {
		e('hListItemCmItems').innerHTML = '';
	},
	
	render:function(ls, fileListItem) {
		var tpl = '<div class="cim_item">' + 
					'{name}' + 
				'</div>',
			i, sZ = sz(ls),
			menuItem,
			listener,
			self = this;
		e('hCatalogItemTitle').innerHTML = fileListItem.name;
		this.delayForMenuItems = 1;
		for (i = 0; i < sZ; i++) {
			menuItem = appendChild('hListItemCmItems', 'div', ls[i], {'class': 'cim_item gray'});
			switch (ls[i]) {
				case l('Remove'):
					menuItem.onclick = function(evt) {
						if (self.delayForMenuItems) {
							evt.preventDefault();
							return false;
						}
						self.onClickRemove.call(self, evt);
					};
					break;
				case l('Rename'):
					menuItem.onclick = function(evt) {
						if (self.delayForMenuItems) {
							evt.preventDefault();
							return false;
						}
						self.onClickRename.call(self, evt);
					};
					break;
				case l('Move'):
					menuItem.onclick = function(evt) {
						if (self.delayForMenuItems) {
							evt.preventDefault();
							return false;
						}
						self.onClickMove.call(self, evt);
					};
					break;
				case l('Download'):
					menuItem.onclick = function(evt) {
						if (self.delayForMenuItems) {
							evt.preventDefault();
							return false;
						}
						self.onClickDownload.call(self, evt);
					};
					break;
			}
		}// end for
		setTimeout(function() {
			var ls = cs('hCatItemMenu', 'cim_item'), i, sZ = sz(ls);
			for (i = 0; i < sZ; i++) {
				removeClass(ls[i], 'gray');
			}
			self.delayForMenuItems = 0;
		}, 700);
	},
	
	onClickRemove:function(evt) {
		var o = this;
		if (o.cmMenuOpenItemType != 'c') {
			showLoader();
			Rest._post(
				{i: o.cmMenuOpenItemId}, function(data){o.onSuccessRemoveFile(data);},
				br + '/driverm.json',
				function(data, responseText, info, xhr){/*o.onFailRemoveFile*/o.onFailGetRemoveIdList(data, responseText, info, xhr)}
			);
			return;
		}
		showLoader();
		Rest._get(function(data){o.onSuccessGetRemoveIdList(data);},
			br + '/driveremid.json?i=' + o.cmMenuOpenItemId,
			function(data, responseText, info, xhr){o.onFailGetRemoveIdList(data, responseText, info, xhr)});
	},
	onSuccessGetRemoveIdList:function(data){
		if (!this.onFailGetRemoveIdList(data)) {
			hideLoader();
			return;
		}
		this.removableIdList = data.list;
		var o = this;
		Rest._token = e('_csrf_token').value;
		Rest._post({list: data.list}, function(data){o.onSuccessRemoveCatalog(data);},
			br + '/drivermrf.json',
			function(data, responseText, info, xhr){/*o.onFailRemoveCatalog*/o.onFailGetRemoveIdList(data, responseText, info, xhr)});
	},
	onFailGetRemoveIdList:function(data, responseText, info, xhr){
		var r = defaultResponseError(data, responseText, info, xhr);
		if (!r) {
			this.removableIdList = [];
		}
		return r;
	},
	onSuccessRemoveCatalog:function(data) {
		if (!this.onFailGetRemoveIdList(data)) {
			return;
		}
		data = {list: this.removableIdList};
		var i, sZ = sz(data.list.length), idList = [], current, idx, j;
		// remove current and childs
		for (i = 0; i < sZ; i++) {
			localStorage.removeItem('f' + data.list[i]);
		}
		//remove from parent list
		current = storage('f' + currentDir);
		idx = -1;
		if (current instanceof Array) {
			sZ = sz(current);
			for (i = 0; i < sZ; i++) {
				j = current[i];
				if (j.i == this.cmMenuOpenItemId && j.type == 'c') {
					idx = i;
					break;
				}
			}
			if (idx > -1) {
				current.splice(idx, 1);
			}
			storage('f' + currentDir, current);
		}
		fileList.render(current);
		hideLoader();
	},
	onSuccessRemoveFile:function(data) {
		if (!this.onFailGetRemoveIdList(data)) {
			return;
		}
		
		var i, current, idx, j;
		
		//remove from parent list
		current = storage('f' + currentDir);
		idx = -1;
		if (current instanceof Array) {
			sz(current);
			for (i = 0; i < SZ; i++) {
				j = current[i];
				if (j.i == this.cmMenuOpenItemId && j.type != 'c') {
					idx = i;
					break;
				}
			}
			if (idx > -1) {
				current.splice(idx, 1);
			}
			storage('f' + currentDir, current);
		}
		fileList.render(current);
		hideLoader();
	},
	onClickRename:function(evt) {
		/*if (o.cmMenuOpenItemType != 'c') {
			showLoader();
			Rest._post(
				{i: o.cmMenuOpenItemId}, function(data){o.onSuccessRemoveFile(data);},
				br + '/driverm.json',
				function(data, responseText, info, xhr){o.onFailGetRemoveIdList(data, responseText, info, xhr)}
			);
			return;
		}*/
		var name = '',
			o = this,
			r = o.findItemByIdAndType();
		if (r.i > -1) {
			name = r.ls[r.i].name;
		}
		
		o.sourceExt = o.getExtensionPart(name);
		name = name.replace(new RegExp(o.sourceExt + '$', 'i'), '');
		
		window.onClickInputDlgOk = function(evt) {
			o.onEnterNewName(evt.inputStr);
		}
		
		window.onClickInputDlgCancel = function(evt) {
			o.onCancelEnterNewName();
		}

		showInputDlg(l('Enter new name'), name);
	},
	onEnterNewName:function(newName) {
		var o = this,
			action = '/drivern.json';
		showLoader();
		Rest._post({i: o.cmMenuOpenItemId, s: newName + o.sourceExt, c: currentDir, t: o.cmMenuOpenItemType}, function(data){o.onSuccessRenameCatalog(data);},
		br + '/drivern.json',
		function(data, responseText, info, xhr){o.onFailRenameCatalog(data, responseText, info, xhr)});
	},
	onCancelEnterNewName:function() {
		hideLoader();
	},
	onSuccessRenameCatalog:function(data) {
		if (!this.onFailRenameCatalog(data)) {
			return;
		}
		var o = this,
			r = o.findItemByIdAndType();
		if (r.i > -1) {
			r.ls[r.i].name = data.name;
			storage('f' + currentDir, r.ls);
		}
		fileList.render(r.ls);
	},
	/**
	 * @return Object {ls:Array, i:Number}
	*/
	findItemByIdAndType:function() {
		/*var ls = storage('f' + currentDir),
			i, o = this, t,
			r = {ls: [], i: -1};
		sz(ls);
		for (i = 0; i < SZ; i++) {
			t = ls[i].type;
			t = t == 'c' ? t : 'f';
			if (ls[i].i == o.cmMenuOpenItemId && o.cmMenuOpenItemType == t) {
				r.ls = ls;
				r.i = i;
				return r;
			}
		}
		return r;*/
		return fileList.findItemByIdAndType(o.cmMenuOpenItemId, o.cmMenuOpenItemType, 'f' + currentDir);
	},
	
	/**
	 * @description If filename end is '.some.zip' retun '.some.zip'
	 * If filename is 'somestring.ext' return 'ext'
	*/
	getExtensionPart:function(name) {
		var a = name.split('.'),
			ext, isZip = false, type, extF, extFType;
        ext = def(a[sz(a) - 1], 0) ? a[sz(a) - 1] : '';
        extF = ext;
        if ('zip' == ext) {
            ext = def(a[sz(a) - 2]) ? a[sz(a) - 2] : '';
            isZip = true;
        }
        type = this.getTypeByExtension(ext);
        extFType = this.getTypeByExtension(extF);
        if ('unknown' == extFType) {
			return '';
		}
        if ('unknown' == type && isZip) {
            return '.zip';
        } else if ('unknown' == type && extF) {
			return '.' + extF;
		} else if ('unknown' == type && !extF) {
			return '';
		} else if ('unknown' != type && extF) {
			if (isZip) {
				return '.' + ext + '.zip';
			}
			return '.' + ext;
			
		}
		return '';
	},
	getTypeByExtension:function(extension)
    {
        var types = {
            //zip
            'zip' : 'zip',
            'gz' : 'zip',
            '7z' : 'zip',
            // audio
            'mp3' : 'audio',
            'ogg' : 'audio',
            'wav' : 'audio',
            'mp4' : 'audio',

            // text
            'txt' : 'text',
            'php' : 'text',
            'js' : 'text',

            // image
            'jpg' : 'image',
            'png' : 'image',
            'bmp' : 'image',
            'jpeg' : 'image',
            'jiff' : 'image',

            // pdf
            'pdf' : 'pdf',

            // apk
            'apk' : 'apk',
        };
        return types[extension] ? types[extension] : 'unknown';
    },

	onFailRenameCatalog:function(data, responseText, info, xhr) {
		hideLoader();
		return defaultResponseError(data, responseText, info, xhr);
	},
	onClickMove:function(evt) {
		alert('Call m');
	},
	
	onClickDownload:function(evt) {
		var o = this;
		Rest._get(function(data){o.onSuccessGetDLink(data);},
			br + '/drivegetlink.json?i=' + this.cmMenuOpenItemId,
			function(data, responseText, info, xhr){o.onFailGetDLink(data, responseText, info, xhr)});
	},
	
	onSuccessGetDLink:function(data){
		if (this.onFailGetDLink(data)) {
			window.location.href = data.link;
		}
	},
	
	onFailGetDLink:function(data, responseText, info, xhr){
		showScreen('hCatalogScreen');
		return defaultResponseError(data, responseText, info, xhr);
	}
	
};
