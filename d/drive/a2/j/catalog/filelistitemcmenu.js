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
		for (i = 0; i < sZ; i++) {
			menuItem = appendChild('hListItemCmItems', 'div', ls[i], {'class': 'cim_item'});
			switch (ls[i]) {
				case l('Remove'):
					menuItem.onclick = function(evt) {
						self.onClickRemove.call(self, evt);
					};
					break;
				case l('Rename'):
					menuItem.onclick = function(evt) {
						self.onClickRename.call(self, evt);
					};
					break;
				case l('Move'):
					menuItem.onclick = function(evt) {
						self.onClickMove.call(self, evt);
					};
					break;
				case l('Download'):
					this.onClickDownload;
					menuItem.onclick = function(evt) {
						self.onClickDownload.call(self, evt);
					};
					break;
			}
			
			
		}
	},
	
	onClickRemove:function(evt) {
		var o = this;
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
		current = storage('f' + parentDir);
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
			storage('f' + parentDir, current);
		}
		fileList.render(current);
		hideLoader();
	},
	onClickRename:function(evt) {
		alert('Call rename');
	},
	onClickMove:function(evt) {
		alert('Call m');
	},
	onClickDownload:function(evt) {
		alert('Call d');
	}
	
};
