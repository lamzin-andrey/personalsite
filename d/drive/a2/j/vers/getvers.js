window.addEventListener('load', gVonLoad);
function gVonLoad() {
	VersionReq.get(window, gVonVersion);
}
function gVonVersion(v){
	var updateState = storage('updateState');
	if (2 == updateState) {
		gVsetUpdateComplete(v);
	} else if(1 == updateState) {
		gVreload(v);
	} else {
		gvCheckVersion(v);
	}
}

function gVsetUpdateComplete(v) {
	storage('updateState', 'c');
	storage('vers', v);
}

function gVreload(v) {
	var cL = location.href;
	cL = cL.split('#')[0];
	storage('updateState', 2);
	goURL(cL + '?v=' + v);
}

function gvCheckVersion(v) {
	v = String(v);
	var curr = String(storage('vers'));
	if (v != curr && !isNull(curr)) {
		storage('updateState', 1);
		goURL(roota2 + '/newversion/?v=' + v);
	} else {
		if ($_GET['v'] != v) {
			storage('vers', v);
			goURL(HttpQueryString.setVariable(location.href, 'v', v));
		}
	}
}
