window.addEventListener('load', onLoadBMenuAlertDlg, false);

function onLoadBMenuAlertDlg() {
	var E = e('bOk');
	if (E) {
		E.onclick = onClickBMenuDlgOk;
	}
}

function onClickBMenuDlgOk(){
    var k = 'chIAgree';
    storage('bmenualert', e(k).value);
    
	addClass('backMenuInfo', 'd-none');
	hideLoader();
    
}
