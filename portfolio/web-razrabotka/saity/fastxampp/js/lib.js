if (! ( !!(window.localStorage) ) ) {
	window.localStorage = {
		getItem:function(){},
		setItem:function(){},
		fake : 1
	}	
}
var Tool = {
	post: function(url, data, F, E) {
		if (!E) {
			E = F;
		}
		data.token = window.token;
		data.xhr = 1;
		var req = new Request.JSON({
			url:url,
			data:data,
			method:'post',
			onSuccess:F,
			onFailure:E
		});
		req.send();
	},
	lskey: function (data) {
		var key = '', i;
		for (i in data) {
			if (i != 'token' && i != 'xhr') {
				key += i + "=" + data[i];
			}
		}
		return key;
	},
	cachepost: function(url, data, F, E) {
		if (!E) {E = F;}
		if (data.action && url) {
			var key = Tool.lskey(data), s, r;
			if (localStorage.getItem(key)) {
				try {
					r = localStorage.getItem(key);
					s = JSON.decode(r);
				    F(s, r);
				    return;
				} catch(err) {;}
			}
			Tool.cSucc = F;
			Tool.cFail = E;
			Tool.lsdata = data;
			Tool.post(url, data, Tool.cacheSuccess, Tool.cacheFail);
		}
	},
	cacheSuccess: function (data, raw) {
		localStorage.setItem(Tool.lskey(Tool.lsdata), raw);
		Tool.cSucc(data, raw);
	},
	cacheFail: function (xrh) {Tool.cFail(xrh);	},
	
	enableInputs: function(id, f) {
		if ($(id)) {
			$(id).getElements("input").each(
				function(i) {
					i.disabled = f;
				}
			);
			$(id).getElements("select").each(
				function(i) {
					i.disabled = f;
				}
			);
		}
	}
} 


function sz(obj) {return obj.length}
function to_i(n) {
	var v = parseInt(n);
	v = v?v:0;
	return  v;
}

function selectByValue(select, n) {
	for (var i = 0; i < sz($(select).options); i++) {
		if ( $(select).options[i].value == n ) {
			$(select).options.selectedIndex = i;
			$(select).onchange();
			break;
		}
	}
}
