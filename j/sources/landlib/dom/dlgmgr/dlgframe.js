class DlgFrame {
	constructor(dlgMgr, html, handler, n, z) {
		let o = this;
		o.destroyed = 0;
		o.dm = dlgMgr;
		o.app = handler;
		o.nId = n;
		o.z = z;
		o.createDlg(html);
		o.CAPTION_H = 27;
		o.isMax = 0;
	}

	createDlg(html) {
		let i, wBtns, s, o = this, w, x = "px";
		wBtns = o.app.getDlgBtns();
		s = o.zGetTpl();
		i = intval(wBtns.charAt(0));
		s = s.replace("{minBtn}", (i ? o.zGetMinBtnTpl() : ''));
		i = intval(wBtns.charAt(1));
		s = s.replace("{maxBtn}", (i ? o.zGetMaxBtnTpl() : ''));
		i = intval(wBtns.charAt(2));
		s = s.replace("{clsBtn}", (i ? o.zGetCloseBtnTpl() : ''));
		s = s.replace("{title}", o.app.getDefaultTitle());
		s = s.replace("{icon}", o.app.getIcon());
		s = str_replace("{did}", o.nId, s);
		s = s.replace("{content}", html);
		
		o.div = appendChild(bod(), "div", s, {
			"id": o.zid(),
			"class": "landDlgMgr"
		});
		o.div.style.zIndex = o.z;
		o.center();
		
		cs(o.div, 'bMin')[0] ? cs(o.div, 'bMin')[0].onclick = (evt) => {o.onClickMin(evt)} : 0;
		cs(o.div, 'bMax')[0] ? cs(o.div, 'bMax')[0].onclick = (evt) => {o.onClickMax(evt)} : 0;
		cs(o.div, 'bCls')[0] ? cs(o.div, 'bCls')[0].onclick = (evt) => {o.onClickClose(evt)} : 0;
		cs(o.div, 'topBrd')[0] ? cs(o.div, 'topBrd')[0].onmousedown = (evt) => {o.onMouseDown(evt)} : 0;
		cs(o.div, 'bod')[0] ? cs(o.div, 'bod')[0].addEventListener("mousedown", (evt) => {o.onMouseDownBod(evt)}) : 0;
	}
	
	center() {
		let w = getViewport(), o = this;
		o.div.style.top = (w.h - o.div.offsetHeight - o.CAPTION_H) / 2 + "px";
		o.div.style.left = (w.w - o.div.offsetWidth) / 2 + "px";
	}
	
	onMouseDown(ev) {
		this.dm.movedN = this.nId;
		this.dm.prevX = ev.pageX;
		this.dm.prevY = ev.pageY;
		this.dm.activate(this.nId);
	}
	
	onMouseDownBod(ev) {
		this.dm.activate(this.nId);
	}
	
	move(dx, dy) {
		let x, y, p = "px", o = this;
		x = intval(this.div.style.left);
		y = intval(this.div.style.top);
		this.div.style.left = (x + dx) + p;
		this.div.style.top = (y + dy) + p;
	}
	
	destroy() {
		this.destroyed = 1;
		this.hide();
	}
	
	onClickClose(ev) {
		ev.preventDefault();
		this.dm.close(this.nId);
	}
	
	setCurrentZ(z) {
		this.z = z;
		this.div.style.zIndex = this.z;
	}
	
	getCurrentZ() {
		return this.z;
	}
	
	show() {
		if (!this.destroyed) {
			show(this.zid());
		}
	}
	
	maximize() {
		let h, d, w = getViewport(), o = this, x = 'px';
		d = o.div;
		if (!o.isMax) {
			h = w.h - o.CAPTION_H;
			w = w.w;
			o.prevH = d.offsetHeight;
			o.prevW = d.offsetWidth;
			o.prevX = intval(d.style.left);
			o.prevY = intval(d.style.top);
			d.style.height = h + x;
			d.style.width = w + x;
			d.style.top = 0 + x;
			d.style.left = 0 + x;
			o.isMax = 1;
		} else {
			d.style.height = o.prevH + x;
			d.style.width = o.prevW + x;
			d.style.top = o.prevY + x;
			d.style.left = o.prevX + x;
			o.isMax = 0;
		}
		
	}
	
	onClickMax(ev) {
		ev.preventDefault();
		this.dm.maximize(this.nId);
	}
	
	onClickMin(ev) {
		ev.preventDefault();
		this.dm.minimize(this.nId);
	}
	
	hide() {
		hide(this.zid());
	}
	
	getIdPref(){
		return this.dm.getIdPref();
	}
	
	// private
	zGetTpl() {
		return `
			<div class="topBrd">
				<img src="{icon}">
				<div class="title">{title}</div>
				<div class="fr">
					{minBtn}
					{maxBtn}
					{clsBtn}
				</div>
				<div class="cf"></div>
			</div>
			<div class="bod">
			{content}
			</div>
		`;
	}
	
	zGetMinBtnTpl() {
		return "<img src=\"/i/dlgmgr/min.jpg\" class=\"bMin\">";
	}
	
	zGetMaxBtnTpl() {
		return `<img src="/i/dlgmgr/max.jpg" class="bMax">`;
	}
	
	zGetCloseBtnTpl() {
		return `<img src="/i/dlgmgr/cls.jpg" class="bCls">`;
	}
	
	zid() {
		return this.getIdPref() + this.nId;
	}
	
}
