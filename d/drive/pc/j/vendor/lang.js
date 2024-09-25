window.jaqedLang = window.jaqedLang || {};
function L(s) {
	if (jaqedLang && jaqedLang[s]) {
		return jaqedLang[s];
	}
	return s;
}

window.addEventListener('load', setLocale, false);

function setLocale() {
	var ls = ee(document, 'legend'), i, sZ = sz(ls),
		textById, ph = 'placeholder';
	for (i = 0; i < sZ; i++) {
		if (ls[i].id) {
			ls[i].innerHTML = L(ls[i].id);
		}
	}
	
	setLocaleE('label');
	setLocaleE('span');
	//setLocaleE('div');
	
	ls = ee(document, 'input');
	sZ = sz(ls);
	for (i = 0; i < sZ; i++) {
		textById = L(ls[i].id);
		if (ls[i].type == 'button') {
			if (textById) {
				ls[i].value = textById;
			} else {
				textById = parseClassForButtonId(attr(ls[i], 'class'));
				ls[i].value = L(textById);
			}
		} else {
			if (textById) {
				attr(ls[i], ph, textById);
			} else {
				textById = parseClassForButtonId(attr(ls[i], 'class'));
				attr(ls[i], ph, L(textById));
			}
		}
	}
}

function isTranslateId(el) {
	var sc, i;
	if (!el) {
		return;
	}
	i = el.id;
	if (!i) {
		return;
	}
	sc = String(i).charAt(1);
	if(String(i).charAt(0) == 'h' && sc.toUpperCase() == sc) {
		return true;
	}
}

function setLocaleE(tag) {
	var ls, sZ;
	ls = ee(document, tag);
	sZ = sz(ls);
	for (i = 0; i < sZ; i++) {
		if (isTranslateId(ls[i])) {
			ls[i].innerHTML = L(ls[i].id);
		}
	}
}

function parseClassForButtonId(s) {
	var a = s.split(/\b/), i, w, ch;
	for (i = 0; i < a.length; i++) {
		w = a[i];
		if (w) {
			if (w[0] == 'b') {
				ch = w[1];
				if (ch === ch.toUpperCase()) {
					return w;
				}
			}
		}
	}
	return '';
}
v("s", "php.js")
