window.root = '/d/drive/a2/cv';
window.br = window.backRoot = '/sp/public';
window.onload = initApp;
function initApp() {
	onLoadA236();
	addClass('hWaitScreen', 'hide');
	showActiveTheme();
	removeClass('hCVersScreen', 'hide');
}

function showActiveTheme() {
	var t = storage('savedTheme'),
		ct,
		aB = e('active'),
		ot = e('other');
	t = t ? t : 'a2';
	t = e(t);
	ct = cs(aB, 'card')[0];
	if (ct) {
		ot.appendChild(ct);
	}
	aB.appendChild(t);
	removeClass(ot, 'h');
}


function chooseV(s) {
	var dt = new Date();
		storage('savedTheme', s);
		gto('/d/drive/?r=' + Math.random() + dt.getTime());
}
function onClickChooseEn() {
	storage('lang', 'langEn');
	e('lang').value = 'en';
}
function onClickChooseRu() {
	storage('lang', 'langRu');
	e('lang').value = 'ru';
}



function onLoadA236() {
	if (nav.userAgent.toLowerCase().indexOf('android 2.3.6') == -1) {
		return;
	}
	d.body.style['min-height'] = '400px';
	// d.body.style['border'] = 'black 1px solid';
	var y = 1;
	w.scrollTo(0, y);
	setTimeout(function(){
		w.scrollTo(0, y);
	}, 200);
}
