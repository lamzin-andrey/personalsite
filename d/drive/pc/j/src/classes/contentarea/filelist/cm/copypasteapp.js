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
		let o = this;
		o.message = s;
		o.step = 1;
		o.it = 1;
		o.isFin = 0;
		o.ival = setInterval(() => {o.zOnTick()}, 1000);
	}
	
	finalize(context, method) {
		let o = this;
		o.finalCtx = context;
		o.finalMethod = method;
		if (o.ival) {
			clearInterval(o.ival);
		}
		o.isFin = 1;
		o.step = 4;
		o.ival = setInterval(() => {o.zOnTick()}, 100);
	}
	
	zOnTick() {
		let o = this, SZ, i, a, cw;
		a = o.message.split("\n");
		SZ = sz(a);
		o.it += 1;
		if (o.it >= SZ) {
			o.it = 1;
		}
		
		//v(o.progressStateLabel, a[o.it].split(',')[0]);
		o.zSetCurrentFileName(a[o.it].split(',')[0]);
		cw = intval(o.dompb.style.width) + o.step;
		cw = cw < 101 ? cw : 100;
		o.dompb.style.width = cw + "%";
		
		v(o.hSrcSz, o.it);
		v(o.hDestSz, SZ);
		v(o.hFrom, "из");
		
		if (o.isFin && cw == 100) {
			o.finalMethod.call(o.finalCtx);
			window.dlgMgr.close(o.n);
		}
	}
	
	zSetCurrentFileName(s){
		let i, q = '', SZ, a = [], j;
		s = String(s);
		SZ = sz(s);
		if (SZ < 50) {
			v(this.progressStateLabel, s);
			return;
		}
		for (i = SZ - 1, j = 0; i > -1; i--, j++) {
			a.push(s.charAt(i));
			if (j > 49) {
				break;
			}
		}
		a.reverse();
		s = a.join('');
		v(this.progressStateLabel, '...' + s);
	}
	
	zAddE(s) {
		this[s] = cs(this.p, s)[0];
	}
}
