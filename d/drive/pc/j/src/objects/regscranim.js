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
			o.switchregisterForm('loginForm', 'resetForm');
		}

		e('bShowAuthScreen2').onclick = () => {
			o.switchregisterForm('resetForm', 'loginForm');
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
				f = 'register_form[name]';
				if ('loginForm' == idForShow) {
					f = "_username";
				}
				if ('resetForm' == idForShow) {
					f = 'reset_password_form[email]';
				}
				foc(f);
				clearInterval(ivl);
				stl(idForHide, d, 'none');
			}
		}, 44);
		
	}
}

q("RegScreen")
