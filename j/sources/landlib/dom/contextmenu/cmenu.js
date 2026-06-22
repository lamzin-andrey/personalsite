// V2
// Основное изменеие: Вместо того, чтобы руками рисовать DOM структуру 
// заводим windows.CM_LS = {};
// и добавляем туда такое для каждого пункта меню:
// W.CM_LS[cmId] = "./i/icon16.png    #путь к иконке, использовать NS если нет
//Открыть           #текст меню
//alert(101)        #значение onclick, использовать NS если нет";
window.CM_LS = {};
window.NL = "\n";
window.NS = "&nbsp;";
window.oncontextmenu = function(event) {
	event.preventDefault();
	// console.log(event);
	
	var s = ContextMenuManager.getTpl(event),
		id = ContextMenuManager.id, cmWrapper, vp = getViewport(),
		x, y, w, h;
	if (s) {
		//alert(s);
		cmWrapper = e(id);
		if (!cmWrapper) {
			cmWrapper = appendChild(bod(), 'div', s, {'id': id}, {});
			cmWrapper.style.position = 'absolute';
			cmWrapper.style.left = '0px';
			cmWrapper.style.top = '0px';
			cmWrapper.style.zIndex = 5004;
		}
		cmWrapper.style.opacity = 0.0;
		cmWrapper.style.display = 'block';
		cmWrapper.innerHTML = s;
		x = event.clientX;
		y = event.clientY;
		w = cmWrapper.offsetWidth;
		h = cmWrapper.offsetHeight;
		cmWrapper.style.left = x + 'px';
		cmWrapper.style.top = y + 'px';
		
		if (x + w > vp.w) {
			x = x - w;
		}
		if (y + h > vp.h) {
			y = y - h;
		}
		if (y < 0) {
			y = 0;
		}
		cmWrapper.style.left = x + 'px';
		cmWrapper.style.top = y + 'px';
		cmWrapper.style.opacity = 1.0;
	}
	
	return false;
}

window.ContextMenuManager = {
	id: 'qdjsfmcm',
	
	hide: function() {
		if (e(this.id)) {
			stl(this.id, 'display', 'none');
		}
	},
	
	getTpl:function(event) {
		var htmlElement = this.getCurrentTarget(event);
		// console.log(htmlElement);
		if (!htmlElement) {
			//alert("getTpl:!htmlElement");
			return '';
		}
		var cmId = htmlElement.getAttribute('data-cmid'),
			targetId = htmlElement.getAttribute('data-id'),
			targetHandler = htmlElement.getAttribute('data-handler'),
			targetHandlerContext = htmlElement.getAttribute('data-handler-context');
		if (!cmId && !targetId) {
			//alert("getTpl:!cmId && !targetI");
			this.hide();
			return '';
		}
		window.currentCmTargetId = targetId;
		if (targetHandlerContext && (window[targetHandlerContext] instanceof Object) && (window[targetHandlerContext][targetHandler] instanceof Function)) {
			window[targetHandlerContext][targetHandler].call(window[targetHandlerContext], targetId, event)
		}
		if (!window.CM_LS[cmId]) {
			this.hide();
		}
		//alert("e(cmId).innerHTML: " + e(cmId).innerHTML + ", cmId " + cmId);
		//return e(cmId).innerHTML;
		return this.createHtml(cmId);
	},
	
	createHtml:function(id) {
		var tpl = //'<div id="{id}">' + 
	'<div class="contextMenu">{items}</div>'
// + '</div>'
,
			itemTpl = '<div class="contextMenuItem"{onclick}>' + 
			'<div class="contextMenuItemIcon">{ico}</div>' + 
			'<div class="contextMenuItemText">{txt}</div>' + 
			'<div class="cf"></div>' + 
		'</div>',
			s, ls, i, z, data, ico, txt, lst;
		data = window.CM_LS[id];
		if (!data) {
			return '';
		}
		s = tpl.replace('{id}', id);
		data = data.split(NL);
		z = sz(data);
		ls = [];
		for (i = 0; i < z; i += 3) {
			ico = data[i];
			txt = data[i + 1];
			lst = data[i + 2];
			if (ico && txt && lst) {
				ls.push(this.createItem(itemTpl, ico, txt, lst));
			}
		}
		s = s.replace('{items}', ls.join(NL));
		return s;
	},
	
	createItem:function(itemTpl, ico, txt, lst) {
		var s = itemTpl;
		if (lst != 'NS') {
			s = s.replace("{onclick}", ' onclick="' + lst + '"');
		} else {
			s = s.replace("{onclick}", '');
		}
		
		if (ico != 'NS') {
			s = s.replace("{ico}", "<img src=\"" + ico + "\">");
		} else {
			s = s.replace("{ico}", NS);
		}
		
		return s.replace("{txt}", txt);
	},
	
	getCurrentTarget:function(event) {
		var node = event.target;
		//alert("NTN = " + node.tagName);
		while(node.tagName != 'BODY') {
			//alert("No Body");
			if (node.getAttribute('data-cmid')) {
				//alert("cmid = " +  attr(node, 'data-cmid'));
				return node;
			}
			node = node.parentNode;
		}
		
		return node;
	}
};

window.onclick = function(event) {
	if((event.button || event.button == '0') && event.button == 0) {
		ContextMenuManager.hide();
	}
}


// For test

window.app = {
	folderContextMenu: {
		onClickOpen:function(){
			// alert('Will open ' + window.currentCmTargetId);
			ContextMenuManager.hide();
		}
	}
};
