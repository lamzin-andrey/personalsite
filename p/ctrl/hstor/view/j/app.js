class DiskBaseApp {
	constructor() {
		w.oPanel = new PanelPatch();
		w.dlgMgr = new DlgMgr(w.oPanel);
		w.br = '/p/hstor';
		this.setListeners();
		this.initRest();
	}
	initRest(){
		let ls, t;
		eee(d, 'meta', (m) => {
			if (attr(m, "name") == "keys") {
				t = attr(m, "content");
			}
		})
		Rest2._setToken(t, "_token");
	}
	setListeners() {
		e('bAddConvert').onclick = () => {this.onClickAddConvert()};
		e('bAddContainer').onclick = () => {this.onClickAddContainer()};
	}
	onClickAddConvert() {
		this.converDlg = new ConvertDlg(); // It Handler
		this.convertDlgId =  w.dlgMgr.create(this.converDlg.html(), this.converDlg);
		w.dlgMgr.center(this.convertDlgId);
	}
	
	onClickAddContainer() {
		this.containerDlg = new ContainerDlg(); // It Handler
		this.containerDlgId =  w.dlgMgr.create(this.containerDlg.html(), this.containerDlg);
		w.dlgMgr.center(this.containerDlgId);
	}
	
	defaultFail(status, responseText, info, xhr, readyState) {
		//if (status)
		return true;
	}
}

w.addEventListener('load', () => {
	w.diskBaseApp = new DiskBaseApp();
});
