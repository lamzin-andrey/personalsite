Depends from 
https://github.com/lamzin-andrey/landlib/blob/master/dom/android-browser-2.3.6/micron.js
https://github.com/lamzin-andrey/php2jstranslator/blob/functions/php.js


Example:

```javascript

// This emulated visual process files copying dialog on my FileManager web page 
// 

// Class  - handler "application", opened in modal window
class CopyPasteApp {
	// interface
	setListeners(n) {
		let parentDiv = e(window.dlgMgr.getIdPref() + n);
		// TODO set modal app controls e t c
    //parentDiv.getElementsByClasName("cancel-button")[0].onclick = (evt) => {this.onClickCancelCopyBtn(evt)};
	}
	
	getDlgBtns() {
		return "000"; // min max close buttons. Set "111" for show it all
	}
	
	getDefaultTitle() {
		return L("Copying"); // L - is translate function
	}
	
	getIcon() {
		return "/d/drive/pc/i/folder32.png"; // modal window icon
	}
	
	getUniqName() {
		return "WUSBPCopyPasteModalApp"; // unique name for taskbar (a-la CLSID Windows)
	} 
	
	getName() {
		return L("Copying"); // name for taskbar
	}
	
	// /interface
	
	setCopyMessage(s) {
		// TODO app logic
	}
}

// In your based app...

function initApp() {
  // it function init my application
  // ... Here initalize application business logic
  

  // Here init DlgManager
  window.oPanel = new PanelPatch(); // For real panel implements class with interface PanelPatch
	window.dlgMgr = new DlgMgr(window.oPanel); // init DlgManager
}


// In deep my based app... I emiulate dialog files copying (for example)

onClickPasteFiles() {
  // 1 Create handler
		const copyingApp = new CopyPasteApp(); // It Handler
		// 2 Create "window"
		window.dlgMgr.create(o.getCopyPasteHtml(), copyingApp);
		// 3 put message to handler
		copyingApp.setCopyMessage(this.message); // TODO business logic (It will animation. progress bar e t c... For my case)
}

```
