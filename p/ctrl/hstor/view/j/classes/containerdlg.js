class ContainerDlg{
	setListeners(n) {
		let o, parentDiv, bSave;
		o = this;
		o.N = n;
		o.parentDiv = e(window.dlgMgr.getIdPref() + n);
		// TODO set modal app controls e t c
		//parentDiv.getElementsByClasName("cancel-button")[0].onclick = (evt) => {this.onClickCancelCopyBtn(evt)};
		o.bSave = o.e('bContainerSave');
		o.iName = o.e('container_color');
		o.iColor = o.e('container_name');
		o.hLoader = o.e('hContLdr');

		o.bSave.onclick = (ev) => {o.onClickSave(ev)};
	}
	getDlgBtns() {
		return "001";
	}
	
	getDefaultTitle() {
		return l("Add container description");
	}
	
	getIcon() {
		return "/i/apps/hstor/d.png";
	}
	
	getUniqName() {
		return "ContainerListModalApp"; // unique name for taskbar (a-la CLSID Windows)
	} 
	
	getName() {
		return l("Add container description"); // name for taskbar
	}
	// end interface
	
	html() {
		let s, n; 
		n = this.N;
		s =  `<div c="convertDlg xp">
			<div>
				<label for="convert_name${n}" >${l('hContainerName')}</label>
				<textarea id="container_name${n}" c="container_name" rows="7"></textarea>
			</div>
			<div>
				<label for="container_color${n}" id="hContainerColor">${l('hContainerColor')}</label>
				<input type="text"  c="container_color" id="container_color${n}">
			</div>
			<div class="buttons mb10">
				<img src="/i/apps/hstor/ld/s.gif" c="hContLdr">
				<input type="button" c="bContainerSave" value="${l('bSave')}">
			</div>
		</div>`;
		
		return str_replace('c="', 'class="', s);
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
		show(o.hLoader);
		Rest2._post(data, o.onSuccessSend, `${br}/savecont.jn`, o.onFailSend, o);
	}
	onSuccessSend(data) {
		w.dlgMgr.close(this.N);
	}
	onFailSend(status, responseText, info, xhr, readyState) {
		//hide(this.hContLdr);
		return w.diskBaseApp.defaultFail(status, responseText, info, xhr, readyState);
	}
}
