function NavbarPanel() {
	var o = this;
	o.btnUp   = e('btnUp');
	o.btnBack = e('btnBack');
	o.btnFwd  = e('btnFwd');
	o.btnHome = e('btnHome');
	
	o.setDisabled(this.btnUp);
	o.setDisabled(this.btnBack);
	o.setDisabled(this.btnFwd);
	o.setDisabled(this.btnHome);
	
	o.history = [];
	o.historyIterator = -1;
	
	o.btnHome.onclick = function(evt) {
		o.onClickHome(evt);
	}
	o.btnUp.onclick = function(evt) {
		try  {
			o.onClickUp(evt);
		} catch(err) {
			alert(err);
		}	
	}
	o.btnFwd.onclick = function(evt) {
		o.onClickFwd(evt);
	}
	o.btnBack.onclick = function(evt) {
		try {
			o.onClickBack(evt);
		}catch(err) {
			alert(err);
		}
	}
}
NavbarPanel.prototype.setDisabled = function(img) {
	var s = img.src;
	console.log(s);
	if (s.indexOf('32d.png') == -1) {
		s = s.replace('32.png', '32d.png');
		attr(img, 'src', s);
		img.isDisabled = true;
	}
}
NavbarPanel.prototype.setEnabled = function(img) {
	var s = img.src;
	if (s.indexOf('32.png') == -1) {
		s = s.replace('32d.png', '32.png');
		attr(img, 'src', s);
		img.isDisabled = false;
	}
}
/**
 * Важно, никогда не вызывать здесь другие setPath
*/
NavbarPanel.prototype.setPath = function(path, fid) {
	var o = this, s = path, stack = mclone(app.addressPanel.buttonAddress.stack);
	if (sz(o.history) == 1 && path == USER && o.history[0][0] == USER) {
		return;
	}
	console.log("Set stack", stack);
	this.history.push([s, fid, stack]);
	this.historyIterator = sz(this.history) - 1;
	
	this.checkUpButtonState(s);
	this.checkHomeButtonState(s);
	this.checkHistoryNavButtonsState(s);
}
NavbarPanel.prototype.checkUpButtonState = function(path) {
	if (USER == path) {
		this.setDisabled(this.btnUp);
	} else {
		this.setEnabled(this.btnUp);
	}
}
NavbarPanel.prototype.checkHomeButtonState = function(path) {
	if (USER && path == '/home/' + USER) {
		this.setDisabled(this.btnHome);
	} else {
		this.setEnabled(this.btnHome);
	}
}

NavbarPanel.prototype.isHome = function(n) {
	return (n == 1 && this.history[0][0] == USER);
}

NavbarPanel.prototype.checkHistoryNavButtonsState = function(path) {
	var o = this, SZ = sz(o.history);
	if (SZ == 0 || o.isHome(SZ)) { // TODO
		o.setDisabled(o.btnBack);
		o.setDisabled(o.btnFwd);
	} else {
		if (o.historyIterator == SZ - 1) {
			o.setDisabled(o.btnFwd);
		} else {
			o.setEnabled(o.btnFwd);
		}
		if (o.historyIterator == 0) {
			o.setDisabled(o.btnBack);
		} else {
			o.setEnabled(o.btnBack);
		}
	}
}

NavbarPanel.prototype.onClickFwd = function(evt) {
	var n = this.historyIterator + 1, s, p;
	if (n <= sz(this.history) - 1) {
		this.historyIterator++;
		p = this.history[this.historyIterator];
		s = p[0];
		app.addressPanel.buttonAddress.stack = mclone(p[2]);
		app.setActivePath(s, ['navbarPanelManager'], p[1]);
		this.checkUpButtonState(s);
		this.checkHomeButtonState(s);
		this.checkHistoryNavButtonsState(s);
	}
}
NavbarPanel.prototype.onClickBack = function(evt) {
	var n = this.historyIterator - 1, s, p, fid;
	if (n > - 1) {
		this.historyIterator--;
		p = this.history[this.historyIterator];
		s = p[0];
		fid = p[1];
		app.addressPanel.buttonAddress.stack = mclone(p[2]);
		app.setActivePath(s, ['navbarPanelManager'], fid);
		this.checkUpButtonState(s);
		this.checkHomeButtonState(s);
		this.checkHistoryNavButtonsState(s);
	}
}

NavbarPanel.prototype.onClickUp = function(evt) {
	var p = this.history[this.historyIterator],
		s = p[0],
		fid = p[1],
		a;
	
	a = s.split('/');
	a.splice(sz(a) - 1, 1);
	if (sz(a) > 1) {
		s = a.join('/');
	} else {
		s = USER;
	}
	
	a = mclone(p[2]);
	fid = a.pop();
	app.addressPanel.buttonAddress.stack = mclone(a);
	app.setActivePath(s, [''], a.pop());
}

NavbarPanel.prototype.onClickHome = function(evt) {
	var path = '/home/' + window.USER;
	if (this.btnHome.isDisabled || !USER) {
		return true;
	}
	
	app.addressPanel.buttonAddress.stack = [];
	app.addressPanel.buttonAddress.stack[0] = 0;
	app.setActivePath(path, [''], 0);
}

NavbarPanel.prototype.clearHistory = function(s, fid, stack) {
	var o = this;
	this.actualizeView(s);
	
	o.history = [];
	o.history.push([s, fid, stack]);
	o.historyIterator = 0;
}

NavbarPanel.prototype.actualizeView = function(s) {
	var o = this;
	if (this.historyIterator == -1) {
		return;
	}
	if (!s) {
		s = this.history[this.historyIterator];
	}
	o.setDisabled(this.btnUp);
	o.setDisabled(this.btnBack);
	o.setDisabled(this.btnFwd);
	o.setDisabled(this.btnHome);
	
	if (s != '/home/' + USER) {
		o.setEnabled(this.btnHome);
	}
	if (s != '/') {
		o.setEnabled(this.btnUp);
	}
	
	if (this.historyIterator < sz(this.history) - 1) {
		o.setEnabled(this.btnFwd);
	}
	if (this.historyIterator > 0) {
		o.setEnabled(this.btnBack);
	}
}
q("kblistener");
