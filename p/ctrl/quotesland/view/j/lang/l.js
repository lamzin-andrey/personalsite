function l(s) {
	return (window.L[s] ? window.L[s] : s);
}

function setDOM() {
	var ls = window.L, i, j, v, nm, n2 = 'placeholder';
	for (i in ls) {
		j = e(i);
		if (j) {
			nm = 'value';
			if (j.hasAttribute(nm)) {
				attr(j, nm, ls[i]);
			} else if (j.hasAttribute(n2)) {
				attr(j, n2, ls[i]);
			} else {
				j.innerHTML = ls[i];
			}
		}
	}
}

window.L = window.langEn;
var lang = storage('lang');
if (lang && window[lang]) {
	window.L = window[lang];
}
setDOM();

