window.fileListItemCmenu = {
	dirMenuItems: [
		l('Remove'),
		l('Rename'),
		l('Move'),
		l('Exit')
	],
	fileMediaMenuItems: [
		l('Remove'),
		l('Rename'),
		l('Download'),
		l('Move'),
		l('Exit'),
		l('Share link')
	],
	fileTextMenuItems: [
		l('Remove'),
		l('Rename'),
		l('Download'),
		l('Move'),
		l('Exit'),
		l('Share link')
	],
	fileUnknownMenuItems: [
		l('Remove'),
		l('Rename'),
		l('Download'),
		l('Move'),
		l('Exit'),
		l('Share link')
	],
	/**
	 * @description Build and show folder or file context menu
	*/
	buildAndShowMenu:function(id){
		var isDir = id.indexOf('fi') != 0,
			item,
			menuItems = this.fileUnknownMenuItems, o = this, b;
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
		
		o = this;
		b = e("bGotoCatFromDwnlScr");
		if (b) {
			b.onclick = function(){
				o.onClickCloseWdLinkScreen();
			}
		}
		
		showScreen('hCatItemMenu');
	},
	
	onClickCloseWdLinkScreen:function() {
		showScreen('hCatalogScreen');
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
			propertiesText,
			filetimeText,
			catalogPropertiesText = '',
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
				case l('Share link'):
					menuItem.onclick = function(evt) {
						if (self.delayForMenuItems) {
							evt.preventDefault();
							return false;
						}
						self.onClickShareLink.call(self, evt);
					};
					break;
				case l('Exit'):
					menuItem.onclick = function(evt) {
						if (self.delayForMenuItems) {
							evt.preventDefault();
							return false;
						}
						self.onClickExit.call(self, evt);
					};
					break;
			}
		}// end for
		
		// Add Properties item
		propertiesText = '<span class="black">' + l('Properties') + ':</span><div class="props">\
			<div class="cim_item_name">' + fileListItem.name + '</div>\
			<div class="prop">' + self.unpackHexSz(fileListItem.s) + '</div>{folderProps}\
			{fileTime}\
		</div>';
		if (fileListItem.type == 'c') {
			catalogPropertiesText = '<div class="prop">' + l('Files') + ': ' + fileListItem.qf + '</div>';
			catalogPropertiesText += '<div class="prop">' + l('Catalogs') + ': ' + fileListItem.qc + '</div>';
		}
		propertiesText = propertiesText.replace('{folderProps}', catalogPropertiesText);
		
		filetimeText = '<div class="prop">' + l('Uploaded') + ': ' + SqzDatetime.desqzDatetime(fileListItem.ct, 0) + '</div>';
		filetimeText += '<div class="prop">' + l('Modify') + ': ' + SqzDatetime.desqzDatetime(fileListItem.ut, 0) + '</div>';
		propertiesText = propertiesText.replace('{fileTime}', filetimeText);
		
		appendChild('hListItemCmItems', 'div', propertiesText, {'class': 'cim_item_props gray'});
		
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
		var o = this;
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
		addClass('hBotMenu', 'hide');
		selectMode = 1;
		var ls = storage('f' + currentDir),
			bc = path.replace('/wcard', ''),
			o = this,
			id = (o.cmMenuOpenItemType == 'c' ? 'f' : 'fi') + o.cmMenuOpenItemId;
		
		selectedItems = {};
		selectedItems[id] = currentDir;
		
		
		fileList.renderCurrentDir(ls, bc);
		hideLoader();
	},
	
	onClickDownload:function(evt) {
		var o = this;
		Rest._get(function(data){o.onSuccessGetDLink(data);},
			br + '/drivegetlink.json?i=' + this.cmMenuOpenItemId,
			function(data, responseText, info, xhr){o.onFailGetDLink(data, responseText, info, xhr)});
	},
	
	onSuccessGetDLink:function(d){
		var isA2, s;
		if (this.onFailGetDLink(d)) {
			isA2 = ~window.navigator.userAgent.toLowerCase().indexOf("android 2.");
			if (~d.link.indexOf("https://yadi.sk") && !isA2) {
				window.open(d.link, "_blank");
				return;
			} else if (isA2) {
				s = d.link.replace("https://", "http://");
				attr("wdlnk", "href", s);
				v("wdlnk", s);
				showScreen("hDwnldWDScreen");
				//window.location.href = d.link.replace("https://", "http://");
				return;
			}
			window.location.href = d.link;
		}
	},
	
	onFailGetDLink:function(data, responseText, info, xhr){
		showScreen('hCatalogScreen');
		return defaultResponseError(data, responseText, info, xhr);
	},
	
	onClickExit:function(evt) {
		showScreen('hCatalogScreen');
	},
	
	unpackHexSz:function(n) {
		var a = String(n).split('g'), i, r;
		sz(a);
		for (i = 0; i < SZ - 1; i++) {
			a[i] = parseInt(a[i], 16);
		}
		
		if (SZ > 2) {
			r = this.formatNumber(S(a[0])) + ',' + a[1] + ' ' + a[2];
		} else {
			r = this.formatNumber(S(a[0])) + ' ' + a[1];
		}
		
		return r;
	},
	
	/**
	 * @return Number
	*/
	calculateFromHexSz:function(n) {
		var a = String(n).split('g'), i, r, meas, m = 1;
		sz(a);
		for (i = 0; i < SZ - 1; i++) {
			a[i] = parseInt(a[i], 16);
		}
		
		if (SZ > 2) {
			meas = a[2];
			r = parseFloat(a[0] + '.' + a[1]);
		} else {
			meas = a[1];
			r = parseFloat(a[0]);
		}
		
		switch (meas) {
			case 'b':
				m = 1;
				break;
			case 'Kb':
				m = 1000;
				break;
			case 'Mb':
				m = 1000000;
				break;
			case 'Gb':
				m = 1000000000;
				break;
		}
		
		return r * m;
	},
	
	formatNumber:function(s) {
		var i, a = [], j = 0;
		for (i = s.length - 1; i > -1 ; i--, j++) {
			if (j > 0 && (j % 3) ==  0) {
				a.push(' ');
			}
			a.push(s.charAt(i));
		}
		s = a.reverse().join('');
		return s;
	},
	
	onClickShareLink:function(evt) {
		var o = this;
		showLoader();
		Rest._get(function(data){o.onSuccessGetFPerm(data);},
			br + '/drivegetfileprm.json?i=' + this.cmMenuOpenItemId,
			function(data, responseText, info, xhr){o.onFailGetFPerm(data, responseText, info, xhr)});
	},
	onSuccessGetFPerm:function(data){
		var rd;
		if (this.onFailGetFPerm(data)) {
			// TODO set fields
			W.ShareLinkSetter.setLink(data.flink);
			
			rd = e(data.shareMode);
			if (rd) {
				rd.checked = true;
			}
			this.renderUsers(data.uls);
			
			showScreen('hFilePermission');
		}
	},
	
	renderUsers:function(list){
		var cont = cs(D, 'customUsersWrapper')[0], i, 
			SZ = sz(list),
			tpl = this.userViewTpl(), s;
		cont.innerHTML = '';
		for (i = 0; i < SZ; i++) {
			s = str_replace('{id}', list[i].id, tpl);
			s = str_replace('{login}', list[i].login, s);
			s = str_replace('{fid}', this.cmMenuOpenItemId, s);
			s = str_replace('{root}', W.roota2, s);
			cont.innerHTML += s;
		}
	},
	
	userViewTpl:function() {
		return '<div class="userCardSm" id="usr{id}">\
						<span class="userCardSmAvatar">\
							<img src="{root}/i/usr.png">\
						</span>\
						<span class="userCardSmNick">{login}</span>\
						<span class="userCardSmAvatarRmBtn" onclick="FPC.rm(event, {id}, {fid})">\
							<img src="{root}/i/clos48.png" class="rmu" onclick="FPC.rm(event, {id}, {fid})">\
						</span>\
						<div class="cl"></div>\
					</div>';
	},
	
	onFailGetFPerm:function(data, responseText, info, xhr){
		showScreen('hCatalogScreen');
		return defaultResponseError(data, responseText, info, xhr);
	}
	
};
