window.FPC = {
	init:function() {
		var cb = function() {
				window.FPC.onClickExit();
			},
			mainExit = e('bClosePermScr'),
			exLs = cs(D, 'pcaseRmBtn'),
			i,
			SZ = sz(exLs);
		for (i = 0; i < SZ; i++) {
			exLs[i].ontouchstart = cb;
		}
		mainExit.ontouchstart = cb;
		mainExit.onclick = cb;
		
		e('bAddFileUser').onclick = function(){
			window.FPC.onClickAddUser();
		}
	},
	onClickAddUser:function() {
		showScreen('hAddFileUser');
	},
	onClickExit:function() {
		var perm = e('bFPPrivate').checked ? 0 : 1, o = this;
		if (e('bFPCustom').checked) {
			perm = 0;
		}
		showLoader();
		Rest._post({i:fileListItemCmenu.cmMenuOpenItemId, p:perm}, function(data){o.onSuccessSaveFilePrm(data);},
			br + '/drivesavefileprm.json',
			function(data, responseText, info, xhr){o.onFailSaveFilePrm(data, responseText, info, xhr)});
	},
	onSuccessSaveFilePrm:function(data){
		this.onFailSaveFilePrm(data);
	},
	onFailSaveFilePrm:function(data, responseText, info, xhr)
	{
		showScreen('hCatalogScreen');
		return defaultResponseError(data, responseText, info, xhr);
	},
	rm:function(evt, uid, fileId){
		var o = this;
		evt.preventDefault();
		showLoader();
		Rest._post({i:uid, f:fileId}, function(data){o.onSuccessRmFileUser(data);},
			br + '/drivermfileprm.json',
			function(data, responseText, info, xhr){o.onFailRmFileUser(data, responseText, info, xhr)});
		return false;
	},
	onSuccessRmFileUser:function(data){
		var o;
		if (this.onFailRmFileUser(data)) {
			o = e('usr' + data.id);
			o ? rm(o) : 0;
		}
	},
	onFailRmFileUser:function(data, responseText, info, xhr)
	{
		showScreen('hFilePermission');
		return defaultResponseError(data, responseText, info, xhr);
	}
};

window.addEventListener('load', FPC.init, false);
