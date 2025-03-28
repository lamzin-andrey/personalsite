/**
 * Библиотека micron появилась, когда я был одержим идеей писать как можно более лаконичный js код
 * Её удобно использовать, когда пишешь код со (и для) старого смартфона.
*/
var D = document,
d = D,
W = window, S = String,
w = W,
nav = W.navigator;
function e(i) {
	if (i && i.tagName || D == i) return i;
	return D.getElementById(i);
}
W.micron$ = e;
function ee(p, c) {
	p = e(p);
	return p.getElementsByTagName(c);
}
function eee(p, c, f) {
	var i, L = ee(p, c), z = sz(L);
	for (i = 0; i < z; i++) {
		f(L[i]);
	}
}
W.micron$$ = ee;
function cs(p, c) {
	var a;
	p = e(p);
	if (p.getElementsByClassName) {
		a = p.getElementsByClassName(c);
		sz(a);
		return a;
	}
	return [];
}
function hasClass(obj, css) {
	var obj = e(obj);
	var c = obj.className, _css = css.replace(/\-/g, "\\-"), 
	re1 = new RegExp("^\\s?" + _css + "\\s*"), 
	re2 = new RegExp("\\s+" + _css + "(\\s+[\\w\\s]*|\\s*)$");
	if (c == css || re1.test(c) || re2.test(c)) {
		return true;
	} 
	return false;
}
function removeClass(obj, css) {
	obj = e(obj);
	if (!obj) {
		return;
	}
	var c = obj.className, re = /[0-9a-zA-Z\-_]+/gm,
	arr = c.match(re),
	i, result = [];
	if (arr) for (i = 0; i < arr.length; i++) {
		if (arr[i] !== css) {
			result.push(arr[i]);
		}
	}
	obj.className = result.join(' ');
}
function addClass(obj, css) {
	obj = e(obj);
	if (!obj) {
		return;
	}
	removeClass(obj, css);
	obj.className += ' ' + css;
}
//getviewport
function getViewport() {
	var w = W.innerWidth, h = W.innerHeight;
	if (!w && D.documentElement && D.documentElement.clientWidth) {
		w = D.documentElement.clientWidth;
	} else if (!w) {
		w = D.getElementsByTagName('body')[0].clientWidth;
	}
	if (!h && D.documentElement && D.documentElement.clientHeight) {
		h = D.documentElement.clientHeight;
	} else if (!h) {
		h = D.getElementsByTagName('body')[0].clientHeight;
	}
	return {w:w, h:h};
}
function appendChild(parent, tag, innerHTML, obj, dataObj) {
	var el = D.createElement(tag), i;
	if (obj) {
		for (i in obj) {
			if (obj[i] instanceof Function) {
				el[i] =  obj[i];
			} else {
				el.setAttribute(i, obj[i]);
			}
		}
	}
	if (dataObj) {
		for (i in dataObj) {
			el.setAttribute('data-' + i, dataObj[i]);
		}
	}
	el.innerHTML = innerHTML;
	e(parent).appendChild(el);
	
	return el;
}
function ce(parent, tag, id, obj, dataObj) {
	obj.id = id;
	return appendChild(parent, tag, '', obj, dataObj);
}
function rm(DOMNode) {
	var o = e(DOMNode);
	o ? o.parentNode.removeChild(o) : 0;
}
function attr(o, name, val) {
	o = e(o);
	if (val) {
		o.setAttribute(name, val);
	}
	if (o.hasAttribute(name)) {
		return o.getAttribute(name);
	}
	return null;
}
function bod() {
	return D.getElementsByTagName('body')[0];
}
function stl(o, s, v) {
	o = e(o);
	if (v) {
		o.style[s] = v;
	}
	v = o.style[s];
	return v;
}
function show(o, v) {
	v = v ? v : 'block';
	stl(o, 'display', v);
}
function hide(o) {
	stl(o, 'display', 'none');
}
/**
 * @description Каюсь, я был уверен что в IE 6 её нет у клласса String
*/
function trim(s) {
	s = S(s).replace(/^\s+/mig, '');
	s = S(s).replace(/\s+$/mig, '');
	return s;
}
/**
 * @description Examples: 
 *    var o = In(1,2,5,6,8); if (x in o) {...}
 *    var a = [1,2,5,8,9,'dfsdsdf',9], o = In(a);  if (x in o) {...}
 * 	  var o = {a:0, b:'dsf', c: 101}, o = In(o); x = 'dsf'; (x in o) // => true
 * 	  var o = {a:0, b:'dsf', c: 101}, o = In(o); x = 200; (x in o) // => false
 * @param {*} a
*/
function In(a) {
	var i, o = {};
	if (a instanceof Array) {
		for (i = 0; i < sz(a); i++) {
			o[a[i]] = 1;
		}
	} else if (a instanceof Object) {
		for (i in a) {
			o[a[i]] = 1;
		}
	} else {
		for (i = 0; i < sz(arguments); i++) {
			o[arguments[i]] = 1;
		}
	}
	return o;
}
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
		if (!(typeof(data) == "string")) {
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
/**
 * @description Преобразовывается изображение в dataUri
 * @param {Image} 
 * @return {String}
*/
function imgToDataUri(i) {
	var canvas;
	try {
		canvas = document.createElement('canvas');
		canvas.width = i.naturalWidth;
		canvas.height = i.naturalHeight;
		canvas.getContext('2d').drawImage(i, 0, 0);
		var r = canvas.toDataURL('image/png');
		delete canvas;
		return r;
	} catch(e) {
		if (canvas) {
			delete canvas;
		}
	}
	return false;
}
/**
 * @description Сохранить объект имеющий поле id в базе webSql (Chrome) по аналогии со storage
 * @param {String} table
 * @param {Mixed} mixed если передан объект с полем id будкет сохранен в таблице под этим i но если передана функция, сделаем запрос на выборку, и вернем объект
 * @param {Number} id при выборке обязателен, при вставке обязателен, если нет в обьъекте поля id или он не объект
*/
function wstorago(table, mixed, id) {
	if (mixed instanceof Function && id) {
		wsStorage(table, id, mixed);
	} else {
		if ((mixed instanceof Object) && !id) {
			id = mixed.id;
		}
		if (id) {
			wsStorage(table, id, 0, mixed);
		}
	}
}
/**
 * @description Сохранить данные в базе webSql (Chrome). По аналогии со storage храним все как JSON объект, но в записи с id как у объекта
 * @param {String} table
 * @param {Number} id
 * @param {Function} | {Null} onData если запрос на выборку, вернем объект
 * @param {Object}  data
*/
function wsStorage(table, id, onData, data) {
	var db, tableNotExists, recordFound, sql = '', args = [],
		isSelectQuery;
	try {
		db = openDatabase("microndb", "0.1", "MicronDb", 200000);
	} catch(e) {
		onData(false);
		return;
	}
	
	if (db) {
		//существует ли таблица?
		db.transaction(function(tx) {
			//tx.executeSql("DROP TABLE " + table, [], null, null);
			tx.executeSql("SELECT data FROM " + table + ' WHERE id = ?', [id],
				function(result){},
				function(tx, error){
					console.log(error);
					if (~error.message.indexOf('no such table')) {
						tableNotExists = 1;
						tx.executeSql("CREATE TABLE " + table + " (id REAL UNIQUE, data TEXT)", [], null, null);
					}
				});
		});
		db.transaction(function(tx) {
			tx.executeSql("SELECT data FROM " + table + ' WHERE id = ?', [id], 
				function(tx, result){
					//console.log('Result exists...');
					if (result.rows && result.rows.item instanceof Function) {
						result = result.rows.item(0)['data'];	
						if (result) {
							try {
								result = JSON.parse(result);
							} catch(e){;}
						}
						recordFound = 1;
					} else {
						result = false;
					}
					if (onData instanceof Function) {
						//console.log(result);
						onData(result);
						return;
					}
					//console.log('Insert / update');
					if (!(data instanceof String)) {
						data = JSON.stringify(data);
					}
					args.push(data);
					args.push(id);
					if (!recordFound) {
						sql = "INSERT INTO " + table + ' (data, id) values(?, ?)';
					} else {
						sql = "UPDATE " + table + ' SET data = ? WHERE id = ?';
					}
					//console.log(sql);
					//console.log(args);
					db.transaction(function(tx) {
						tx.executeSql(sql, args, 
							function(result){
								//console.log('Ins/Upd Result:');
								//console.log(result);
							},
							function(tx, error){
								//console.log('Ins/Upd Fail:');
								console.log(error);
							});
					});
					
					
				},
				function(tx, error){
					console.log(error);
				});
		});
		
	}//end if db
}
/**
 * @description Для удобной установки значений по умолчанию
 * @param {*} value
 * @param {*} defaultValue
 * @return * defaultValue if value is undefined
*/
function def(v, defV) {
    if (S(v)  == 'undefined'){
      return defV;
    }
    return v;
}
/**
 * @description disable form
*/
function df(i){
	eee(i, 'input', di);
	eee(i, 'textarea', di);
	eee(i, 'select', di);
}
function di(o){
	let s = "disabled";
	attr(o, s, s);
}
/**
 * @description disable form
*/
function ef(i){
	eee(i, 'input', ei);
	eee(i, 'textarea', ei);
	eee(i, 'select', ei);
}
function ei(o){
	o.removeAttribute("disabled");
}
/**
 * @description Возвращает размер массива - 1
 * @param {Array} o
 * @return Number array length - 1
*/
function decsz(o) {
  return sz(o) - 1;
}
/**
 * @description Безопасно возвращает размер массива
 * @param {Array} o
 * @return Number array length - 1
*/
function sz(o) {
  if (o && String(o.length) === 'undefined') {
    if (o instanceof Object) {
		var l = 0, i;
		for (var i in o) {
			l++;
		}
		window.SZ = l;
		return l;
	}
  }
  window.SZ = o && o.length ? o.length : 0;
  return window.SZ;
}

function slAo(s, t, i){
	var o;
	s = e(s);
	i = i ? i : t;
	o = new Option(t, i);
	s.options[sz(s.options)] = o;
}

function slUo(s, t, i){
	var o, j, z;
	s = e(s);
	z = sz(s.options);
	for (j = 0; j < z; j++) {
		if (s.options[j].value == i) {
			s.options[j].text = t;
		}
	}
}

/**
 * @description Меняет два элемента в массиве (или объекте) местами
 * @param {Array|Object}  o
 * @param {String|Number} i
 * @param {String|Number} j
*/
function ex(data, i, j) {
  var b = data[i];
  data[i] = data[j];
  data[j] = b;
}
/**
 * @return Array
*/
function array_values(o) {
	if (o instanceof Array) {
		return o;
	}
	if (o instanceof Object) {
		var r = [], i;
		for (i in o) {
			r.push(o[i]);
		}
		return r;
	}
	
	return [];
}
function val(i, vl) {
	return v(i, vl);
}

function v(o, s) {
	var r = s;
	o = e(o);
	// if (o.tagName == 'INPUT' || o.tagName == 'TEXTAREA' || o.tagName == 'SELECT') {
	if (o.tagName in In(['INPUT', 'TEXTAREA', 'SELECT'])) {
		if (o.type != 'checkbox') {
			if (S(s) !== 'undefined') {
				o.value = s;
			} else {
				r = o.value;
			}
		} else {
			if (S(s) !== 'undefined') {
				if (!s) {
					o.checked = false;
				} else {
					o.checked = true;
				}
			} else {
				r = o.checked;
			}
		}
	} else {
		if (S(s) !== 'undefined') {
			o.innerHTML = s;
		} else {
			r = o.innerHTML;
		}
	}
	
	return r;
}

function loc() {
	return location;
}

function foc(i){
	e(i) ? e(i).focus() : 0;
}

function gto(s) {
	loc().href = s;
}

function reload() {
	loc().reload();
}

function isU(x) {
	return String(x) === "undefined";
}

function isNull(x) {
	return String(x) === "null";
}

function ctrg(ev) {
	return ev.currentTarget;
}

function nv() {
	return navigator;
}

function isSmart() {
	var s = nv().userAgent.toLowerCase();
	return !!(~s.indexOf("iphone") || ~s.indexOf("android"));
}
