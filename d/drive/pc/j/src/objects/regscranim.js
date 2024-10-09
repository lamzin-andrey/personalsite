const RegScreenAnim = {
	setListeners() {
		let o = this;
		e('bShowAuthScreen4').onclick = () => {
			o.switchregisterForm('emailRegisterForm', 'loginForm');
		}

		e('bShowAuthScreen').onclick = () => {
			o.switchregisterForm('registerForm', 'loginForm');
		}

		e('bShowRegisterScreen').onclick = () => {
			o.switchregisterForm('loginForm', 'registerForm');
		}

		e('bShowResetScreen').onclick = () => {
			o.switchregisterForm('loginForm', 'rsetForm');
		}

		e('bShowAuthScreen2').onclick = () => {
			o.switchregisterForm('rsetForm', 'loginForm');
		}
	},
	switchregisterForm(idForHide, idForShow){
		let ivl, qLogAlpha = 1.0, logAlpha = 0.0,
			f = 'flex', o = 'opacity', d = 'display';
		hide('lbaloon');
		hide('rbaloon');
		hide('rsbaloon');
		hide('lpbaloon');
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
}

q("RegScreen")
