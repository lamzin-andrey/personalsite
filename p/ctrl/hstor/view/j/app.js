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
		let o = this;
		e('bAddConvert').onclick = () => {o.onClickAddConvert()};
		e('bAddContainer').onclick = () => {o.onClickAddContainer()};
		e('bSave').onclick = () => {o.onClickSave()};
		e('bSearch').onclick = () => {o.onClickSearch()};
	}
	onClickSearch(){
		let o = this;
		df("fileSearch");
		Rest2._get(o.onSuccessSearch, `${br}/searchfile.jn?s=${v("searchWord")}`, o.onFailSearch, o);
	}
	onSuccessSearch(d){
		if (!this.onFailSearch(d)){
			return;
		}
		let i, z = sz(d.ls), r, tr, id = "searchResult", s;
		v(id, "");
		for (i = 0; i < z; i++) {
			r = d.ls[i];
			s = date('d.m.Y H:i', strtotime(r.save_date));
			tr = appendChild(id, 'tr', `<td>${r.file_name}</td><td>${r.disk_name}</td><td>${s}</td>`, {});
		}
	}
	onFailSearch(d, rText, info){
		ef("fileSearch");
		return this.defaultFail(d, rText, info);
	}
	onClickSave(){
		let data = {}, o = this;
		data["name"] = v("name");
		data["file_name"] = v("file_name");
		data["disk_name"] = v("disk_name");
		data["disk_name"] = v("disk_name");
		data["convert_id"] = v("convert_id");
		data["container_id"] = v("container_id");
		data["artists"] = v("artists");
		data["content_year"] = v("content_year");
		data["save_date"] = v("save_date");
		data["additional_info"] = v("additional_info");
		data["additional_info_2"] = v("additional_info_2");
		data["do_share"] = v("do_share");
		df("fileData");
		Rest2._post(data, o.onSuccessSave, `${br}/savefile.jn`, o.onFailSave, o);
	}
	onSuccessSave(d){
		if (!this.onFailSave(d)){
			return;
		}
		
	}
	onFailSave(d, rText, info){
		ef("fileData");
		return this.defaultFail(d, rText, info);
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
