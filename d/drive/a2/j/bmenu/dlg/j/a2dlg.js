window.addEventListener('load', onLoadBMenuAlertDlg, false);

function onLoadBMenuAlertDlg(){
     e('bOk').onclick = onClickBMenuDlgOk;
}

function onClickBMenuDlgOk(){
    var k = 'chIAgree';
    storage('bmenualert', e(k).value);
    
	addClass('backMenuInfo', 'd-none');
	hideLoader();
    
}
