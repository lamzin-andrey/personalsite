class DlgMgr {
	constructor (panel) {
		let o = this;
		o.panel = panel;
		o.maxZ = 1000;
		o.ls = [];
		o.movedN = -1;
		window.addEventListener("mousemove", (evt) => {o.onMouseMove(evt);});
		window.addEventListener("mouseup", (evt) => {o.onMouseUp(evt);});
	}
	
	onMouseMove(evt) {
		let o = this;
		if (o.movedN != -1) {
			o.ls[o.movedN].move(evt.pageX - o.prevX, evt.pageY - o.prevY);
			o.prevX = evt.pageX;
			o.prevY = evt.pageY;
		}
	}
	
	onMouseUp(evt) {
		console.log("UP!");
		this.movedN = -1;
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
		let o = this, n = sz(o.ls);
		html = o.zHtml(html);
		o.ls.push(new DlgFrame(o, html, handler, n, o.maxZ));
		o.maxZ++;
		handler.setListeners(n);
		o.panel.add(n, handler); // handler.getIcon(), getUniqName, getName()
		
		return n;
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
	
	setTitle(n, s) {
		this.ls[n].setTitle(s);
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
	
	getIdPref(){
		return "LandDlgMgr";
	}
}
