window.upload = {
	init:function() {
		var o = this,
			iFile = e('iFile');
		this.iFile = iFile;
		iFile.onchange = function(evt){
			o.onSelectFile(evt);
		}
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
			addClass('hFormUpWr', 'd-none');
		} catch (err) {
			alert(err);
		}
	},
	onSuccessUpload:function(data) {
		removeClass('hFormUpWr', 'd-none');
	},
	onFailUpload:function(data) {
		removeClass('hFormUpWr', 'd-none');
		alert('Fail!');
	}
};
