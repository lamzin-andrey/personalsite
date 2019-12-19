/***
 * Попытка сделать совместимость bootstrap 4 (flex) в android 2 browser
 * 
 * Попытка сделать d-flex .justify-content-center в android 2 browser
 * 
 * @depends jquery
*/


function B4Shim() {
	var o = this;
	window.onresize = function(){
		o.initDFlex();
	};
	o.initDFlex();
	o.initCollapse();
}
/***
 * @description Реализация collapse
*/
B4Shim.prototype.initCollapse = function() {
	var self = this, lsDivs = $('.collapse'), i, lsA = $('a[data-toggle=collapse], button[data-toggle=collapse], input[data-toggle=collapse]'), j, id, attrName;
	for (i = 0; i < lsA.length; i++) {
		id = this.getCollapseId(lsA[i]);
		if (id) {
			//$(id).css('display', 'none');
			$(id).hide();
			$(lsA[i]).click(function(evt){ self.onClickCollapseElement(evt); });
		}
	}
}
/**
 * @description Получить идентификатор элемента collapse
 * @param {HtmlElement} o
 * @return String
*/
B4Shim.prototype.getCollapseId = function(o) {
	var attrName = 'href', r;
	if (o.tagName != 'A') {
		attrName = 'data-target';
	}
	r = $(o).attr(attrName);
	r = r ? r : '';
	return r;
}
/**
 * @description Клик на элемнте collapse
*/
B4Shim.prototype.onClickCollapseElement = function(evt) {
	evt.preventDefault();
	var id = this.getCollapseId(evt.target),
		elTarget = $(id),
		ds = 'display', n = 'none', f = 'slideUp', md = 'slow';
		
	if (elTarget[0]) {
		
		c = elTarget.css(ds);
		if (c == n) {
			f = 'slideDown';
		}
		elTarget[f](md);
	}
	return false;
}
/***
 * @description Применяет стили, похожие на flex к элементам с соответствующими bootstrap 4 классами 
*/
B4Shim.prototype.initDFlex = function() {
	this.justifyContentCenter();
}
/***
 * Попытка сделать d-flex .justify-content-center в android 2 browser
 * В bootstrap4shim.css при этом d-flex определен как table-row а вложенные в него div первого уровня как table-cell
*/
B4Shim.prototype.justifyContentCenter = function() {
	var i, ls = $('.d-flex.justify-content-center');
	for (i = 0; i < ls.length; i++) {
		this._setJustifyContentCenter(ls[i]);
	}
}
/**
 * @see justifyContentCenter
 * @param {HtmlElement} block .d-flex.justify-content-center
*/
B4Shim.prototype._setJustifyContentCenter = function(block) {
	var nTargerWidth = screen.width - 30, i, ls, targetDiv, lst = [], tid = 'tmp-dfjcc';
	//Если в .d-flex.justify-content-center один вложенный div на первом уровне, то просто указать ему ширину screen.width - 30
	//иначе сначала переместить весь контент в новый div (ширинf screen.width - 30)
	//а новый div сделать потомком .d-flex.justify-content-center
	ls = $(block).find('> div');
	if (ls.length > 1) {
		targetDiv = document.createElement('div');
		for (i = 0; i < ls.length; i++) {
			targetDiv.appendChild(ls[i]);
		}
		$(targetDiv).addClass('d-flex');//.addClass('justify-content-center');
		tid = 'align-items-center'
		if ($(block).hasClass(tid)) {
			$(targetDiv).addClass(tid);
		}
		block.appendChild(targetDiv);
	} else if (ls.length == 1) {
		targetDiv = ls[0];
	}
	targetDiv.style.minWidth = nTargerWidth + 'px';
	
}

function runB4Shim(){
	new B4Shim();
}
$(runB4Shim);
