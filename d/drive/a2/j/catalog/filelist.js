window.fileList = {
	id: 'fl',
	addCatalog:function(name, id) {
		var ls = storage('f' + currentDir),
			inObj;
		if (!(ls instanceof Array)) {
			ls = [];
		}
		if (!(name in In(ls))) {
			ls.push({type:'c', name:name, i: id});
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
		s, i, sZ = sz(list), j, newItem,
		attr = {
			'class': 'it'
		};
		this.clear();
		
		if (sZ > 0) {
			removeClass(e(this.id), 'empty');
		}
		
		for (i = 0; i < sZ; i++) {
			j = list[i];
			s = tpl.replace('{t}', j.type);
			s = s.replace('{name}', j.name);
			attr['data-name'] = j.name;
			newItem = appendChild(e(this.id), 'div', s, attr);
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
		storage('f' + currentDir, data.ls);
		this.render(data.ls);
	},
	
	onFailGetFileList:function(data, responseText, info, xhr) {
		hideLoader();
		return defaultResponseError(data, responseText, info, xhr);
	},
	
};
