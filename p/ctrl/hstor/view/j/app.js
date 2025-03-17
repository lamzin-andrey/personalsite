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
		o = this;
		try {
			d = JSON.parse(v("jsond"));
		}catch(err){
			return;
		};
		//console.log(d);
		o.containers = d.containers;
		o.converts = d.converts;
		o.zSetSel("container_id", d.containers);
		o.zSetSel("convert_id", d.converts);
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
		
		e('bEditConvert').onclick = () => {o.onClickEditConvert()};
		e('bEditContainer').onclick = () => {o.onClickEditContainer()};
	}
	onClickSearch(){
		let o = this;
		o.mode = 1;
		df("fileSearch");
		Rest2._get(o.onSuccessSearch, `${br}/searchfile.jn?s=${v("searchWord")}`, o.onFailSearch, o);
	}
	onSuccessSearch(d){
		if (!this.onFailSearch(d)){
			return;
		}
		let i, z, r, tr, id, s, fileNameLnk, diskNameLnk, o;
		z = sz(d.ls);
		id = "searchResult";
		o = this;
		o.currentData = d;
		if (o.mode == 2) {
			show("hBackBtn", "inline-block");
			o.mode = 3;
		} else if (o.mode == 1){
			hide("hBackBtn");
		}
		v(id, "");
		for (i = 0; i < z; i++) {
			r = d.ls[i];
			s = date('d.m.Y H:i', strtotime(r.save_date));
			diskNameLnk = o.getDiskNameLnk(r);
			fileNameLnk = o.getFileNameLnk(r);
			tr = appendChild(id, 'tr', `<td>${fileNameLnk}</td><td>${diskNameLnk}</td><td>${s}</td>`, {});
		}
	}
	getDiskNameLnk(r) {
		let s = `<a href="#" onclick="return w.diskBaseApp.onClickDiskName('${r.id}')">${r.disk_name}</a>`;
		return s;
	}
	getFileNameLnk(r) {
		let s = `<a href="#" onclick="return w.diskBaseApp.onClickFileName('${r.id}')">${r.file_name}</a>`;
		return s;
	}
	findById(id, ls){
		let z, i, trg, c;
		z = sz(ls);
		for (i = 0; i < z; i++) {
			c = ls[i];
			if (c.id == id) {
				trg = c;
				break;
			}
		}
		return trg;
	}
	onClickFileName(id) {
		let i, z, trg, c, o;
		o = this;
		
		trg = o.findById(id, o.currentData.ls);
		
		if (!trg) {
			o.showError(l("File Not Found"));
			return;
		}
		console.log(trg);
		trg.container = o.findById(trg.container_id, o.containers);
		trg.convert = o.findById(trg.convert_id, o.converts);
		
		this.fileFullInfoDlg = new FileFullInfoDlg(); // It Handler
		this.fileFullInfoDlgId =  w.dlgMgr.create(this.fileFullInfoDlg.html(), this.fileFullInfoDlg);
		o.fileFullInfoDlg.setData(trg);
		dlgMgr.center(this.fileFullInfoDlgId);
		/*let o = this;
		df("fileSearch");
		
		if (o.mode != 3) {
			o.mode = 2;
			o.previousData = o.currentData;
		}
		Rest2._get(o.onSuccessSearch, `${br}/searchfile.jn?dn=${id}`, o.onFailSearch, o);
		return false;*/
	}
	onClickDiskName(id) {
		//alert("Will load! " + id);
		let o = this;
		df("fileSearch");
		
		if (o.mode != 3) {
			o.mode = 2;
			o.previousData = o.currentData;
		}
		Rest2._get(o.onSuccessSearch, `${br}/searchfile.jn?dn=${id}`, o.onFailSearch, o);
		return false;
	}
	onClickBackBtn() {
		let o = this;
		o.mode = 1;
		o.onSuccessSearch(o.previousData);
	}
	onFailSearch(d, rText, info){
		ef("fileSearch");
		return this.defaultFail(d, rText, info);
	}
	onClickSave(){
		let data = {}, o = this;
		data["id"] = v("id");
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
	onClickEditConvert() {
		this.converDlg = new ConvertDlg(); // It Handler
		this.convertDlgId =  w.dlgMgr.create(this.converDlg.html(), this.converDlg);
		w.dlgMgr.center(this.convertDlgId);
		this.converDlg.setData();
	}
	onClickAddContainer() {
		this.containerDlg = new ContainerDlg(); // It Handler
		this.containerDlgId =  w.dlgMgr.create(this.containerDlg.html(), this.containerDlg);
		w.dlgMgr.center(this.containerDlgId);
	}
	onClickEditContainer() {
		this.containerDlg = new ContainerDlg(); // It Handler
		this.containerDlgId =  w.dlgMgr.create(this.containerDlg.html(), this.containerDlg);
		w.dlgMgr.center(this.containerDlgId);
		this.containerDlg.setData();
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
