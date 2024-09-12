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
