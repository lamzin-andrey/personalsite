class ConvertDlg extends ContainerDlg{
	setListeners(n) {
		let o, parentDiv, bSave;
		o = this;
		o.N = n;
		o.parentDiv = e(window.dlgMgr.getIdPref() + n);
		o.bSave = o.e('bConvertSave');
		o.iName = o.e('convert_name');
		o.iColor = o.e('convert_color');
		o.hLoader = o.e('hCnvLdr');
		o.ctrl = "saveconv.jn";
		o.listId = "convert_id";
		o.listName = "converts";
		o.iId = o.e(o.listId);

		o.bSave.onclick = (ev) => {o.onClickSave(ev)};
	}
	
	getDefaultTitle() {
		return l("Add convert description");
	}
	
	
	getUniqName() {
		return "ConvertListModalApp"; // unique name for taskbar (a-la CLSID Windows)
	} 
	
	getName() {
		return l("Add convert description"); // name for taskbar
	}
	// end interface
	
	html() {
		
		let s, n; 
		n = this.N;
		s =  `<div c="convertDlg xp">
			<div>
				<label for="convert_name${n}" ><i>*</i> ${l('hConvertName')}</label>
				<textarea id="convert_name${n}" c="convert_name" rows="7"></textarea>
			</div>
			<div>
				<label for="convert_color${n}" id="hConvertColor"><i>*</i> ${l('hConvertColor')}</label>
				<input type="text"  c="convert_color" id="convert_color${n}">
				<input type="hidden"  c="convert_id" id="convert_id_${n}">
			</div>
			<div class="buttons mb10">
				<img src="/i/apps/hstor/ld/s.gif" c="hCnvLdr">
				<input type="button" c="bConvertSave" value="${l('bSave')}">
			</div>
		</div>`;
		
		return str_replace(' c="', ' class="', s);
	}
	setData(){
		let id, o, d;
		o = this;
		id = v(o.listId);
		v(o.iId, id);
		d = w.diskBaseApp.findById(id, w.diskBaseApp[o.listName]);
		if (!d) {
			v(o.iId, 0);
			return;
		}
		v(o.iName, d.name);
		v(o.iColor, d.color);
	}
}
