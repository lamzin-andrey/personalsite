class ContainerDlg{
	setListeners(n) {
		let parentDiv = e(window.dlgMgr.getIdPref() + n);
		// TODO set modal app controls e t c
		//parentDiv.getElementsByClasName("cancel-button")[0].onclick = (evt) => {this.onClickCancelCopyBtn(evt)};
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
		return `<div class="convertDlg xp">
			<div>
				<label for="convert_name" id="hContainerName">${l('hContainerName')}</label>
				<textarea id="container_name" rows="7"></textarea>
			</div>
			<div>
				<label for="container_color" id="hContainerColor">${l('hContainerColor')}</label>
				<input type="text"  id="container_color">
			</div>
			<div class="buttons mb10">
				<img src="/i/apps/hstor/ld/s.gif" id="hContLdr">
				<input type="button" id="bContainerSave" value="${l('bSave')}">
			</div>
		</div>`;
	}
}
