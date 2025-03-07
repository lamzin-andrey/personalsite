class DiskBaseApp {
	constructor() {
		w.oPanel = new PanelPatch();
		w.dlgMgr = new DlgMgr(w.oPanel);
		w.br = '/p/hstor';
		this.initLists();
		this.setListeners();
		this.initRest();
	}
	initLists(){
		let d, i, z, o;
		try {
			d = JSON.parse(v("jsond"));
		}catch(err){
			return;
		};
		//console.log(d);
		this.zSetSel("container_id", d.containers);
		this.zSetSel("convert_id", d.converts);
	}
	zSetSel(id, ls) {
		let z, i;
		slAo(id, l("Not select"), -1);
		if(ls) {
			z = sz(ls);
			for (i = 0; i < z; i++) {
				slAo(id, ls[i].name, ls[i].id);
			}
		}
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
		e('bSave').onclick = () => {this.onClickSave()};
	}
	onClickSave(){
		let data = {};
		data["name"] = v("name");
		data["file_name"] = v("file_name");
		data["disk_name"] = v("disk_name");
		data["disk_name"] = v("disk_name");
		data["convert_id"] = v("convert_id");
		Rest2._post();
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
	
	defaultFail(d, responseText, info, xhr, readyState) {
		if (d instanceof Object) {
			if (d.status == "ok") {
				return true;
			}
			
			if (d.errors) {
				let i, a;
				a = []
				for (i in d.errors) {
					a.push(d.errors[i].replace('&laquo;', '"').replace('&raquo;', '"'));
				}
				this.showError(a.join("\n"));
				return false
			}
			
		}
		
		if (info) {
			this.showError(info);
		}
		return false;
	}
	
	showError(s){
		alert(s);
	}
}

w.addEventListener('load', () => {
	w.diskBaseApp = new DiskBaseApp();
});
