function regScrLayout() {
	return '';
}


function setRegScrLayout() {
	try {
		appendChild(e('body'), 'div', regScrLayout(), {"class": "scr", "style": "display:none", id: "hRegisterScreen"});
	} catch(er) {
		alert(er);
	}
}

setFMgrLayout();
