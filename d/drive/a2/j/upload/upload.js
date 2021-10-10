window.upload = {
	init:function() {
		var o = this,
			iFile = e('iFile');
		this.iFile = iFile;
		iFile.onchange = function(evt){
			o.onSelectFile(evt);
		}
		
		this.bCancel = e('bUploadCancel');
		this.bCancel.onclick = function(evt){
			o.onClickCancel(evt);
		}
		this.progressStateLabel = e('progressStateLabel');
	},
	onSelectFile:function() { 
		var o = this;
		try {
			Rest._postSendFileAndroid2(this.iFile, br + '/drvupload.json', {c: currentDir}, 
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
			onLoadA236();
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
		fileList.addCatalog(data.file.name, data.file.i, data.file.type);
		this.onClickCancel();
	},
	onFailUpload:function(data, responseText, info, xhr) {
		removeClass('hFormUpWr', 'd-none');
		mainMenuBackPush();
		return defaultResponseError(data, responseText, info, xhr);
	},
	onClickCancel:function(evt) {
		uploadAnimation.stop();
		this.progressStateLabel.innerHTML = l('ChooseFile');
		showScreen('hCatalogScreen');
	}
};
