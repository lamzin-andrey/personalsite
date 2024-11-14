class CopyPasteApp {
	// interface
	setListeners(n) {
		let o = this;
		o.n = n;
		o.p = e(window.dlgMgr.getIdPref() + n);
		o.zAddE("progressStateLabel");
		o.zAddE("dompb");
		o.zAddE("hSrcSz");
		o.zAddE("hDestSz");
		o.zAddE("hFrom");
	}
	
	getDlgBtns() {
		return "000";
	}
	
	getDefaultTitle() {
		return L("Copying");
	}
	
	getIcon() {
		return "/d/drive/pc/i/folder32.png";
	}
	
	getUniqName() {
		return "WUSBPCopyPasteModalApp";
	}
	
	getName() {
		return L("Copying");
	}
	
	// /interface
	
	setCopyMessage(s) {
		console.log(s);
		let o = this;
		o.message = s;
		o.step = 1;
		o.it = 1;
		o.isFin = 0;
		o.ival = setInterval(() => {o.zOnTick()}, 1000);
	}
	
	finalize() {
		let o = this;
		if (o.ival) {
			clearInterval(o.ival);
		}
		o.isFin = 1;
		o.step = 2;
		o.ival = setInterval(() => {o.zOnTick()}, 100);
	}
	
	zOnTick() {
		let o = this, SZ, i, a, cw;
		a = o.message.split("\n");
		SZ = sz(a);
		o.it += o.step;
		if (o.it > SZ) {
			o.it = 1;
		}
		
		
		v(o.progressStateLabel, a[o.it]);
		cw = intval(o.dompb.style.width) + o.step;
		cw = cw < 101 ? cw : 100;
		o.dompb.style.width = cw + "%";
		
		v(o.hSrcSz, o.it);
		v(o.hDestSz, SZ);
		v(o.hFrom, "из");
		
		if (o.isFin && cw == 100) {
			window.dlgMgr.close(o.n);
		}
	}
	
	zAddE(s) {
		this[s] = cs(this.p, s)[0];
	}
}
