class ConvertDlg{
	setListeners(n) {
		let parentDiv = e(window.dlgMgr.getIdPref() + n);
		// TODO set modal app controls e t c
		//parentDiv.getElementsByClasName("cancel-button")[0].onclick = (evt) => {this.onClickCancelCopyBtn(evt)};
	}
	getDlgBtns() {
		return "001";
	}
	
	getDefaultTitle() {
		return l("Add convert description");
	}
	
	getIcon() {
		return "/i/apps/hstor/d.png";
	}
	
	getUniqName() {
		return "ConvertListModalApp"; // unique name for taskbar (a-la CLSID Windows)
	} 
	
	getName() {
		return l("Add convert description"); // name for taskbar
	}
	// end interface
	
	html() {
		return `<div class="convertDlg xp">
			<div>
				<label for="convert_name" id="hConvertName">${l('hConvertName')}</label>
				<textarea id="convert_name" rows="7"></textarea>
			</div>
			<div>
				<label for="convert_color" id="hConvertColor">${l('hConvertColor')}</label>
				<input type="text"  id="convert_color">
			</div>
			<div class="buttons mb10">
				<img src="/i/apps/hstor/ld/s.gif" id="hCnvLdr">
				<input type="button" id="bConvertSave" value="${l('bSave')}">
			</div>
		</div>`;
	}
}
