window.upload = {
	init:function() {console.log("Skip init upload");
		var o = this;
		o.iFile = 0;
		o.iFiles = [];
		o.currentFile = -1;
		o.currentInput = -1;
		o.totalCounter = -1;
		o.uploadedSize = 0;
		o.uploadedCounter = 0;
		// TODO этот listener надо вешать сразу после отрисовки меню
		// Или в пункте прямо написать upload.onSelectFile
		/*iFile.onchange = function(evt){
			o.onSelectFile(evt);
		}*/
		
		/*
		o.bCancel = e('bUploadCancel'); // TODO это кнопка закрытия экрана аплоада... её кажется что нет.
		o.bCancel.onclick = function(evt){
			o.onClickCancel(evt); // Но можно скрывать плашку... Не нужно.
		}
		o.progressStateLabel = e('progressStateLabel'); // TODO скорее всего другая выборка
		
		o.bCloseSpaceInfo = e('bNLSOk'); // ПОчему бы не запилить диалог с сообщением. Но тогда логику в него конечно.
		o.bCloseSpaceInfo.onclick = function(evt){
			o.onClickCloseSpaceInfo();
		}*/
	},
	onSelectFile:function(evt) {
		var o = window.upload;
		o.showUploadProgressDialog();
		o.iFiles.push(ctrg(evt));
		console.log(o.iFiles);
		Rest._get(
			function(data){o.onSuccessGetSpace(data);},
			br + '/space.json',
			function(data, responseText, info, xhr){ o.onFailUpload(data, responseText, info, xhr);}
		);
	},
	onSuccessGetSpace:function(data){
		if (!this.onFailUpload(data)) {// TODO see
			// this.onClickCancel();
			return;
		}
		this.onSpaceOk();
	},
	onSpaceOk:function() {
		var o = this, i, SZ;
		o.totalSize = o.calculateTotalSize();
		o.currentFile = o.currentFile == -1 ? 0 : o.currentFile;
		o.currentInput = o.currentInput == -1 ? 0 : o.currentInput;
		o.totalCounter = o.totalCounter == -1 ? 0 : o.totalCounter;
		
		SZ = sz(o.iFiles);		
		o.totalLength = 0;
		for (i = 0; i < SZ; i++) {
			o.totalLength += sz(o.iFiles[i].files);
		}
		if (!o.uploadProcessing) {
			o.uploadOneFile();
		} else {
			o.uploadDlgApp.setXFromY(o.totalCounter + 1, o.totalLength);
		}
	},
	uploadOneFile:function() {
		var o = this,
			lang = storage("lang");
		try {
			// Тут надо хорошенько подумать. Возможно, данные из инпутов надо будет складывать куда-то.
			// Но не исключено, что основное все само образуется
			// Надо мониторить, сколько файлов в iFile после каждого выбора. Тогда смогу решить.
			o.uploadDlgApp.setFilename(o.iFiles[o.currentInput].files[o.currentFile].name);
			o.uploadDlgApp.setXFromY(o.totalCounter + 1, o.totalLength);
			Rest._fileIndex = o.currentFile;
			console.log(`Upload input ${o.currentInput}, fileN = ${o.currentFile}`);
			o.uploadProcessing = 1;
			Rest._postSendFile(o.iFiles[o.currentInput], br + '/drvupload.json', {c: fmgr.tab.currentFid, lang: lang}, 
				function(data) {
					o.onSuccessUpload(data);
				},
				function(data) {
					o.onFailUpload(data);// TODO see
				},
				function(percents, current, total) {
					o.onProgressUpload(percents, current, total); // TODO see
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
		var i, j, o = this, n = 0,
			sZ = sz(o.iFiles);
		for (j = 0; j < sZ; j++) {
			for (i = 0; i < sz(o.iFiles[j].files); i++) {
				n += o.iFiles[j].files[i].size;
			}
		}
		
		return n;
	},
	onSuccessUpload:function(data) {
		var o = this, needNext = 0;
		if (!o.onFailUpload(data)) {
			o.onClickCancel();
			return;
		}
		if (!e('f' + data.file.i)) {
			// TODO add item code
			// fileList.addCatalog(data.file.name, data.file.i, data.file.type, data.file.s, data.file.ut, data.file.ct);
		}
		
		if (data.isRt) {
			setTimeout(function(){
				showSuccess(l("Your file uploaded to") + " " + data.file.name);
			}, 1500);
		}
		
		
		if (sz(o.iFiles[o.currentInput].files) - 1 > o.currentFile) {
			
			//console.log("needNext = 1; V1");
			
			needNext = 1;
			o.currentFile++;
			o.totalCounter++;
		} else {
			//console.log("needNext = 1; V2");
			needNext = 1;
			o.currentInput++;
			o.currentFile = 0;
			o.totalCounter++;
		}
		
		if (sz(o.iFiles) <= o.currentInput) {
			needNext = 0;
		}
		
		if (needNext) {
			o.uploadOneFile();
		} else {
			o.currentFile = 0;
			o.currentInput = 0;
			o.onClickCancel();
			o.uploadDlgApp.reset();
			o.totalCounter = 0;
			o.uploadProcessing = 0;
			o.iFiles = [];
		}
	},
	
	onProgressUpload:function(percents, current, total) {
		var o = this, i, j, p, fileSize = new FileSize(),
			meas = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		o.uploadDlgApp.setProgressValue(
			fileSize.getHumanValue(current, meas, 1024, 2, 3, false), 
			fileSize.getHumanValue(total, meas, 1024, 2, 3, false),
			percents
		);
		
		o.uploadedSize = 0;
		for (j = 0; j < sz(o.iFiles); j++) {
			for (i = 0; i < sz(o.iFiles[j].files); i++) {
				if (j < o.currentInput) {
					o.uploadedSize += o.iFiles[j].files[i].size;
				} else if (j == o.currentInput && i < o.currentFile) {
					o.uploadedSize += o.iFiles[j].files[i].size;
				} else if (j == o.currentInput && i == o.currentFile) {
					o.uploadedSize += current;
				}
			}
		}
		
		if (o.uploadedSize > o.totalSize) {
			o.uploadedSize = o.totalSize;
		}
		p = Math.round((o.uploadedSize * 100) / o.totalSize);
		
		
		o.uploadDlgApp.setTotalProgressValue(
			fileSize.getHumanValue(o.uploadedSize, meas, 1024, 2, 3, false), 
			fileSize.getHumanValue(o.totalSize, meas, 1024, 2, 3, false),
			p
		);
		
	},
	// TODO тут конь вообще не валялся
	onFailUpload:function(data, responseText, info, xhr) {
		var o = this;
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
					// TODO patch it
					o.showNoLeftSpaceScreen(data);
				} else {
					showError(data.error);
				}
			}
		}
		
		return false;
	},
	onClickCancel:function(evt) {
		dlgMgr.hide(this.uploadProcessDlgN);
	},
	// TODO Вынести в модалку
	showNoLeftSpaceScreen:function(data) {
		v('hFreeAftDV', data.freePD);
		v('hFreeAftWV', data.freePW);
		v('hFreeAftMV', data.freePM);
		v('hFreeAft6MV', data.freeP6M);
	},
	onClickCloseSpaceInfo:function() {
		hideLoader();
	},
	
	showUploadProgressDialog:function() {
		var o = this, dlg = o.uploadProcessDlgN, v = getViewport();
		if (isU(dlg)) {
			o.uploadDlgApp = new UploadViewDlg(); // TODO
			dlg = o.uploadProcessDlgN = window.dlgMgr.create(o.getUploadDialogHtml(), o.uploadDlgApp);
		} else {
			// o.uploadDlgApp.reset(0); // TODO
			window.dlgMgr.show(dlg);
		}
		console.log("v.h", v.h);
		dlgMgr.move(dlg, v.w - 20 - 440, v.h - (111 + dlgMgr.ls[dlg].CAPTION_H * 2));
	},
	
	getUploadDialogHtml:function() {
		return `<div class="ePb uploadDlg">
			<div class="progressStateLabel w440"><span class="hSrcSz cfcnt">2</span> <span class="hFrom from1">from</span> <span class="hDestSz cftot">7</span> <span class="fname">Vospitanie-amiosti-u-sobak-i-muzhchin</span></div>
			  <div class="pbarandtextwr">
				 <div class="pbarwrap">
					<div style="width:10%;" class="dompb pb1">&nbsp;</div>
				 </div>
				 <span class="hSrcSz tpcsz">(0.5 Mb </span> <span class="hFrom from2">from</span> <span class="hDestSz tptsz">10Mb) 1%</span>
			  </div>
			  <div class="pbarandtextwr">
				 <div class="pbarwrap">
					<div style="width:75%;" class="dompb pb2">&nbsp;</div>
				 </div>
				 <span class="hSrcSz cpcsz">700 Kb</span> <span class="hFrom from3">from</span> <span class="hDestSz cptsz">10 Mb) 75%</span>
			  </div>
			</div>`;
	}
};
