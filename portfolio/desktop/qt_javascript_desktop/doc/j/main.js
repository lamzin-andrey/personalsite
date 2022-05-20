function main() {
	
	window.onresize = onResize;
	window.onkeyup = onKeyUp;
	onResize();
	Demo.init();
	try {
		Search.init();
	} catch(err) {
		alert(err);
	}
}
function onResize() {
	// for table
	console.log('I call');
	e('contentArea').style.height = (getViewport().h - 0) + 'px';
	e('contentArea').style.maxHeight = (getViewport().h - 32) + 'px';
	
	e('sidebarWrapper').style.height = (getViewport().h - 0) + 'px';
	e('sidebarWrapper').style.maxHeight = (getViewport().h - 18) + 'px';
	
	setTimeout(function() {
		e('contentArea').style.height = (getViewport().h - 0) + 'px';
		e('contentArea').style['max-height'] = (getViewport().h - 32) + 'px';
		
		e('sidebarWrapper').style.height = (getViewport().h - 0) + 'px';
		e('sidebarWrapper').style.maxHeight = (getViewport().h - 18) + 'px';
	}, 1000);
	
	
}

function onKeyUp(evt) {
    if (evt.ctrlKey) {
	switch(evt.keyCode) {
	    case 79:	//O
		// onClickChangeEnv();
		break;
	    case 81:	//Q
		onClickExitMenu();
		break;
	}
	    
    }
	if (evt.keyCode == 27 && window.mainMenuIsHide) {
		Qt.showMainMenu();
	}
}

function onClickExitMenu() {
	Qt.quit();
}

function onClickAbout() {
	alert('Version 3.1.1 pre-release');
}

window.onload = main;

