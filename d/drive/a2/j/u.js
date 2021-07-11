window.onload = initApp;
function initApp() {
	// styling
	var o = getViewport(),
		h = Math.round(o.h / 2) - 64;
	stl('im', 'margin-top', h + 'px');
	getAuthState();
}

function getAuthState() {
	Rest._token = '';
	Rest._get(onSuccessGetAuthState, '/sp/dast.json', onFailGetAuthState);
}
function onSuccessGetAuthState(data) {
	alert('Success');
}
function onFailGetAuthState(err) {
}

	
