window.micronDefaultContext = window;
function aevt(o, name, lst, ctx, opts) {
	opts = opts ? opts : false;
	if (o instanceof String) {
		o = e(o);
	}
	o.addEventListener(name, function onAevt(evt) {
		if (!ctx) {
			ctx = window.micronDefaultContext;
		}
		if (ctx) {
			return lst.call(ctx, evt);
		} else {
			return lst(evt);
		}
	}, opts);
}

function si(lst, ms, ctx) {
	return setInterval(function onSi() {
		if (!ctx) {
			ctx = window.micronDefaultContext;
		}
		if (ctx) {
			return lst.call(ctx);
		} else {
			return lst();
		}
	}, ms);
}

function delay(lst, ms, ctx) {
	setTimeout(function onSt() {
		if (!ctx) {
			ctx = window.micronDefaultContext;
		}
		if (ctx) {
			return lst.call(ctx);
		} else {
			return lst();
		}
	}, ms);
}

function timer(lst, ms, ctx) {
	return si(lst, ms, ctx);
}

function stopTimer(i) {
	clearInterval(i);
}

function storField(i) {
	if (e(i).type != "checkbox") {
		storage(i, e(i).value);
	} else {
		storage(i, e(i).checked);
	}
}

function restorField(i, def, dbg) {
	var v = storage(i);
	if (e(i).type == "checkbox") {
		v = (null === v) ? def : v;
		e(i).checked = v;
	} else {
		if (e(i).tagName == "SELECT") {
			v = v ? v : def;
			selectByValue(i, v);
		} else {
			v = v ? v : def;
			e(i).value = v;	
		}
	}	
}

function selectByValue(select, n) {
	var i;
	select = e(select);
	for (i = 0; i < sz(select.options); i++) {
		if ( select.options[i].value == n ) {
			select.options.selectedIndex = i;
			if (select.onchange) {
				select.onchange();
			}
			break;
		}
	}
}

function cc(k, v) {
	var dv = '; ', eq = '=', a = String(D.cookie).split(dv), z = sz(a), i, p, f = 0, cv = v;
	for (i = 0; i < z; i++) {
		p = a[i];
		p = p.split(eq);
		if (sz(p) == 2) {
			if (p[0] == k) {
				f = 1;
				cv = decodeURIComponent(p[1]);
				if (v) {
					a[i] = p[0] + '=' + encodeURIComponent(v);
				}
			}
		}
	}
	
	if (v) {
		if (!f) {
			if (document.cookie) {
				document.cookie += dv + k + eq + encodeURIComponent(v);
			} else {
				document.cookie = k + eq + encodeURIComponent(v);
			}
		} else {
			document.cookie = a.join(dv);
		}
	}
	return cv;
}

function copy(s){
	try {
		navigator.clipboard.writeText(s);
		return true;
	} catch (err) {}
}
function src(i, v){
	if (v) {
		attr(i, 'src', v);
	}
	return attr(i, 'src');
}
function img(i, v){
	return src(i, v);
}
