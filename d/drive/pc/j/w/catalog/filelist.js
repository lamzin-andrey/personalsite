window.fileList = window.fileList || {};
if (!fileList.render) {
	showError('Unable load parent fileList object');
}

fileList.render = function(list){
	var tpl = // '<div class="it">' + 
						'<div class="im_wrapper">' + 
							'<img src="{path}/i/mime/{t}.png">' +
						'</div>' +
						'<span class="fn">{name}</span>{checkbox}' + 
						'<div class="right dMenuBlock">...</div>' + 
						'<div class="cl"></div>',
					// '</div>',
		prefix = 'f',
		s, i, sZ = sz(list), j, newItem, cid, chName,
		vAttr = {
			'class': 'it'
		},
		self = this, checkboxTpl = '<div class="imch_wrapper">' + 
							'<img src="' + roota2 + '/i/{ct}.jpg" class="chVw">' +
						'</div>',
		dMenuBlock;
		
		if (!selectMode) {
			checkboxTpl = '';
			removeClass(this.id, 'selmod');
		} else {
			addClass(this.id, 'selmod');
		}
		tpl = tpl.replace('{checkbox}', checkboxTpl);
		
		this.clear();
		
		if (sZ > 0) {
			removeClass(e(this.id), 'empty');
		}
		
		list = this.sort(list);
		sZ = sz(list);
		for (i = 0; i < sZ; i++) {
			j = list[i];
			s = tpl.replace('{t}', j.type);
			s = s.replace('{path}', roota2);
			s = s.replace('{name}', j.name);
			vAttr['data-name'] = j.name;
			prefix = 'f';
			if (j.type != 'c') {
				prefix = 'fi';
			}
			cid = vAttr['id'] = prefix + j.i;
			
			if (selectMode) {
				chName = 'check_intive';
				if (selectedItems[cid]) {
					chName = 'check_active';
				}
				s = s.replace('{ct}', chName);
			}
			
			newItem = appendChild(e(this.id), 'div', s, vAttr);
			newItem.addEventListener('click', function(evt){self.onClickItem(evt);}, false);
			dMenuBlock = cs(newItem, 'dMenuBlock')[0];
			dMenuBlock.addEventListener('click', function(evt){self.onClickItemMenu(evt);}, false);
		}
		e('hPath').innerHTML = path;
		if (path.length > getViewport().w - 100) {
			setTimeout(function(){
				e('hPath').innerHTML = '<marquee scrolldelay="42" scrollamount="1">' + path + '</marquee>';
			}, 2*1000);
		}
		onLoadA236();
}

fileList.onClickItem = function(evt) {
	try {
		var id = this.getItemId(evt.currentTarget),
			dt = new Date(),
			time = dt.getTime(),
			startTime,
			endY = this.getTouchY(evt);
		if (!id) {
			alert('Fail get item id on end click');
			return;
		}
		e(id).style['background-color'] = null;
		
		
		startTime = this.touchItemsMap[id] ? this.touchItemsMap[id] : 0;
		
		if (!this.touchItemsMap[id]) {
			console.log('onFirstClickItem');
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
				self.touchItemsMap[id] = 0;
				delete self.touchItemsMap[id];
			}, 1000);
			if (selectMode) {
				this.onClickCheckbox({target: e(id)});
			}
			
			return true;
		}
		
		
		if (Math.abs(this.startY - endY) < 1) {
			if (time - startTime < 500) { // double click
				if (id.indexOf('fi') == -1) { // folder
					if (selectMode) { // Инвертируем выделение установленное при первом клике
						this.onClickCheckbox({target: e(id)});
					}
					
					if (!selectedItems[id]) {
						this.onEnterInFolder({target: e(id)});
					} else {
						showError(l('Unable move folder into this. (Deselect it)'));
					}
				} else { // file
					if (selectMode) {
						this.onClickCheckbox({target: e(id)});
					}
				}
			} else { // one click
				if (!selectMode) {
					// this.onCallContextMenu({target: e(id)});
				} else {
					// if (id.indexOf('fi') == -1) {
						this.onClickCheckbox({target: e(id)});
					// }
				}
			}
		} else {
			this.clearActiveItems();
		}
		
	} catch (err) {
		showError(err);
	}
}


fileList.onClickItemMenu = function(evt) {
	evt.preventDefault();
	var o = this, id = o.getItemId(evt.currentTarget);
	o.onCallContextMenu({target: e(id)});
	return false;
}
