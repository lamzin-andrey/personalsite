window.fileList = {
	id: 'fl',
	/** @property  touchItemsMap store time start touch */
	touchItemsMap: {},
	addCatalog:function(name, id, type) {
		type = def(type, 'c');
		var ls = storage('f' + currentDir),
			inObj;
		if (!(ls instanceof Array)) {
			ls = [];
		}
		if (!(name in In(ls))) {
			ls.push({type:type, name:name, i: id});
		}
		storage('f' + currentDir, ls);
		
		this.render(ls);
	},
	
	render:function(list) {
		var tpl = // '<div class="it">' + 
						'<div class="im_wrapper">' + 
							'<img src="./a2/i/mime/{t}.png">' +
						'</div>' +
						'<span class="fn">{name}</span>' + 
						'<div class="cl"></div>',
					// '</div>',
		prefix = 'f',
		s, i, sZ = sz(list), j, newItem,
		vAttr = {
			'class': 'it'
		},
		
		self = this;
		this.clear();
		
		if (sZ > 0) {
			removeClass(e(this.id), 'empty');
		}
		
		for (i = 0; i < sZ; i++) {
			j = list[i];
			s = tpl.replace('{t}', j.type);
			s = s.replace('{name}', j.name);
			vAttr['data-name'] = j.name;
			prefix = 'f';
			if (j.type != 'c') {
				prefix = 'fi';
			}
			vAttr['id'] = prefix + j.i;
			newItem = appendChild(e(this.id), 'div', s, vAttr);
			
			newItem.addEventListener('touchstart', function(evt){self.onStartTouchItem(evt);}, false);
			newItem.addEventListener('touchend', function(evt){self.onEndTouchItem(evt);}, false);
			
		}
		e('hPath').innerHTML = path;
		if (path.length > 30) {
			setTimeout(function(){
				e('hPath').innerHTML = '<marquee scrolldelay="42" scrollamount="1">' + path + '</marquee>';
			}, 2*1000);
		}
	},
	
	clear:function() {
		addClass(e(this.id), 'empty');
		var ls = cs(this.id, 'it'), i, sZ = sz(ls), j;
		for (i = sZ - 1; i > -1; i--) {
			j = ls[i];
			e(this.id).removeChild(j);
		}
	},
	
	loadCurrentDir: function() {
		showLoader();
		//TODO RestLS
		currentDir = parseInt(storage('pwd') );
		currentDir = isNaN(currentDir) ? 0 : currentDir;
		// TODO режим показа скрытых файлов m=1
		var self = this;
		Rest._get(function(data){
			self.onSuccessGetFileList(data);
		},
		
		br + '/drivelist.json?c=' + currentDir + '&m=0', 
		
		function(data, responseText, info, xhr) {
			self.onFailGetFileList(data, responseText, info, xhr);
		});
	},
	onSuccessGetFileList:function(data) {
		if (!this.onFailGetFileList(data)) {
			return;
		}
		window.parentDir = data.p;
		if (currentDir == 0) {
			setUpButtonDisable(this.bUp);
		} else {
			setUpButtonEnable(this.bUp);
		}
		
		if (currentDir == homeDir) {
			setHomeButtonDisable(this.bHome);
		} else {
			setHomeButtonEnable(this.bHome);
		}
		
		storage('f' + currentDir, data.ls);
		var s = '/wcard';
		path = data.bc == '/' ? s : (s + data.bc);
		this.render(data.ls);
	},
	
	onFailGetFileList:function(data, responseText, info, xhr) {
		hideLoader();
		return defaultResponseError(data, responseText, info, xhr);
	},
	
	onStartTouchItem:function(evt) {
		console.log('onStartTouchItem');
		this.startY = this.getTouchY(evt);
		// #302e9f
		var id = this.getItemId(evt.currentTarget),
			dt = new Date(),
			self = this,
			time = dt.getTime();
		if (!id) {
			alert('Fail get item id');
			return;
		}
		this.touchItemsMap[id] = time;
		e(id).style['background-color'] = '#302e9f';
		setTimeout(function() {
			self.clearActiveItems();
		}, 1000);
		
		return true;
	},
	
	onEndTouchItem:function(evt) {
		try {
			var id = this.getItemId(evt.currentTarget),
				dt = new Date(),
				time = dt.getTime(),
				startTime,
				endY = this.getTouchY(evt);
			if (!id) {
				alert('Fail get item id on end touch');
				return;
			}
			e(id).style['background-color'] = null;
			
			
			startTime = this.touchItemsMap[id] ? this.touchItemsMap[id] : 0;
			if (Math.abs(this.startY - endY) < 1) {
				if (time - startTime < 500) {
					console.log(id);
					if (id.indexOf('fi') == -1) {
						this.onEnterInFolder({target: e(id)});
					}
				} else {
					this.onCallContextMenu({target: e(id)});
				}
			} else {
				this.clearActiveItems();
			}
		} catch (err) {
			alert(err);
		}
	},
	
	clearActiveItems:function() {
		var ls = cs(e('fl', 'it')), i;
			sz(ls);
		for (i = 0; i < SZ; i++) {
			ls[i].style['background-color'] = null;
		}
	},
	
	getTouchY:function(evt){
		var o = evt.changedTouches;
		o = o && o[0] ? o[0] : 0;
		o = o && o.clientY ? o.clientY : 0;
		
		return o;
	},
	
	getItemId:function(currentTarget) {
		var node = currentTarget, id;
		do {
			id = attr(node, 'id');
			if (id && hasClass(node, 'it')) {
				return id;
			}
			node = node.parentNode;
		} while (node);
		
		return '';
	},
	
	onCallContextMenu:function(evt) {
		console.log('Will show cm ' + evt.target.id);
		fileListItemCmenu.buildAndShowMenu(evt.target.id);
	},
	
	onEnterInFolder:function(evt) {
		var id = attr(evt.target, 'id');
		window.currentDir = id.replace('fi', '').replace('f', '');
		storage('pwd', currentDir);
		if (window.currentDir > 0) {
			this.loadCurrentDir();
		}
	},
	
	initUpButton:function(bUp){
		var o = this;
		this.bUp = bUp;
		bUp.onclick = function(evt){
			return o.onClickUpButton(evt);
		}
	},
	
	onClickUpButton:function(evt){
		if (currentDir != parentDir) {
			setUpButtonEnable(this.bUp);
			window.currentDir = parentDir;
			storage('pwd', currentDir);
			this.loadCurrentDir();
		} else {
			setUpButtonDisable(this.bUp);
		}
		//-
		if (currentDir != homeDir) {
			setHomeButtonEnable(this.bHome);
		} else {
			setHomeButtonDisable(this.bHome);
		}
	},
	
	initHomeButton:function(bHome){
		var o = this;
		this.bHome = bHome;
		bHome.onclick = function(evt){
			return o.onClickHomeButton(evt);
		}
	},
	
	onClickHomeButton:function(evt){
		if (currentDir != homeDir) {
			setHomeButtonEnable(this.bHome);
			window.currentDir = homeDir;
			storage('pwd', currentDir);
			this.loadCurrentDir();
		} else {
			setHomeButtonDisable(this.bHome);
		}
		//-
		if (currentDir != parentDir) {
			setUpButtonEnable(this.bUp);
		} else {
			setUpButtonDisable(this.bUp);
		}
	}
	
};
