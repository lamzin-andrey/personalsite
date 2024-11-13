function CopyPaste(tab) {
	this.tab = tab;
	this.lastAction = "";
}
CopyPaste.prototype.copyAction = function(targetId) {
	this.copycutAction("cp");
}
CopyPaste.prototype.cutAction = function(targetId) {
	this.copycutAction("mv");
}

CopyPaste.prototype.copycutAction = function(cmd) {
	var i, items = this.tab.oSelectionItems,  r = [], id, el;
	for (i in items) {
		id = i.replace('f', '');
		r.push(this.tab.currentPath + '/' + this.tab.list[id].name);
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
	if (o.lastAction == "") {
		return;
	}
	
	if (o.tab.bufferSources) {
		o.message = o.lastAction + '\n' + o.tab.currentPath + '\n' + o.tab.bufferSources;
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
	
	if (!localStorage.getItem(key)) {
		o.stopTimer();
		// 1 Create handler
		app = new CopyPasteApp();
		// 2 Create window
		window.dlgMgr.create(o.getCopyPasteHtml(), app);
		// 3 put message to handler
		app.setCopyMessage(o.message); // TODO
		o.message = "";
	}
}

CopyPaste.prototype.stopTimer = function() {
	if (this.ival) {
		clearInterval(this.ival);
		this.ival = null;
	}
}

// TODO 
CopyPaste.prototype.getCopyPasteHtml = function() {
	return `<div class="dbgBg">Hello World, I Copy</div>`;
}
