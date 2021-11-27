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
		o.totalSize = o.calculateTotalSize();
		o.uploadedSize = 0;
		o.currentFile = 0;
		o.totalLength = o.iFile.files.length;
		o.uploadedCounter = 0;
		addClass('hFormUpWr', 'd-none');
		o.progressStateLabel.innerHTML = l('Uploading...');
		uploadAnimation.run();
		o.uploadOneFile();
	},
	uploadOneFile:function() {
		var o = this;
		try {
			Rest._fileIndex = o.currentFile;
			Rest._postSendFile(o.iFile, br + '/drvupload.json', {c: currentDir}, 
				function(data) {
					o.onSuccessUpload(data);
				},
				function(data) {
					o.onFailUpload(data);
				},
				function(percents, current, total) {
					o.onProgressUpload(percents, current, total);
				},
				'_csrf_token_uf',
				e('_csrf_token').value,
				5 * 60
			);
		} catch (err) {
			alert(err);
		}
	},
	calculateTotalSize:function() {
		var i, o = this, n = 0,
			sZ = sz(o.iFile.files);
		o.uploadedSizeAcc = [];
		for (i = 0; i < sZ; i++) {
			n += o.iFile.files[i].size;
			o.uploadedSizeAcc[i] = 0;
		}
		
		return n;
	},
	onSuccessUpload:function(data) {
		var o = this;
		if (!o.onFailUpload(data)) {
			o.onClickCancel();
			return;
		}
		fileList.addCatalog(data.file.name, data.file.i, data.file.type, data.file.s, data.file.ut, data.file.ct);
		
		o.currentFile++;
		if (o.totalLength > o.currentFile) {
			o.uploadOneFile();
		} else {
			// finalize
			o.currentFile = 0;
			o.onClickCancel();
		}
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
	},
	onProgressUpload:function(percents, current, total) {
		var o = this;
		e('progressState').innerHTML = HumanValue.getHumanFilesize(current, 2, 3, false) + ' / ' + HumanValue.getHumanFilesize(total, 2, 3, false) + ' (' + percents + '%)';
		e('dompb').style['width'] = percents + '%';
		
		o.uploadedSize = 0;
		var i;
		for (i = 0; i < o.iFile.files.length; i++) {
			if (i < o.currentFile) {
				o.uploadedSize += o.iFile.files[i].size;
			} else if (i == o.currentFile) {
				o.uploadedSize += current;
			}
		}
		if (o.uploadedSize > o.totalSize) {
			o.uploadedSize = o.totalSize;
		}
		// console.log(o.currentFile, current, o.uploadedSize, o.totalSize);
		var p = Math.round((o.uploadedSize * 100) / o.totalSize);
		e('dompb2').style['width'] = p + '%';
		e('progressState2').innerHTML = HumanValue.getHumanFilesize(o.uploadedSize, 2, 3, false) + ' / ' + HumanValue.getHumanFilesize(o.totalSize, 2, 3, false) + ' (' + p + '%)';
	}
};
