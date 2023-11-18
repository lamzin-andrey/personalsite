window.AddFileUser = {
	init:function() {
		var o = this;
		e('srchuser').oninput = function(){
			setTimeout(
				function() {
					o.onInput();
				},
				100
			);
		}
		e('bCloseAddFileUserScr').onclick = function(){
			o.onClose();
		}
	},
	onClose() {
		showScreen('hFilePermission');
	},
	onInput() {
		var s = e('srchuser').value;
		if (sz(s) > 2) {
			this.searchUsers(s);
		}
	},
	searchUsers:function(s){
		var o = this;
		Rest._post({i:fileListItemCmenu.cmMenuOpenItemId, s:s}, function(data){o.onSuccessGetUsers(data);},
			br + '/drivesrchusr.json',
			function(data, responseText, info, xhr){o.onFailGetUsers(data, responseText, info, xhr)});
	},
	onSuccessGetUsers:function(data){
		var o = this, i, SZ, tpl = this.userViewTpl(), s,
			cont = e('customUsersSearchWrapper');
		if (o.onFailGetUsers(data)) {
			SZ = sz(data.ls);
			cont.innerHTML = '';
			for (i = 0; i < SZ; i++) {
				s = str_replace('{id}', data.ls[i].id, tpl);
				s = str_replace('{login}', data.ls[i].login, s);
				s = str_replace('{fid}', fileListItemCmenu.cmMenuOpenItemId, s);
				s = str_replace('{root}', W.roota2, s);
				cont.innerHTML += s;
			}
		}
	},
	onFailGetUsers:function(data, responseText, info, xhr)
	{
		return defaultResponseError(data, responseText, info, xhr);
	},
	add:function(userId, fileId){
		var o = this;
		showLoader();
		Rest._post({i:fileId, u:userId}, function(data){o.onSuccessAddUsers(data);},
			br + '/driveaddfileusr.json',
			function(data, responseText, info, xhr){o.onFailAddUsers(data, responseText, info, xhr)});
	},
	onSuccessAddUsers:function(data){
		var o = this, i, SZ;
		if (o.onFailAddUsers(data)) {
			W.fileListItemCmenu.onClickShareLink();
		}
	},
	onFailAddUsers:function(data, responseText, info, xhr)
	{
		showScreen('hAddFileUser');
		return defaultResponseError(data, responseText, info, xhr);
	},
	userViewTpl:function() {
		return '<div class="userCardSm" id="usr{id}">\
						<span class="userCardSmAvatar">\
							<img src="{root}/i/usr.png">\
						</span>\
						<span class="userCardSmNick">{login}</span>\
						<span class="userCardSmAvatarAddBtn tc" onclick="AddFileUser.add({id}, {fid})">\
							<img src="{root}/i/+.png" class="rmu" >\
						</span>\
						<div class="cl"></div>\
					</div>';
	},
	
};


window.addEventListener('load', function (){
	window.AddFileUser.init();
}, false);
