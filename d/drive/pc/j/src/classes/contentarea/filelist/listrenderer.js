function ListRenderer(){
	this.context = null;
	this.sz = 0;
	this.part = 0;
	this.ls = [];
	this.processing = false;
	this.filesSize = 0;
	this.dfProc = 0;
}

ListRenderer.ONE_ITEM_HEIGHT = 30;

ListRenderer.prototype.renderPart = function(){
	var start = 0,
		end = sz(this.ls), i, j,
		item, block, s,
		done = 1,
		o, self = this,
		statusText, freeSpaceText = '', sizeText = '',
		cmId,
		createdItemFound = -1, el, domLs, domEl;
	domLs = cs(this.context.contentBlock, this.context.cName);
	o = this.context;
	for (i = start, j = 0; i < end; i++, j++) {
		item = this.ls[i];
		domEl = domLs[j];
		el = this.appendNew(i, item);
		
		if (item.name == app.tab.createdItemName) {
			createdItemFound = i;
		}
		
	}
	if (createdItemFound > -1) {
		app.tab.selectItemByIdx(createdItemFound);
	}
	
	if (done) {
		this.processing = false;
		if (!this.skipCalcSize) {
			for (i = 0; i < end; i++) {
				item = this.ls[i];
				this.incSize(item.sz);
			}
		}
		freeSpaceText = 'TODO free space text';
		console.log("here used DevM.getPluralFreeSpaceOfDiskPartByPath");
		if (freeSpaceText) {
			freeSpaceText = ', ' + freeSpaceText;
		}
		statusText = this.sz + ' ' 
						+ TextTransform.pluralize(this.sz, L('Objects'), L('Objects-voice1'), L('Objects-voice2'))
						+ ' (' + this.getHumanFilesize(intval(this.filesSize), 2, 3, false) + ')'
						+ freeSpaceText;
		this.context.setStatus.call(this.context, statusText);
		
	}
}


ListRenderer.prototype.run = function(sz, context, ls, firstItemIdx, skipCalcSize){
	var o = this;
	
	context.contentBlock.innerHTML = '';
	o.context = context;
	o.sz = sz;
	o.ls = ls;
	if (!skipCalcSize) {
		o.filesSize = 0;
	}
	o.skipCalcSize = skipCalcSize;
	
	o.processing = true;
	o.renderPart();
	
	return true;
}

ListRenderer.prototype.incSize = function(sz){
	var aSz = sz.split(','), fSz, m = 1024;
	aSz[1] = (aSz[1] ? aSz[1] : '').replace(/\D/mig, '');
	fSz = aSz[0] + '.' + aSz[1];
	fSz = parseFloat(fSz);
	if (sz.indexOf('K') != -1) {
		fSz *= m;
	} else if (sz.indexOf('M') != -1) {
		fSz *= m * m;
	} else if (sz.indexOf('G') != -1) {
		fSz *= m * m * m;
	} else if (sz.indexOf('T') != -1) {
		fSz *= m * m * m * m;
	}
	this.filesSize += fSz;
	return fSz;
}



ListRenderer.prototype.getHumanFilesize = function($n, $percision, $maxOrder, $pack) {
    var fileSize = new FileSize();
    return fileSize.getHumanValue($n,
		['Bytes', 'KB', 'MB', 'GB', 'TB'],
		1024,
		$percision,
		$maxOrder,
		$pack
    );
}



ListRenderer.prototype.createElement = function(item, i) {
	var s, active = 'active';
	if (e('f' + i)) {
		return e('f' + i);
	}
	if (!this.context.oSelectionItems['f' + i]) {
		active = ''
	}
	s = this.context.tpl.call(this.context);
	s = s.replace('{name}', item.name);
	s = s.replace('{name}', item.name);
	s = s.replace('{img}', item.i);
	s = s.replace('{sz}', item.sz);
	s = s.replace('{type}', item.type);
	s = s.replace('{type}', item.type);
	s = s.replace('{mt}', item.mt);
	s = s.replace('{active}', active);
	s = s.replace('{id}', i);
	s = s.replace('{id}', i);
	s = s.replace('{did}', item.id);
	block = appendChild(this.context.contentBlock, 'div', s, {
		'data-cmid': item.cmId,
		'data-id': "f" + i,
		'data-handler': "onContextMenu",
		'data-handler-context': "tab",
		id: 'f' + i
	});
	
	return block;
}

ListRenderer.prototype.updateItem = function(i, newItem, existsNode) {
	var el = e('f' + i), child;
	if (existsNode) {
		el = existsNode;
	}
	if (!el) {
		return;
	}
	attr(el, 'data-cmid', newItem.cmId);
	attr(el, 'data-id', 'f' + i);
	attr(el, 'id', 'f' + i);
	stl(el, 'display', null);
	this.renderItemName(i, newItem.name);
	this.renderItemIcon(i, newItem.i);
	this.renderItemSize(i, newItem.sz);
	this.renderItemType(i, newItem.type);
	this.renderItemModifyDate(i, newItem.mt);
}
ListRenderer.prototype.renderItemModifyDate = function(i, mt) {
	this.setSubValue(e('f' + i), 'tabContentItemDate', mt);
}
ListRenderer.prototype.setSubValue = function(el, className, val) {
	var child = cs(el, className)[0];
	if (child) {
		child = cs(child, 'tabContentItemName')[0];
		if (child) {
			child.innerHTML = val;
		}
	}
}
ListRenderer.prototype.renderItemType = function(i, t) {
	var el = e('f' + i), 
		child = cs(el, 'tabContentItemType')[0];
	if (child) {
		attr(child, 'title' , t);
		child = cs(child, 'tabContentItemName')[0];
		if (child) {
			child.innerHTML = t;
		}
	}
}
ListRenderer.prototype.renderItemSize = function(i, size) {
	this.setSubValue(e('f' + i), 'tabContentItemSize', size);
}
ListRenderer.prototype.renderItemIcon = function(i, src) {
	var child = cs(('f' + i), 'imgTabContentItemIcon')[0];
	if (child) {
		attr(child, 'src', src);
		/*child.onload = function() {
			app.tab.onLoadPreview(i);
		}*/
	}
}
ListRenderer.prototype.getCurrentIcon = function(i) {
	var child = cs(('f' + i), 'imgTabContentItemIcon')[0];
	if (child) {
		return attr(child, 'src');
	}
	return '';
}
ListRenderer.prototype.setCurrentIcon = function(i, src) {
	var child = cs(('f' + i), 'imgTabContentItemIcon')[0];
	if (child) {
		attr(child, 'src', src);
	}
}
ListRenderer.prototype.renderItemName = function(i, name) {	
	var child = cs(e('f' + i), this.context.cName)[0];
	if (child) {
		attr(child, 'title', name + ' id = f' + i);
		this.setSubValue(child, 'tabContentItemNameMain', name);
	}
}
ListRenderer.prototype.setListeners = function(block) {
	var o = this.context;
	block.onclick = function(evt) {
		o.onClickItem.call(o, evt);
	}
}
ListRenderer.prototype.appendNew = function(i, item) {
	var block = this.createElement(item, i);
	this.setListeners(block);
	return block;
}



