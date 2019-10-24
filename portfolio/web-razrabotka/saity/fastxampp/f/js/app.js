'use strict';
var TOP = 0;  //При использовании ncore установить в другое значение
var ival;
//controller
window.onload = init;
function init(){
	//все скрины в div.screenWrapper
	window.W = window;
	window.D = document;
	W.root = '/portfolio/web-razrabotka/saity/fastxampp/f';
	initFileInputs();
    
    /** @var HtmlInput iBody текстовое поле  */
    
    /** @var HtmlDivElement hError блок с сообщением об ошибке */
    /** @var HtmlDivElement hErrorText Сообщение об ошибке     */
    /** @var HtmlDivElement hMessage блок с сообщением         */
    /** @var HtmlDivElement hMessageText Сообщение             */
    
    /** @var HtmlDivElement hUploadAppScreen блок всех элементов связанных с загрузкой файлов приложения */
    /** @var HtmlDivElement hProcessMonitorScreen блок всех элементов связанных с процессом компиляции приложения */
    
    /** @var HtmlAnchorElement   */
    $('gotoUpload2').onclick = $('gotoUpload').onclick = onGetUploadForm;
    $('bCompile').onclick   = onCompileClick;
    
}
function onComplieState(data) {
	if (data.status == 'error') {
		if(data.info == 'notFound') {
			//TODO сделать активной кнопку создать новое приложение
			error(data);//еуе должно быть Извините, но информация о вашем приложении утеряна. Мы сейчас пилим регистрацию пользователей, которая решит эту проблему, а пока вы можете перезагрузить ваше приложение на компиляцию, нажав кнопку "Новое приложение" 
		}
	}
	if (data.status == 'ok') {
		if (data.msg) {
			hCompileInfo.innerHTML = wrap(data.msg);
		}
	}
	if (S(data.ts) != 'undefined') {
		var n = 60 - data.ts, t;
		t = n = n <= 0 ? 60 : n;
		setTimeout(function(){
			hide(hCountDownWrap);
			clearInterval(ival);
			_get(onComplieState, '/state.php');
		}, n * 1000);
		ival = setInterval(function(){
			if (t > 0) {
				show(hCountDownWrap);
				t--;
				hCountDown.innerText = t;
			} else {
				hide(hCountDownWrap);
				clearInterval(ival);
			}
		}, 1000);
	}
}
function onCompileClick(evt){
	evt.preventDefault();
	var data = {name:iAppName.value, display:iAppDisplayName.value};
	if (trim(data.name) && trim(data.display)) {
		error('');
		success('');
		_post(data, onSaveMetadata, '/save.php', onFailSaveMetadata);
	} else {
		if (!trim(data.name)) {
			error('Заполните Системное имя вашего приложения');
		}
		if (!trim(data.display)) {
			error('Заполните Отображаемое имя вашего приложения');
		}
	}
}
function onSaveMetadata(data){
	if (data.status == 'ok') {
		hideScreens();
		show(hProcessMonitorScreen);
		_get(onComplieState, '/state.php');
	} else if(data.status == 'error') {
		error(data);
	}
}
function onFailSaveMetadata() {
	console.log(arguments);
}
function onUpload(data){
	if (data.status == 'ok') {
		bCompile.disabled = false;
		error('');
	} else if(data.status == 'error') {
		error(data);
	}
}
function onFailUpload(){
	defaultFail();
}
function onUploadProgress(loadedBytes){
	if (loadedBytes) {
		hProgView.innerHTML = loadedBytes + '%';
	}
}
function onChooseFile(){
};
function onGetUploadForm() {
	clearInterval(ival);
	hProgView.innerHTML = '0%';
	hideScreens();
	show(hUploadAppScreen);
}
//- end controller

//-- View --
function error(s) {
	hide(hMessage);
	
	if (!(s instanceof Object)) {
		if (s.length > 0) {
			hErrorText.innerHTML = s;
			show(hError);
		} else {
			hide(hError);
		}
	} else {
		var data = s;
		if (data.msg) {
			error(data.msg);
		} else if(data.info in In('validate', 'validate2')) {
			var i, a = [];
			for (i in data) {
				if (!isNaN(parseInt(i))) {
					a.push(data[i]);
				}
			}
			error(wrap(a.join('\n')));
		}
	}
}
function success(s, isHtml) {
	hide(hError);
	if (s.length > 0) {
		if (!isHtml) {
			hMessageText.innerText = s;
		} else {
			hMessageText.innerHTML = s;
		}
		show(hMessage);
	} else {
		hide(hMessage);
	}
}
function hideScreens() {
	var ls = getScreenWrapperList(), i, scr;
	for (i = 0; i < sz(ls); i++) {
		scr = $$(ls[i], 'div')[0];
		if (scr.tagName == 'DIV') {
			hide(scr);
		}
	}
}
function getScreenWrapperList(){
	var c = 'screenWrapper';
	if (D.getElementsByClassName) {
		return D.getElementsByClassName(c);
	}
	var ls = $$(D, 'div'), i, res = [];
	for (i = 0; i < sz(ls); i++) {
		if (hasClass(ls[i], c)) {
			res.push(ls[i]);
		}
	}
	return res;
}
function getToken() {
	var list = $$(D, 'meta'), i, t;
	for (i = 0; i < list.length; i++) {
		if (attr(list[i], 'name') == 'app') {
			t = attr(list[i], 'content');
			break;
		}
	}
	return t;
}
function gotop() {
	W.scrollTo(0, TOP);
}
function wrap(s){
	var a = s.split('\n');
	s = '<p>' + a.join('</p><p>') + '</p>';
	return s;
}
//- end View


//========Local Storage ==============
/**
 * @description Индексирует массив по указанному полю
 * @param {Array} data
 * @param {String} id = 'id'
 * @return {Object};
*/
function storage(key, data) {
	var L = window.localStorage;
	if (L) {
		if (data === null) {
			L.removeItem(key);
		}
		if (!(data instanceof String)) {
			data = JSON.stringify(data);
		}
		if (!data) {
			data = L.getItem(key);
			if (data) {
				try {
					data = JSON.parse(data);
				} catch(e){;}
			}
		} else {
			L.setItem(key, data);
		}
	}
	return data;
}
