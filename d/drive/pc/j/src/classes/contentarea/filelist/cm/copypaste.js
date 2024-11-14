function CopyPaste(tab) {
	this.tab = tab;
	this.lastAction = "";
}
CopyPaste.prototype.cutAction = function(targetId) {
	this.copycutAction("mv");
}

CopyPaste.prototype.copycutAction = function(cmd) {
	var i, items = this.tab.oSelectionItems,  r = [], id, el, type;
	for (i in items) {
		id = i.replace('f', '');
		type = this.tab.list[id].src.type == 'c' ? "f" : "fi";
		r.push(this.tab.currentPath + '/' + this.tab.list[id].name + ',' + type + this.tab.list[id].id);
		if (cmd == "mv") {
			el = e(i);
			if (el) {
				stl(el, "opacity", 0.5);
				this.tab.cutItems.push(el);
			}
		}
	}
	
	if (sz(r)) {
		this.tab.bufferSources = r.join('\n');
	}
	
	this.lastAction = cmd;
}

CopyPaste.prototype.pasteAction = function(targetId) {
	var o = this;
	if (app.tab.currentFid == 0) {
		showError(L("Unable paste files in root. Select a catalog."));
		return;
	}
	if (o.lastAction == "") {
		return;
	}
	
	if (o.tab.bufferSources) {
		o.message = o.lastAction + '\n' + o.tab.currentPath + ',' + this.tab.currentFid + '\n' + o.tab.bufferSources;
		o.tab.bufferSources = "";
		o.ival = setInterval(function() {
			o.onTick();
		}, 500);
		o.onTick();
	}
}

CopyPaste.prototype.onTick = function() {
	var key = "copyProc", o = this, app;
	
	if (!o.message) {
		o.stopTimer();
		return;
	}
	
	if (!o[key]) {
		o.stopTimer();
		// 1 Create handler
		o.cpApp = new CopyPasteApp();
		// 2 Create dialog window
		o.dlgId = window.dlgMgr.create(o.getCopyPasteHtml(), o.cpApp);
		// 3 put message to handler
		o.cpApp.setCopyMessage(o.message);
		o[key] = 1;
		Rest2._post(o.converMvMessage(), o.onMoveFiles, window.br + "/drivemv.json", o.onFailMoveFiles, o);
		o.message = "";
	}
}

CopyPaste.prototype.converMvMessage = function() {
	var o = this, s = o.message, a = s.split("\n"),
		SZ = sz(a), r = {}, b = [];
	r.t = a[1].split(',')[1];
	for (i = 2; i < SZ; i++) {
		b.push(a[i].split(',')[1]);
	}
	r.ls = b.join(',');
	return r;
}

CopyPaste.prototype.unsetCopyProc = function(data) {
	this.copyProc = 0;
}

CopyPaste.prototype.onMoveFiles = function(data) {
	var o = this;
	if (!o.onFailMoveFiles(data)) {
		return;
	}
	this.cpApp.finalize(o, o.unsetCopyProc);
	this.tab.onFileList(data);
}

CopyPaste.prototype.onFailMoveFiles = function(data) {
	if (!data) {
		try {
			data = JSON.parse(responseText);
		}catch(err) {
			;
		}
	}
	
	if (data && data.status == 'ok') {
		return true;
	}
	
	if (data && data.status == 'error') {
		if (data.error) {
			showError(data.error);
		} else if (data.errors && (data.errors instanceof Array) ) {
			showError(data.errors.join("\n"));
		}
	}
	this.copyProc = 0;
	window.dlgMgr.close(this.dlgId);
	return false;
}
	
CopyPaste.prototype.stopTimer = function() {
	if (this.ival) {
		clearInterval(this.ival);
		this.ival = null;
	}
}


CopyPaste.prototype.getCopyPasteHtml = function() {
	return `<div class="ePb">
	  <div class="progressStateLabel" >&nbsp;</div>
	  <div class="pbarandtextwr">
		 <div class="pbarwrap">
			<div style="width:0%;" class="dompb">&nbsp;</div>
		 </div>
		 <span class="hSrcSz">2</span> <span class="hFrom">from</span> <span class="hDestSz">7</span> 
	  </div>
	</div>`;
}
