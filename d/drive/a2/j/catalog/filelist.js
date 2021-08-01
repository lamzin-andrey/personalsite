window.fileList = {
	id: 'fl',
	addCatalog:function(name, current) {
		var ls = storage(current),
			inObj;
		if (!(ls instanceof Array)) {
			ls = [];
		}
		if (!(name in In(ls))) {
			ls.push({type:'c', name:name});
		}
		
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
		// this.clear();
		
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
		for (i = 0; i < sZ; i++) {
			j = ls[i];
			e(this.id).removeChild(j);
		}
	}
	
};
