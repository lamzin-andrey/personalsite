function ButtonAddress(){
	this.className = "ButtonAddress";
	this.prevPath = "";
	this.currentPath = "";
	this.buttonsData = [];
	this.buttonsDataForRender = [];
	this.placer = e('addressButtonPlacer');
	this.activeButtonClass = "addressButtonActive";
	this.stack = [];
	this.currentId = 0;
}
ButtonAddress.prototype.setPath = function(s, id){
	this.push(id);
	this.currentPath = s;
	this.currentId = id;
	this.buttonsData = this.createButtonsData(s);
	this.render();
}
ButtonAddress.prototype.push = function(id){
	var i, SZ, o = this;
	if (isU(id)) {
		return;
	}
	SZ = sz(o.stack);
	if (SZ == 0) {
		o.stack.push(id);
		return;
	}
	for (i = 0; i < SZ; i++) {
		if (o.stack[i] == o.currentId) {
			o.stack[i + 1] = id;
			o.stack.splice(i + 2);
			break;
		}
	}
	
	
}
// TODO also call it on resize
ButtonAddress.prototype.render = function(){
	var buttonsWidth, placeWidth, n = 0, 
		leftButton = this.createLeftButton(),
		rightButton = this.createRightButton();
	placeWidth = this.setPlacerWidth();
	this.buttonsDataForRender = mclone(this.buttonsData);
	buttonsWidth = this.renderButtonsData(); // TODO with margin 4.5 здесь не надо рендерить (все), надо рендерить одну и считать
	while (buttonsWidth > placeWidth && sz(this.buttonsDataForRender) > 3) {
		n++;
		this.buttonsDataForRender = mclone(this.buttonsData);
		this.trimLeft(this.buttonsDataForRender, n);
		array_unshift(this.buttonsDataForRender, leftButton);
		this.buttonsDataForRender.push(rightButton);
		buttonsWidth = this.renderButtonsData();
	}
	this.nDisplayedButtons = sz(this.buttonsData) - n;
	this.nRightButton = sz(this.buttonsData);
	this.renderFinal();
}
// 

ButtonAddress.prototype.renderButtonsData = function(noAll){
	var a = this.buttonsDataForRender, i, SZ = sz(a), j,
		tpl = '{img}{name}', s,
		parent = e('addressButtonPlacer'),
		btn, w = 0, o = this;
	if (!parent) {
		return;
	}
	parent.innerHTML = "";
	for (i = 0, j = this.nRightButton - this.nDisplayedButtons; i < SZ; i++, j++) {
		s = tpl.replace("{name}", this.renderButtonName(a[i].name));
		s = s.replace("{img}", this.renderButtonImage(a[i].imgSrc, a[i].imgClass)); // TODO
		if (!noAll) {
			parent.innerHTML = "";
		}
		
		btn = appendChild(parent, "div", s, {
			"class": "addressButton" + (a[i].addClass ? (' ' + a[i].addClass) : ''),
			"id": ("tb" + j),
			"title" : (a[i].title ? (' ' + a[i].title) : ''),
			"data-fid": (a[i].fid ? a[i].fid : 0)
		});
		if (a[i].isLeftButton) {
			btn.onclick = function(evt){
				return o.onClickLeftButton(evt);
			}
		} else if (a[i].isRightButton) {
			btn.onclick = function(evt){
				return o.onClickRightButton(evt);
			}
		} else {
			btn.onclick = function(evt){
				return o.onClickButton(evt);
			}
		}
		w += btn.offsetWidth + 4.5;
	}
	
	return w;
}

ButtonAddress.prototype.renderFinal = function(){
	this.renderButtonsData(true);
}

ButtonAddress.prototype.renderButtonImage = function(imgSrc, className) {
	if (!imgSrc) {
		return '';
	}
	var s = '<img src="' + imgSrc + '"{cn}>';
	if (className) {
		s = s.replace("{cn}", " class=\"" + className + '"');
	} else {
	s = s.replace("{cn}", '');
	}
	
	return s;
}

ButtonAddress.prototype.renderButtonName = function(name) {
	if (!name) {
		return '';
	}
	return '<span>' + name + '</span>';
}

ButtonAddress.prototype.createLeftButton = function() {
	var o = {
		name: '',
		path: '',
		addClass: 'addressButtonLeft',
		imgClass: 'addressButtonLeftIcon',
		imgSrc: App.dir() + '/i/addressButtonLeft.png',
		isLeftButton: true
	};
	
	return o;
}

ButtonAddress.prototype.createRightButton = function() {
	var o = {
		name: '',
		path: '',
		addClass: 'addressButtonRight',
		imgClass: 'addressButtonRightIcon',
		imgSrc: App.dir() + '/i/addressButtonRight.png',
		isRightButton: true
	};
	
	return o;
}

ButtonAddress.prototype.onClickRightButton = function(evt) {
	var i, start, end;
	this.nRightButton++;
	this.buttonsDataForRender = [];
	if (this.nRightButton >= sz(this.buttonsData)) {
		this.nRightButton = sz(this.buttonsData) - 1;
	}
	start = this.nRightButton - this.nDisplayedButtons + 1;
	end = start + this.nDisplayedButtons;
	this.buttonsDataForRender.push(this.createLeftButton());
	// alert(start + ', ' + end);
	for (i = start; i < end; i++) {
		if (this.buttonsData[i].path == app.tab.currentPath) {
			this.buttonsData[i].addClass = this.activeButtonClass;
		} else {
			this.buttonsData[i].addClass = '';
		}
		this.buttonsDataForRender.push(this.buttonsData[i]);
	}
	this.buttonsDataForRender.push(this.createRightButton());
	this.renderFinal();
}

ButtonAddress.prototype.onClickLeftButton = function(evt) {
	var i, start, end;
	
	this.buttonsDataForRender = [];
	if (this.nRightButton < this.nDisplayedButtons) {
		this.nRightButton = this.nDisplayedButtons;
	}
	start = this.nRightButton - this.nDisplayedButtons;
	end = start + this.nDisplayedButtons;
	this.buttonsDataForRender.push(this.createLeftButton());
	// alert(start + ', ' + end);
	for (i = start; i < end; i++) {
		if (this.buttonsData[i].path == app.tab.currentPath) {
			this.buttonsData[i].addClass = this.activeButtonClass;
		} else {
			this.buttonsData[i].addClass = '';
		}
		this.buttonsDataForRender.push(this.buttonsData[i]);
	}
	this.buttonsDataForRender.push(this.createRightButton());
	this.nRightButton--;
	this.renderFinal();
}

ButtonAddress.prototype.onClickButton = function(evt) {
	var o = this, trg = ctrg(evt), id, path, i, SZ = sz(o.buttonsData) + 2,
		fid = attr(trg, "data-fid");
	id = trg.id.replace("tb", '');
	path = o.buttonsData[id].path;
	
	for (i = 0; i < SZ; i++) {
		if (e("tb" + i)) {
			removeClass(e("tb" + i), o.activeButtonClass);
		}
	}
	addClass(trg, o.activeButtonClass);
	if (path) {
		o.currentId = fid;
		window.app.setActivePath(path, ['addresspanel'], fid);
		SZ = sz(o.buttonsData);
		for (i = 0; i < SZ; i++) {
			if (o.buttonsData[i].fid == fid) {
				trg  = "addressButtonActive";
			} else {
				trg  = "";
			}
			o.buttonsData[i].addClass  = trg;
		}
	}
}

ButtonAddress.prototype.createButtonsData = function(s){
	var a, i, SZ, r = [], item, p = [],
		j, stack, diffJ, cid;
	s = s.replace('//', '/');
	a = s.split("/");
	
	if (s == "/"){
		a.splice(1, 1);
	}
	SZ = sz(a);
	
	stack = mclone(this.stack);
	diffJ = SZ - sz(stack);
	
	for (i = 0; i < SZ; i++) {
		p.push(a[i]);
		cid = 0;
		if (i >= diffJ) {
			cid = stack[i - diffJ];
		}
		
		item = this.createButtonDataItem(a[i], p, cid);
		r.push(item);
	}
	
	return r;
}

ButtonAddress.prototype.createButtonDataItem = function(name, aPath, currentId){
	var o, path = '/', imgSrc = '', imgClass = '', title, addClass = '';
	
	path = aPath.join('/');
	if (name == USER) {
		title = L("Filesystem");
		path = USER;
		imgSrc = App.dir() + "/i/usb32.png";
		imgClass = "addressButtonIcon";
	} else {
		console.log(aPath, path);
		imgSrc = "";
		imgClass = "";
		title = name;
	}
	
	if (path == ('/home/' + USER)) {
		imgSrc = App.dir() + "/i/home32.png";
		imgClass = "addressButtonIcon";
	}
	console.log("activeBtn", window.app.tab.currentPath, path);
	if (path == window.app.tab.currentPath) {
		addClass = "addressButtonActive";
	}
	
	o = {
		name: name,
		title: title,
		path: path,
		addClass: addClass,
		imgClass: imgClass,
		imgSrc: imgSrc,
		fid: currentId
	};
	
	return o;
}

ButtonAddress.prototype.setPlacerWidth = function(){
	// return getViewport().w - e('sidebarWrapper').offsetWidth; 
	return e('addressButtonPlacer') ? e('addressButtonPlacer').offsetWidth : 0;
}
ButtonAddress.prototype.trimLeft = function(a, n){
	var i;
	for (i = 0; i < n; i++) {
		a.splice(0, 1);
	}
}
ButtonAddress.prototype.show = function() {
	this.placer.style.display = 'block';
	this.render();
}
ButtonAddress.prototype.hide = function() {
	this.placer.style.display = 'none';
}
