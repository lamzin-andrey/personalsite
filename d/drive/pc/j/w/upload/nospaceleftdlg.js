class NoSpaceLeftDlg {
	// interface
	setListeners(n) {
		let s, o = this;
		o.n = n;
		o.p = e(window.dlgMgr.getIdPref() + n);
		o.zAddE("title");
		o.zAddE("p0");
		o.zAddE("p1");
		o.zAddE("p2");
		o.zAddE("p3");
		o.zAddE("p4");
		o.zAddE("p5");
		o.zAddE("p6");
		o.zAddE("p7");
		o.zAddE("p8");
		o.zAddE("btns");
		ee(o.btns, "input")[0].onclick = (evt) => {o.onClickUnderstand();}
	}
	
	onClickUnderstand() {
		dlgMgr.close(this.n);
	}
	
	getDlgBtns() {
		return "001";
	}
	
	getDefaultTitle() {
		return L("On WebUSB no left space...");
	}
	
	getIcon() {
		return "/d/drive/a2/i/facesad.png";
		return "/d/drive/pc/i/folder32.png";
	}
	
	getUniqName() {
		return "WUSBPNoLegftSpaceModal";
	}
	
	getName() {
		return L("NoLeftSpaceDevice");
	}
	
	// /interface
	
	translate(freePD, freePW, freePM, freeP6M) {
		let o = this, s = "No left space on WebUSB";
		v(o.title, L(s));
		v(o.p0, L(s));
		v(o.p1, L("Try delete files."));
		v(o.p2, L("If you deleted files, but see it message, read more."));
		v(o.p3, L("According to Federal Law 374 of the Russian Federation, I must store all your files for at least 6 months."));
		v(o.p4, L("So even if you recently deleted a file and don't see it among your files, it's still taking up disk space."));
		v(o.p5, L("Will be available in 24 hours <span></span>"));
		v(o.p6, L("Will be free in a week <span></span>"));
		v(o.p7, L("Will be free in a month <span></span>"));
		v(o.p8, L("Will be released in six months <span></span>"));
		o.zS(5, freePD);
		o.zS(6, freePW);
		o.zS(7, freePM);
		o.zS(8, freeP6M);
		v(ee(o.btns, "input")[0], L("I understand"));
	}
	
	zS(n, m) {
		let p = cs(this.p, "p" + n)[0];
		v(ee(p, "span")[0], m);
	}
	
	zAddE(s) {
		this[s] = cs(this.p, s)[0];
	}
}
