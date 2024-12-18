function regScrLayout() {
	return `<h3>Quick Login</h3>
			<a class="ai ua" href="/files/Polzovatelskoe_soglashenie_2024-12-12.docx">
				<img src="/d/drive/pc/i/doc48.png">
				<div id="hUAName"></div>
			</a>
			<a class="ai pp" href="/files/Politika_zashity_i_obrabotki_personalnyh_dannyh_2024-12-12.doc">
				<img src="/d/drive/pc/i/doc48.png">
				<div id="hPPName"></div>
			</a>
			<a class="ai aa" href="/files/Soglasie_na_poluchenie_reklamy.docx">
				<img src="/d/drive/pc/i/doc48.png">
				<div id="hAAName"></div>
			</a>
			
			<div class="lformw" id="emailRegisterForm" style="display:flex">
				
				<div  class="lform qenter">
					<div class="regTitle">
						<span id="tRegTitle">Быстрый вход</span> ${regScrLSw()}
						<div class="cf"></div>
					</div>
					
					<div class="iwrapper rel" >						
						<div class="abs lbaloon" id="lbaloon" >
							<div class="rel">
								<img src="./i/reg/dogball2.png" class="lvatar" title="${L("Like a watchdog")}">
								<img src="./i/reg/msgcrn.png" class="hilow">
								<div  class="hMsg tHMsg"></div>
							</div>
						</div>
						
						<img src="./i/lock.png">
						<div class="w100">
							<input type="email" id="emailRE" placeholder="E-mail" class="emailRE">
							<div id="hSendedEmail" style="display:none" class="hSendedEmail"><span id="hLinkSendedPhraseStart"></span> &nbsp;<a id="resetLinkMailbox" href="#" target="_blank">Email</a></div>
						</div>
						<input type="hidden" id="apptypeRe" value="drv">
						<input type="hidden" id="tokenRE" value="x">
					</div>
					<div class="ma regchb">
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="agreeRE" >
							<label for="agreeRE"><span id="hIAgreeRE">I agree with</span> <a target="_blank" href="/files/Polzovatelskoe_soglashenie_2024-12-12.docx"><span id="hPolicyNameRE">Пользовательским сог</span></a></label>
						</div>
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="agreeCC" >
							<label for="agreeCC"><span id="hIAgreeCC">I accept all cookies.</span> <a target="_blank" href="/d/drive/a2/cookie/"><span id="hCCLink">More info about cookies</span></a></label>
						</div>
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="agreeAdvRE" >
							<label for="agreeAdvRE"><span id="hIAgreeAdvRE">I accept all cookies.</span> <a target="_blank" href="/files/Soglasie_na_poluchenie_reklamy.docx"><span id="hAdvDocLinkRE">More info about cookies</span></a></label>
						</div>
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="isSubscribedRE" >
							<label for="isSubscribedRE" id="hIAgreeFromAuthorRE">
								Я согласен получать email рассылку от автора сайта
							</label>
						</div>
					</div>
				
					<div class="regbtnplace">
						<div>
							<input type="button" id="bRegisterNowRE" value="Вход" class="regbtn">
						</div>
						<div class="tc mt-3">
							<input type="button" id="bShowAuthScreen4" value="Login and Password" class="regbtn">
						</div>
					</div>
					
				</div>
				
				<!-- end -->
				
				
			</div>
			
			<h3>Form LP Login </h3>
			<div class="lformw" id="loginForm" style="display:none">
				<div  class="lform qenterlf">
					<div class="regTitle">
						<span id="hLoginFrmHeading">Вход по паролю</span> ${regScrLSw()}
						<div class="cf"></div>
					</div>
					<div class="iwrapper-lp rel" >
						<div class="abs lbaloon" id="lpbaloon" >
							<div class="rel">
								<img src="./i/reg/dogball2.png" class="lvatar" title="${L("Like a watchdog")}">
								<img src="./i/reg/msgcrn.png" class="hilow">
								<div  class="hMsg tHMsg"></div>
							</div>
						</div>
						
						<div class="df">
							<img src="./i/lock.png">
							<input type="text" id="_username" placeholder="Login" class="username">
						</div>
						<input type="hidden" id="_csrf_token">
						<input type="hidden" id="apptype" value="drv">
						<input type="hidden" id="apptypeRe" value="drv">
						<input type="hidden" id="tokenRE" value="x">
					</div>
					
					<div class="gbox" id="lsucess"></div>
					<div class="iwrapper-lp rel" >
						<div>
							<input type="password" id="_password" placeholder="Пароль" class="password">
						</div>
					</div>
					<div class="ma regchb">
						<div class="cbwrapper-lp tl ml-2 arial">
							<input type="checkbox" id="_remember_me" checked>
							<label for="_remember_me" id="hRememberMeLabel">Запомнить меня</label>
						</div>
					</div>
				
					<div class="regbtnplace">
						<div>
							<input type="button" id="bLogin" value="Войти" class="regbtn">
						</div>
						<div class="tc mt-3">
							<input type="button" id="bShowRegisterScreen" value="Регистрация" class="regbtn">
						</div>
						<div class="tc mt-3">
							<input type="button" id="bShowResetScreen" value="Сброс пароля" class="regbtn">
						</div>
					</div>
					
				</div>
				
				
				<!-- end -->
				
			</div>
		
			
			<h3>Form register </h3>
			<div class="lformw" id="registerForm" style="display:none">
				
				<div  class="lform regf">
					<div class="regTitle">
						<span id="hRegisterTitle">Регистрация</span> ${regScrLSw()}
						<div class="cf"></div>
					</div>
					<div class="abs lbaloon" id="rbaloon" >
						<div class="rel">
							<img src="./i/reg/dogball2.png" class="lvatar" title="${L("Like a watchdog")}">
							<img src="./i/reg/msgcrn.png" class="hilow">
							<div  class="hMsg tHMsg"></div>
						</div>
					</div>
					
					<div class="twocol">
						<div class="lcol">
							<div class="iwrapper rel">
								<input type="text" id="register_form[name]" placeholder="Имя" class="username-r username-r-name">
							</div>
							<div class="iwrapper rel">
								<input type="text" id="register_form[surname]" placeholder="Фамилия" class="username-r">
							</div>
							<div class="iwrapper rel">
								<input type="email" id="register_form[email]" placeholder="E-mail" class="email-r">
							</div>
						</div><!-- / lcol-->
						
						<div class="rcol">
							<div class="iwrapper rel">
								<img src="./i/lock.png">
								<input type="text" id="register_form[username]" placeholder="Login" class="username-r">
							</div>
							<div class="iwrapper rel">
								<input type="password" id="register_form[passwordRaw]" placeholder="Password" class="password-r">
							</div>
							<div class="iwrapper rel">
								<input type="password" id="register_form[passwordRepeat]" placeholder="Пароль повторно" class="password-r">
								<input type="hidden" id="register_form[_token]">
							</div>
						</div><!-- / rcol-->
					</div>
					
					<div class="iwrapper rel" >
						<input type="hidden" id="apptypeRe" value="drv">
						<input type="hidden" id="tokenRE" value="x">
					</div>
					<div class="ma regchb">
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="register_form[agree]" >
							<label for="register_form[agree]">
								<span id="hIAgree">I agree with</span>
								<a target="_blank" href="/files/Politika_zashity_i_obrabotki_personalnyh_dannyh_2019-04-12.doc">
									<span id="hPolicyNameRE">Политикой конфендициальности</span>
								</a>
							</label>
						</div>
						
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="register_form[agreeCC]" >
							<label for="register_form[agreeCC]">
								<span id="hIAgreeCCRE">I agree with</span>
								<a target="_blank" href="/d/drive/a2/cookie/">
									<span id="hCCLinkRE"></span>
								</a>
							</label>
						</div>
						
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="register_form[agreeAdv]" >
							<label for="register_form[agreeAdv]">
								<span id="hIAgreeAdvRE">I agree with</span>
								<a target="_blank" href="/files/Soglasie_na_poluchenie_reklamy.docx">
									<span id="hAdvDocLinkRE"></span>
								</a>
							</label>
						</div>
						
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="register_form[isSubscribed]">
							<label for="register_form[isSubscribed]" id="hIAgreeFromAuthor">
								Я согласен получать email рассылку от автора сайта
							</label>
						</div>
					</div>
				
					<div class="regbtnplace">
						<div>
							<input type="button" id="bRegisterNow" value="Регистрироваться" class="regbtn">
						</div>
						<div class="tc mt-3">
							<input type="button" id="bShowAuthScreen" value="К форме логина" class="regbtn">
						</div>
					</div>
					
				</div>
				
				<!-- end -->
				
				
			</div>
		
		
		
			<h3>Reset</h3>
			<div class="lformw" id="resetForm" style="display:none">
				
				<div  class="lform qenter">
					<div class="regTitle">
						<span id="hResetPwdHead">Сброс пароля</span> ${regScrLSw()}
						<div class="cf"></div>
					</div>
					
					
					<div class="abs lbaloon" id="rsbaloon" >
						<div class="rel">
							<img src="./i/reg/dogball2.png" class="lvatar" title="${L("Like a watchdog")}">
							<img src="./i/reg/msgcrn.png" class="hilow">
							<div  class="hMsg tHMsg"></div>
						</div>
					</div>
					
					<div class="iwrapper rel" >
						<img src="./i/lock.png">
						<input type="email" id="reset_password_form[email]" placeholder="E-mail" class="emailRE">
						<div id="hSendedEmailRs" style="display:none" class="hSendedEmail"><span id="hLinkSendedPhraseStartRs"></span> &nbsp;<a id="resetLinkMailboxRs" href="#" target="_blank">Email</a></div>
						
						<input type="hidden" id="apptype" value="drv">
						<input type="hidden" id="reset_password_form[_token]" value="x">
					</div>					
					
					<div class="reset-spacer">&nbsp;</div>
					
					<div class="regbtnplace">
						<div>
							<input type="button" id="bReset" value="Сбросить пароль" class="regbtn">
						</div>
						<div class="tc mt-3">
							<input type="button" id="bShowAuthScreen2" value="К форме логина"" class="regbtn">
						</div>
					</div>
					
				</div>
				
				<!-- end -->
			</div>`;
}

function regScrLSw() {
	return `<div class="lfLng">
				<div class="block">
					<span>Ru</span>
					<img src="/d/drive/a2/clang/i/ru64.png">
					<div class="cf"></div>
				</div>
			</div>`;
}


function setRegScrLayout() {
	try {
		appendChild(e('body'), 'div', regScrLayout(), {"class": "scr lformbg", "style": "display:none", id: "hRegisterEScreen"});
	} catch(er) {
		alert(er);
	}
}

setRegScrLayout();
//setRegScrListeners();
RegScreenAnim.setListeners();

