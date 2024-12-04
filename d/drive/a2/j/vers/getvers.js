window.addEventListener('load', gVonLoad);
function gVonLoad() {
	VersionReq.get(window, gVonVersion);
}
function gVonVersion(v){
	if (storage('vers') !== v) {
		storage('vers', v);
		goURL(roota2 + '/newversion/?v=' + v);
		return;
	}
	if ($_GET['v'] != v) {
		goURL(HttpQueryString.setVariable(location.href, 'v', v));
	}
}
