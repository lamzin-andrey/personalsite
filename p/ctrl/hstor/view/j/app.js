class DiskBaseApp {
	constructor() {
		w.oPanel = new PanelPatch();
		w.dlgMgr = new DlgMgr(w.oPanel);
		this.setListeners();
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
}

w.addEventListener('load', () => {
	w.diskBaseApp = new DiskBaseApp();
});
