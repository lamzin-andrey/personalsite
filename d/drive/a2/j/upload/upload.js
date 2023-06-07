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
		alert("A2V9");
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
			this.onClickCancel();
			return;
		}
		this.onSpaceOk();
	},
	onSpaceOk:function() { 
		var o = this;
		try {
			Rest._postSendFileAndroid2(this.iFile, br + '/drvupload.json', {c: currentDir, lang: "en"}, 
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
		//if (!e('f' + data.file.i)) {
			fileList.addCatalog(data.file.name, data.file.i, data.file.type, data.file.s, data.file.ut, data.file.ct);
		//}
		/*if (data.isRt) {
			
			setTimeout(function(){
				showMessage(l("Your file uploaded to") + " " + data.file.name);
			}, 1500);
		}*/
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
