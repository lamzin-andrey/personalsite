class DlgMgr {
	constructor (panel) {
		this.panel = panel;
		this.maxZ = 1000;
		this.ls = [];
	}
	
	/**
	 * @property {Mixed} html
	 *           String html
	 *           String id for block with inner html
	 *           Array [context, method]
	 *           Object{context, method}
	 * 
	 * @property {Object} handler
	 * */
	create(html, handler) {
		let o = this, html, n = sz(o.ls);
		html = o.zHtml(html);
		o.ls.push(new DlgFrame(o, html, handler, n, o.maxZ));
		o.maxZ++;
		handler.setListeners(n);
		o.panel.add(n, handler); // handler.getIcon(), getUniqName, getName()
	}
	
	activate(n) {
		let i, currentZ, o = this, SZ = sz(o.ls);
		currentZ = o.ls[n].getCurrentZ();
		if (currentZ == o.maxZ) {
			return;
		}
		
		o.ls[n].setCurrentZ(o.maxZ);
		for (i = 0; i < SZ; i++) {
			if (o.ls[i].getCurrentZ() == o.maxZ) {
				o.ls[i].setCurrentZ(currentZ);
				break;
			}
		}
	}
	
	hide(n) {
		this.ls[n].hide();
		this.panel.hide(n);
	}
	
	close(n) {
		let o = this, trg = o.ls[n];
		o.activate(n);
		trg.destroy();
		o.maxZ--;
		this.panel.close(n);
	}
	
	show(n) {
		let o = this, trg = o.ls[n];
		trg.show();
		o.activate(n);
	}
	
	minimize(n) {
		this.hide(n);
	}
	
	maximize(n) {
		let o = this, trg = o.ls[n];
		trg.show();
		trg.maximize();
		o.activate(n);
	}
	
	zHtml(s) {
		let obj;
		if (typeof(s) == "string") {
			obj = e(s);
			if (obj && obj.innerHTML) {
				return obj.innerHTML;
			}
			
			return s;
		}
		
		if (s instanceof Function) {
			return s();
		}
		
		if (s instanceof Array) {
			return s[1].call(s[0]);
		}
		
		if (s instanceof Object) {
			return s.method.call(s.context);
		}
		
		return "";
	}
}
