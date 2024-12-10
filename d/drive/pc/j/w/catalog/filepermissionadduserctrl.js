window.AddFileUser = {
	init() {
		let o = this, dlg = fmgr.dlgProp;
		dlg.srchuser.oninput = () => {
			setTimeout(
				() => {
					o.onInput();
				},
				100
			);
		}
	},
	onInput() {
		let s = fmgr.dlgProp.srchuser.value;
		if (sz(s) > 2) {
			this.searchUsers(s);
		}
	},
	searchUsers(s){
		let o = this;
		Rest2._post({i:fmgr.dlgProp.id, s:s}, o.onSuccessGetUsers,
			br + '/drivesrchusr.json',
			o.onFailGetUsers, o
		);
	},
	onSuccessGetUsers(data){
		let o = this, i, SZ, tpl = o.userViewTpl(), s,
			dlg = fmgr.dlgProp,
			cont = dlg.customUsersSearchWrapper;
		if (o.onFailGetUsers(data)) {
			SZ = sz(data.ls);
			cont.innerHTML = '';
			for (i = 0; i < SZ; i++) {
				s = str_replace('{id}', data.ls[i].id + '-' + PropsDlg.I, tpl);
				s = str_replace('{login}', data.ls[i].login, s);
				s = str_replace('{fid}', dlg.id, s);
				s = str_replace('{root}', roota2, s);
				cont.innerHTML += s;
			}
		}
	},
	onFailGetUsers(data, responseText, info, xhr)
	{
		return defaultResponseError(data, responseText, info, xhr);
	},
	
	add(evt, userId, fileId){
		let o = this, trg = ctrg(evt), s;
		userId = userId.split('-')[0];
		ee(trg, "img")[0].src = root + "/i/ld/s.gif";
		o.cAppendUserId = userId;
		s = attr(trg, "onclick");
		attr(trg, "onclick", s.replace("AddFileUser.add", "FPC.rm"));
		Rest2._post({i:fileId, u:userId}, o.onSuccessAddUsers,
			br + '/driveaddfileusr.json',
			o.onFailAddUsers, o
		);
	},
	onSuccessAddUsers(data){
		let o = this, i, d = fmgr.dlgProp, s;
		if (o.onFailAddUsers(data)) {
			i = e('usr' + o.cAppendUserId + '-' + PropsDlg.I);
			if (i) {
				ee(i, "img")[1].src = roota2 + "/i/exit.png";
				s = attr(ee(i, "span")[2], "onclick");
				attr(ee(i, "span")[1], "onclick", s.replace("FPC.rm", "AddFileUser.add"));
				d.customUsersWrapper.appendChild(i);
				i.parentNode = d.customUsersWrapper;
			}
		}
	},
	onFailAddUsers(data, responseText, info, xhr)
	{
		let r = defaultResponseError(data, responseText, info, xhr), i;
		if (!r) {
			i = e('usr' + this.cAppendUserId + '-' + PropsDlg.I);
			if (i) {
				ee(i, "img")[1].src = roota2 + "/i/+.png";
				i.parentNode = d.customUsersSearchWrapper;
			}
		}
		
		return r;
	},
	userViewTpl() {
		return '<div class="userCardSm" id="usr{id}">\
						<span class="userCardSmAvatar">\
							<img src="{root}/i/usr.png">\
						</span>\
						<span class="userCardSmNick">{login}</span>\
						<span class="userCardSmAvatarAddBtn tc" onclick="AddFileUser.add(window.event, \'{id}\', {fid})">\
							<img src="{root}/i/+.png" class="rmu" >\
						</span>\
						<div class="cf"></div>\
					</div>';
	},
	
	showLoader() {
		'here show small loader for add';
	}
	
};


