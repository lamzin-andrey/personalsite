class FileFullInfoDlg{
	setListeners(n) {
		let o, parentDiv, bSave;
		o = this;
		o.N = n;
		o.parentDiv = e(window.dlgMgr.getIdPref() + n);
		/*o.bSave = o.e('bContainerSave');
		o.iName = o.e('container_name');
		o.iColor = o.e('container_color');
		o.hLoader = o.e('hContLdr');
		o.ctrl = "savecont.jn";
		o.listId = "container_id";

		o.bSave.onclick = (ev) => {o.onClickSave(ev)};*/
	}
	getDlgBtns() {
		return "001";
	}
	
	getDefaultTitle() {
		return l("Full File Data");
	}
	
	getIcon() {
		return "/i/apps/hstor/d.png";
	}
	
	getUniqName() {
		return "FFDModalApp"; // unique name for taskbar (a-la CLSID Windows)
	} 
	
	getName() {
		return l("File data"); // name for taskbar
	}
	// end interface
	
	html() {
		let s, n; 
		n = this.N;
		s =  `<div c="convertDlg xp">
			<div>
				<label for="container_name${n}" ><i>*</i> ${l('hContainerName')}</label>
				<textarea id="container_name${n}" c="container_name" rows="7"></textarea>
			</div>
			<div>
				<label for="container_color${n}" id="hContainerColor"><i>*</i> ${l('hContainerColor')}</label>
				<input type="text"  c="container_color" id="container_color${n}">
			</div>
			<div class="buttons mb10">
				<img src="/i/apps/hstor/ld/s.gif" c="hContLdr">
				<input type="button" c="bContainerSave" value="${l('bSave')}">
			</div>
		</div>`;
		
		return str_replace(' c="', ' class="', s);
	}
	e(i){
		return cs(this.parentDiv, i)[0];
	}
	onClickSave(ev){
		let data, o;
		o = this;
		data = {};
		data.name = v(o.iName);
		data.color = v(o.iColor);
		show(o.hLoader, 'inline-block');
		Rest2._post(data, o.onSuccessSend, `${br}/${o.ctrl}`, o.onFailSend, o);
	}
	onSuccessSend(data) {
		let o = this;
		if(o.onFailSend(data)) {
			
			slAo(o.listId, v(o.iName), data.id);
			w.dlgMgr.close(o.N);
		}
	}
	onFailSend(status, responseText, info, xhr, readyState) {
		hide(this.hLoader);
		return w.diskBaseApp.defaultFail(status, responseText, info, xhr, readyState);
	}
}
