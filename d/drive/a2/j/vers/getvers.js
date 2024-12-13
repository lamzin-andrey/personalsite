window.addEventListener('load', gVonLoad);
function gVonLoad() {
	VersionReq.get(window, gVonVersion);
}
function gVonVersion(v){
	var sv = storage('vers'), H = HttpQueryString;
	if (sv != v) {
		storage('vers', v);
		if (sv) {
			goURL(roota2 + '/newversion/?v=' + v);
		} else {
			goURL(H.setVariable(location.href, 'v', v));
		}
		return;
	}
	if ($_GET['v'] != v) {
		goURL(H.setVariable(location.href, 'v', v));
	}
}
