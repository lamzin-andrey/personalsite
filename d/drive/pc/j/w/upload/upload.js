window.upload = {
	init:function() {
		var o = this;
		o.iFile = 0;
		o.iFiles = [];
		o.currentFile = -1;
		o.currentInput = -1;
		o.totalCounter = -1;
		o.uploadedSize = 0;
		o.uploadedCounter = 0;
	},
	onSelectFile:function(evt) {
		var o = window.upload;
		o.showUploadProgressDialog();
		o.iFiles.push(ctrg(evt));
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
		var i, SZ, o = this;
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
			
			o.uploadProcessing = 1;
			Rest._postSendFile(o.iFiles[o.currentInput], br + '/drvupload.json', {c: fmgr.tab.currentFid, lang: lang}, 
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
		var up, o = this, needNext = 0;
		if (!o.onFailUpload(data)) {
			return;
		}

		o.cid = data.file.i;
		if (!fmgr.tab.checkBool(o.existsByFid, o)) {
			up = fmgr.tab.createItem(data.file);
			fmgr.tab.list.push(up);
			fmgr.tab.redraw();
		}
		
		if (data.isRt) {
			setTimeout(function(){
				showSuccess(L("Your file uploaded to") + " " + data.file.name);
			}, 500);
		}
		
		
		if (sz(o.iFiles[o.currentInput].files) - 1 > o.currentFile) {
			needNext = 1;
			o.currentFile++;
			o.totalCounter++;
		} else {
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
			o.onClickCancel();
		}
	},
	
	existsByFid:function(it) {
		if (this.cid == it.src.i) {
			return true;
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
	onFailUpload:function(data, responseText, info, xhr) {
		var o = this;
		if (data && data.status == 'ok') {
			return true;
		}
		
		o.onClickCancel();
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
		var o = this;
		dlgMgr.hide(o.uploadProcessDlgN);
		o.currentFile = 0;
		o.currentInput = 0;
		o.uploadDlgApp.reset();
		o.totalCounter = 0;
		o.uploadProcessing = 0;
		o.iFiles = [];
	},
	
	showNoLeftSpaceScreen:function(data) {
		var a = data;
		if (fmgr.noSpaceLeftDlg) {
			delete fmgr.noSpaceLeftDlg;
		}
		fmgr.noSpaceLeftDlg = new NoSpaceLeftDlg();
		fmgr.noSpaceLeftDlgN = dlgMgr.create(this.getNoSpaceLeftHtml(), fmgr.noSpaceLeftDlg);
		fmgr.noSpaceLeftDlg.translate(a.freePD, a.freePW, a.freePM, a.freeP6M);
		dlgMgr.center(fmgr.noSpaceLeftDlgN);
		
	},
	getNoSpaceLeftHtml:function(){
		return `<div class="no-left-dlg">
			<div class="fp"><span class="p0">На WebUSB закончилось место</span> <img src="/d/drive/a2/i/facesad.png"></div>
			<div class="p p1">Попробуйте удалить файлы.</div>
			<div class="p p2"></div>
			<div class="p p3"></div>
			<div class="p p4"></div>
			<div class="p p5">
			</div>
			<div class="p p6">
			</div>
			<div class="p p7">
			</div>
			<div class="p p8">
			</div>
			<div class="tr btns">
				<input type="button" value="">
			</div>
		</div>`;
	},
	onClickCloseSpaceInfo:function() {
		hideLoader();
	},
	
	showUploadProgressDialog:function() {
		var o = this, dlg = o.uploadProcessDlgN, v = getViewport();
		if (isU(dlg)) {
			o.uploadDlgApp = new UploadViewDlg();
			dlg = o.uploadProcessDlgN = window.dlgMgr.create(o.getUploadDialogHtml(), o.uploadDlgApp);
		} else {
			o.uploadDlgApp.reset();
			window.dlgMgr.show(dlg);
		}
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
