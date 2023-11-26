/** @depends from rest.js */
/** @depends from micron.js */

/**
 * <input type="file" 
 * 	data-url="/upload.php"
 * 	data-onselect="window.app.onSelectFile"
 *  data-onprogress="window.app.onProgressFile"
 *  data-onsuccess="window.app.onSuccessUploadFile"
 *  data-onfail="window.app.onFailUploadFile"
*/

function initFileInputs() {
	var ls = ee(D, 'input', function(i) {
		var onChange, onProgress, onSuccess, onFail, F = new Function();
		if (i.type == 'file') {
			onProgress = attr(i, 'data-onprogress');
			if (window[onProgress] instanceof Function) {
				onProgress = window[onProgress];
			} else {
				onProgress = F;
			}
			
			onSuccess = attr(i, 'data-onsuccess');
			if (window[onSuccess] instanceof Function) {
				onSuccess = window[onSuccess];
			} else {
				onSuccess = F;
			}
			
			onFail = attr(i, 'data-onfail');
			if (window[onFail] instanceof Function) {
				onFail = window[onFail];
			} else {
				onFail = F;
			}
			
			
			onChange = attr(i, 'data-onselect');
			if (window[onChange] instanceof Function) {
				i.onchange = window[onChange];
			} else {
				i.onchange = function() {
					Rest._postSendFile(i, attr(i, 'data-url'), {}, onSuccess, onFail, onProgress, '_token', Rest._token);
				};
			}
			
			
			
		}
	});
}
