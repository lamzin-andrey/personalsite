class CDlg {
	constructor() {
		this.init();
	}
	
	init(n) {
		let o = this;
		o.wb = "001"; // min max close buttons. Set "111" for show it all
		o.title = "Default title"; // L - is translate function
		o.ico = "/d/drive/pc/i/exec32.png"; // modal window icon
		o.name = "Logger0"; // name for taskbar
		o.uniqName = "uniqueName";
	}
	
	setListeners(n) {
		this.parentDiv = e(window.dlgMgr.getIdPref() + n);
		this.n = n;
	}
	
	getDlgBtns() {
		return this.wb;
	}
	
	getDefaultTitle() {
		return this.title;
	}
	
	getIcon() {
		return this.ico;
	}
	getUniqName() {
		return this.uniqName; // unique name for taskbar (a-la CLSID Windows)
	}
	getName() {
		return this.name;
	}
	
	html(s) {
		return '<div class="loggerapp"><textarea></textarea></div>';
	}
	
	e(cn) {
		if (this.parentDiv){
			return cs(this.parentDiv, cn)[0];
		}
	}
	
	setTitle(s) {
		let h = e("LandDlgMgr" + this.n);
		console.log(this.n);
		let t = cs(h, "title")[0];
		v(t, s);
		this.title = s;
	}
}

//extend(CDlg, Dlg);
//Dlg.uniqName = "PlayOSLoggerModalApp";
//W.iapps[Dlg.uniqName] = Dlg;
