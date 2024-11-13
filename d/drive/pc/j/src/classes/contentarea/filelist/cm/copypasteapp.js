class CopyPasteApp {
	// interface
	setListeners(n) {
		let p = e(window.dlgMgr.getIdPref() + n);
		// TODO
	}
	
	getDlgBtns() {
		return "000";
	}
	
	getDefaultTitle() {
		return L("Copying");
	}
	
	getIcon() {
		return "/d/drive/pc/i/folder32.png";
	}
	
	getUniqName() {
		return "WUSBPCopyPasteModalApp";
	}
	
	getName() {
		return L("Copying");
	}
	
	// /interface
	
	setCopyMessage(s) {
		// TODO
	}
}
