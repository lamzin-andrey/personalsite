class UploadViewDlg {
	// interface
	setListeners(n) {
		let o = this, fr = "из";
		o.n = n;
		o.p = e(window.dlgMgr.getIdPref() + n);
		o.zAddE("tpcsz");
		o.zAddE("from1");
		o.zAddE("from2");
		o.zAddE("from3");
		o.zAddE("tptsz");
		v(o.from1, fr);
		v(o.from2, fr);
		v(o.from3, fr);
		
		o.zAddE("cfcnt");
		o.zAddE("cftot");
		o.zAddE("fname");
		
		o.zAddE("cpcsz");
		o.zAddE("cptsz");
		
		o.zAddE("pb1");
		o.zAddE("pb2");
	}
	
	getDlgBtns() {
		return "000";
	}
	
	getDefaultTitle() {
		return L("Uploading");
	}
	
	getIcon() {
		return "/d/drive/a2/i/up.png";
	}
	
	getUniqName() {
		return "WUSBPUploadModal";
	}
	
	getName() {
		return L("Uploading");
	}
	
	// /interface
	
	setProgressValue(c, t, p) {
		let o = this;
		v(o.tpcsz, "(" + c);
		v(o.tptsz, t + ") " + p + '%');
		o.pb1.style.width = p + '%';
	}
	
	setTotalProgressValue(c, t, p) {
		let o = this;
		v(o.cpcsz, "(" + c);
		v(o.cptsz, t + ") " + p + '%');
		o.pb2.style.width = p + '%';
	}
	
	setFilename(s) {
		v(this.fname, s);
	}
	
	setXFromY(x, y){
		v(this.cfcnt, x);
		v(this.cftot, y);
	}
	
	reset() {
		v(this.fname, s);
		this.setProgressValue(0, 0, 0);
		this.setTotalProgressValue(0, 0, 0);
	}
	
	
	zAddE(s) {
		this[s] = cs(this.p, s)[0];
	}
}
