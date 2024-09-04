e('bShowAuthScreen4').onclick = function(){
	switchRegForm('qLogForm', 'logForm');
}

e('bShowAuthScreen').onclick = function(){
	switchRegForm('regForm', 'logForm');
}

e('bShowRegisterScreen').onclick = function(){
	switchRegForm('logForm', 'regForm');
}

e('bShowResetScreen').onclick = function(){
	switchRegForm('logForm', 'rsetForm');
}

e('bShowAuthScreen2').onclick = function(){
	switchRegForm('rsetForm', 'logForm');
}


function switchRegForm(idForHide, idForShow){
	var ivl, qLogAlpha = 1.0, logAlpha = 0.0,
		f = 'flex', o = 'opacity', d = 'display';
	
	stl(idForHide, o, qLogAlpha);
	stl(idForShow, o, logAlpha);
	
	ivl = setInterval(() => {
		stl(idForHide, d, f);
		stl(idForShow, d, f);
		qLogAlpha -= .1;
		logAlpha += .1;
		
		if (qLogAlpha < 0 || logAlpha >= 0.9) {
			qLogAlpha = 0;
			logAlpha = 1;
		}
		
		stl(idForHide, o, qLogAlpha);
		stl(idForShow,  o, logAlpha);
		if (logAlpha == 1) {
			clearInterval(ivl);
			stl(idForHide, d, 'none');
		}
	}, 44);
	
}
