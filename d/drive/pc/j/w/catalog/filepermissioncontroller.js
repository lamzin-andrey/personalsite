window.FPC = {
	init(silent) {
		let bSave = fmgr.dlgProp.bSavePermScr,
			d = fmgr.dlgProp,
			i, o = this;
		o.silent = silent;
		
		v(d.hPrivateCaseLabel, L("Only for me"));
		v(d.hPublicCaseLabel, L("For all"));
		v(d.hCustomCaseLabel, L("For users") + ":");
		attr(fmgr.dlgProp.srchuser, "placeholder", L("Type user login and search"));
		
		Rest2._get(o.onLinkData, `${br}/drivegetfileprm.json?i=${d.id}`, o.onFailGetLinkData, o);
		bSave.onclick = () => {window.FPC.onClickSave(bSave);}
		d.bCopy.onclick = () => {window.FPC.onClickCopy();}
		
	},
	
	onClickCopy(){
		var b = fmgr.dlgProp.bCopy, W = 22, cw = W, hh, dr = -1;
		try {
			navigator.clipboard.writeText(attr(fmgr.dlgProp.clink, "href"));
			stl(b, "width", W + "px");
			stl(b, "height", "22px");
			hh = setInterval(() => {
				cw += dr;
				if (cw < 0) {
					dr *= -1;
					cw = 1;
					attr(b, "src", root + "/i/success.png");
				} 
				if (cw > W) {
					stl(b, "width", W + "px");
					clearInterval(hh);
					setTimeout(() => {attr(b, "src", root + "/i/copy.png");}, 1000);
				}
				stl(b, "width", cw + "px")
				//attr(b, "src", root + "/i/copy.png");
			}, 1000/72);
		} catch(err) {
			showError(L("Your browser does not support copying. But you can click link and copy adress line in opened tab."));
		}
	},
	
	onLinkData(data) {
		let o = this, d = fmgr.dlgProp;
		if (!o.onFailGetLinkData(data)) {
			showError(L("Error get file information"));
			d.noHavePermData = 1;
			return;
		}
		ShareLinkSetter.setLink(data.flink);
		d[data.shareMode].checked = true;
		o.renderUsers(data.uls, data.shareMode);
		if (o.silent) {
			d.showPerm();
		}
	},
	onFailGetLinkData(data, rt, inf, xhr) {
		return defaultResponseError(data, rt, inf, xhr);
	},
	onClickSave(bSave) {
		let perm, o;
		o = this;
		perm = fmgr.dlgProp.bFPPrivate.checked ? 0 : 1
		if (fmgr.dlgProp.bFPCustom.checked) {
			perm = 0;
		}
		addClass(bSave, "ac");
		o.showLoader();
		Rest2._post({i:fmgr.dlgProp.id, p:perm}, o.onSuccessSaveFilePrm,
			br + '/drivesavefileprm.json',
			o.onFailSaveFilePrm, o);
	},
	onSuccessSaveFilePrm(data){
		this.onFailSaveFilePrm(data);
	},
	onFailSaveFilePrm(data, responseText, info, xhr){
		hide(fmgr.dlgProp.ldrw2);
		removeClass(fmgr.dlgProp.bSavePermScr, "ac");
		return defaultResponseError(data, responseText, info, xhr);
	},
	rm(evt, uid, fileId){
		let trg, o = this;
		trg = ctrg(evt);
		ee(trg, "img")[0].src = root + "/i/ld/s.gif";
		evt.preventDefault();
		uid = uid.split('-')[0];
		Rest2._post({i:uid, f:fileId}, o.onSuccessRmFileUser,
			br + '/drivermfileprm.json',
			o.onFailRmFileUser, o);
		return false;
	},
	onSuccessRmFileUser(data){
		let o = this;
		if (o.onFailRmFileUser(data)) {
			o = e('usr' + data.id + '-' + PropsDlg.I);
			o ? rm(o) : 0;
		}
	},
	onFailRmFileUser(data, responseText, info, xhr){
		return defaultResponseError(data, responseText, info, xhr);
	},
	showLoader() {
		show(fmgr.dlgProp.ldrw2, 'inline-block');
	},
	userViewTpl() {
		return '<div class="userCardSm" id="usr{id}">\
						<span class="userCardSmAvatar">\
							<img src="{root}/i/usr.png">\
						</span>\
						<span class="userCardSmNick">{login}</span>\
						<span class="userCardSmAvatarAddBtn tc" onclick="FPC.rm(window.event, \'{id}\', {fid})">\
							<img src="{root}/i/exit.png" class="rmu" >\
						</span>\
						<div class="cf"></div>\
					</div>';
	},
	renderUsers(ls, mode){
		let o = this, i, SZ, tpl = o.userViewTpl(), s,
			dlg = fmgr.dlgProp,
			cont = dlg.customUsersWrapper;
		
		SZ = sz(ls);
		cont.innerHTML = '';
		for (i = 0; i < SZ; i++) {
			if (mode != "bFPPublic") {
				dlg["bFPCustom"].checked = true;
			}
			s = str_replace('{id}', ls[i].id + '-' + PropsDlg.I, tpl);
			s = str_replace('{login}', ls[i].login, s);
			s = str_replace('{fid}', dlg.id, s);
			s = str_replace('{root}', W.roota2, s);
			cont.innerHTML += s;
		}
	}
};
