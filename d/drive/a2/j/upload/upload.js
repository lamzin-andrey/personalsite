window.upload = {
	init:function() {
		var o = this,
			iFile = e('iFile');
		o.iFile = iFile;
		iFile.onchange = function(evt){
			o.onSelectFile(evt);
		}
		
		o.bCancel = e('bUploadCancel');
		o.bCancel.onclick = function(evt){
			o.onClickCancel(evt);
		}
		o.progressStateLabel = e('progressStateLabel');
		o.bCloseSpaceInfo = e('bNLSOk');
		o.bCloseSpaceInfo.onclick = function(evt){
			o.onClickCloseSpaceInfo();
		}
	},
	onSelectFile:function() {
		var o = this;
		Rest._get(
			function(data){o.onSuccessGetSpace(data);},
			br + '/space.json',
			function(data, responseText, info, xhr){ o.onFailUpload(data, responseText, info, xhr);}
		);
	},
	onSuccessGetSpace:function(data){
		if (!this.onFailUpload(data)) {
			return;
		}
		this.onSpaceOk();
	},
	onSpaceOk:function() { 
		var o = this, lang = storage("lang");
		try {
			Rest._postSendFileAndroid2(this.iFile, br + '/drvupload.json', {c: currentDir, lang: lang}, 
				function(data) {
					o.onSuccessUpload(data);
				},
				function(data) {
					o.onFailUpload(data);
				},
				function(data) {
					// o.onProgressUpload(data);
				},
				'_csrf_token_uf',
				e('_csrf_token').value,
				5 * 60
			);
			addClass('hFormUpWr', 'd-none');
			this.progressStateLabel.innerHTML = l('Uploading...');
			uploadAnimation.run();
		} catch (err) {
			alert(err);
		}
	},
	onSuccessUpload:function(data) {
		removeClass('hFormUpWr', 'd-none');
		if (!this.onFailUpload(data)) {
			this.onClickCancel();
			return;
		}
		if (!e('f' + data.file.i)) {
			fileList.addCatalog(data.file.name, data.file.i, data.file.type, data.file.s, data.file.ut, data.file.ct);
		}
		if (data.isRt) {
			
			setTimeout(function(){
				showMessage(l("Your file uploaded to") + " " + data.file.name);
			}, 1500);
		}
		this.onClickCancel();
	},
	onFailUpload:function(data, responseText, info, xhr) {
		var o = this;
		removeClass('hFormUpWr', 'd-none');
		mainMenuBackPush();
		//return defaultResponseError(data, responseText, info, xhr);
		if (data && data.status == 'ok') {
			return true;
		}
		
		if (data && data.status == 'error') {
			if (data.error) {
				if (data.noLeftSpace == 1) {
					o.showNoLeftSpaceScreen(data);
				} else {
					showError(data.error);
				}
			} else if (data.errors && (data.errors instanceof Array) ) {
				if (data.noLeftSpace == 1) {
					o.showNoLeftSpaceScreen(data);
				} else {
					showError(data.error);
				}
			}
		}
		
		return false;
	},
	onClickCancel:function(evt) {
		uploadAnimation.stop();
		this.progressStateLabel.innerHTML = l('ChooseFile');
		showScreen('hCatalogScreen');
	},
	showNoLeftSpaceScreen:function(data) {
		val('hFreeAftDV', data.freePD);
		val('hFreeAftWV', data.freePW);
		val('hFreeAftMV', data.freePM);
		val('hFreeAft6MV', data.freeP6M);
		showScreen('hNLSScreen');
	},
	onClickCloseSpaceInfo:function() {
		hideLoader();
	}
};
