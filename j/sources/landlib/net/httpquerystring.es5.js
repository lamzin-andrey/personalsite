/**
 * @object Object for comfortable work with http query string elements
*/
window.HttpQueryString = {
	SSLP: 'https://',
	/**
	 * @description parse http query string
	 * @param {String} s 
	 * @return Object a-la PHP $_GET object
	*/
	parse:function(s) {
		if (!s && window && window.location &&  window.location.href) {
			s = window.location.href;
		}
		var a, i, GET = {}, aB;
		a = s.split('#');
		a = a[0].split('?');
		a = String(a[1]).split('&');
		for (i = 0; i < a.length; i++) {
			aB = a[i].split('=');
			GET[aB[0]] = aB[1];
		}
		return GET;
	},
	/**
	 * @description parse http query string, return http host name
	 * @param {String} s 
	 * @return http host name
	*/
	host:function(s) {
		if (!s && window && window.location &&  window.location.href) {
			s = window.location.href;
		}
		var a = s.split('/');
		return a[2];
	},
	/**
	 * @description parse http query string, return php $_SERVER['REQUEST_URI'] analogy
	 * @param {String} s 
	 * @return a-la php $_SERVER['REQUEST_URI']
	*/
	requestUri:function(s) {
		if (!s && window && window.location && window.location.href) {
			s = window.location.href;
		}
		var a = s.split('#');
		a = a[0].split('/');
		a.shift();
		a.shift();
		a.shift();
		return ('/' + a.join('/') );
	},
	/**
	 * @description Get one value from query string
	 * @param {String} key
	 * @param {String} _default
	 * @param {String} querystring
	 * @return String;
	*/
	_GET: function(key, _default, querystring) {
		var def = _default,
			v = key,
			search = querystring;
		if (!def) {
			def = null;
		}
		var s = window.location.href, buf = [], val, map = {};
		s = search ? search : s;
		while (s.indexOf(v + '[]') != -1) {
			val = this._GET(v  + '[]', def, s);
			if (!map[val]) {
				buf.push( decodeURIComponent(val) );
				map[val] = 1;
			}
			s = s.replace(v + '[]=' + val, '');
		}
		if (buf.length) {
			return buf;
		}
		var st = s.indexOf("?" + v + "=");
		if (st == -1) st = s.indexOf("&" + v + "=");
		if (st == -1) return def;
		var en = s.indexOf("&", st + 1);
		if ( en == -1 ) {
			return s.substring( st + v.length + 2);
		}
		return s.substring( st + v.length + 2, en );
	},
	/**
	 * @description Set value for variable varName in link string.
	 * 	replace in string like 'base?=a=v1&b=v2&c=v3' variable 'varName' value.
	 *  If variable not exists, it will added
	 * @param {String}  value may has value 'CMD_UNSET', then variable 'varName' will removed from query sting
	 * @param {Boolean} checkByValue = false. Set it in the true, if you want use unsetValue. It use for unset array element
	 * @param {String} unsetValue  = '' if  checkByValue = true && value == 'CMD_UNSET' must contains value, which need unset (will remove string varName=$unsetValue, for example in "?arr[]=1&arr[]=2" will unseted second element if unsetValue equal 2). It use for unset array element
	 * @return String (modified link)
	*/
	setVariable:function(link, varName, value, checkByValue, unsetValue) {
		value = decodeURIComponent(value);
		checkByValue = String(checkByValue) == 'undefined' ? false : checkByValue;
		unsetValue = String(unsetValue) == 'undefined' ? '' : unsetValue;
		unsetValue = decodeURIComponent(unsetValue);
		link = decodeURIComponent(link);
		
		var sep = '&', arr = link.split('?'), base = arr[0], tail = arr[1], cmdUnset = 'CMD_UNSET', searchStr;
		if (!tail) {
			sep = '';
			tail = '';
		}
		searchStr = checkByValue ? (varName + '=' + (value != cmdUnset ? value : unsetValue)  ) : (varName + '=');
		if (!~tail.indexOf(searchStr)) {
			if (value != cmdUnset) {
				tail += sep + varName + '=' + value;
			}
		} else {
			if (value != cmdUnset) {
				if (!checkByValue) {
					tail = tail.replace(new RegExp(varName + '=[^&]*'), varName + '=' + value);
				}
			} else {
				if (!checkByValue) {
					varName = varName.replace('[', '\\[');
					varName = varName.replace(']', '\\]');
					tail = tail.replace(new RegExp(varName + '=[^&]*', 'g'), '').replace(/&&/g, '&').replace(/&$/, '');
					tail = tail.replace(/&$/g, '').replace(/^&/g, '');
				} else {
					tail = tail.replace(varName + '=' + unsetValue, '');
					tail = tail.replace(/&&/g, '&');
					tail = tail.replace(/&$/g, '').replace(/^&/g, '');
				}
			}
		}
		link = tail ? (base + '?' + tail) : base;
		return link;
	},
	/**
	 * @description 
	 * @return Boolean true if current location dstart from https://
	*/
	isSSL:function() {
		var a = location.href.split(':');
		
		return (a[0] == 'https');
	}
}
window.addEventListener('load', function() {
	window.$_GET = HttpQueryString.parse();
	window.$_SERVER = {};
	window.$_SERVER['HTTP_HOST'] = HttpQueryString.host();
	window.$_SERVER['REQUEST_URI'] = HttpQueryString.requestUri();
});

