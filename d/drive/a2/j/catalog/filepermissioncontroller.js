window.FPC = {
	rm:function(uid, fileId){
		var o = this;
		showLoader();
		Rest._post({i:uid, f:fileId}, function(data){o.onSuccessRmFileUser(data);},
			br + '/drivermfileprm.json',
			function(data, responseText, info, xhr){o.onFailRmFileUser(data, responseText, info, xhr)});
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
