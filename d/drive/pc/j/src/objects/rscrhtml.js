function regScrLayout() {
	return `<h3>Quick Login</h3>
			<div class="lformw" id="emailRegisterForm" style="display:flex">
				
				<div  class="lform qenter">
					<div class="regTitle">������� ����</div>
					<div class="iwrapper rel" >
						<!-- -25 373, -79 131-->
						<div class="abs" id="lbaloon">
							<div class="rel">
								<img src="./i/reg/dogball.png" class="dogmsg">
								<div id="hMsg" class="abs">E-mail ���������� ��� ����������</div>
							</div>
						</div>
						
						<img src="./i/lock.png">
						<div class="w100">
							<input type="email" id="emailRE" placeholder="E-mail" class="emailRE">
							<div id="hSendedEmail" style="display:none" class="hSendedEmail">������ ���������� ��� �� &nbsp;<a id="resetLinkMailbox" href="#" target="_blank">Email</a></div>
						</div>
						<input type="hidden" id="apptypeRe" value="drv">
						<input type="hidden" id="tokenRE" value="x">
					</div>
					<div class="ma regchb">
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="agreeRE" >
							<!-- TODO ����� ������ ������ �������� ��� ����� �����-->
							<label for="agreeRE"><span id="hIAgreeRE">I agree with</span> <a target="_blank" href="/files/Politika_zashity_i_obrabotki_personalnyh_dannyh_2019-04-12.doc"><span id="hPolicyNameRE">��������� ������������������</span></a></label>
						</div>
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="isSubscribedRE" >
							<label for="isSubscribedRE" id="hIAgreeFromAuthorRE">
								� �������� �������� email �������� �� ������ �����
							</label>
						</div>
					</div>
				
					<div class="regbtnplace">
						<div>
							<input type="button" id="bRegisterNowRE" value="����" class="regbtn">
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
				<div  class="lform qenter">
					<div class="regTitle">���� �� ������</div>
					<div class="iwrapper-lp rel" >
						<!-- -25 373, -79 131-->
						<div class="abs" id="lbaloon">
							<div class="rel">
								<img src="./i/reg/dogball.png" class="dogmsg">
								<div id="hMsg" class="abs">E-mail ���������� ��� ����������</div>
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
					<div class="iwrapper-lp rel" >
						<div>
							<input type="password" id="_password" placeholder="������" class="password">
						</div>
					</div>
					<div class="ma regchb">
						<div class="cbwrapper-lp tl ml-2 arial">
							<input type="checkbox" id="_remember_me" checked>
							<label for="_remember_me" id="rememberMeLabel">��������� ����</label>
						</div>
					</div>
				
					<div class="regbtnplace">
						<div>
							<input type="button" id="bLogin" value="�����" class="regbtn">
						</div>
						<div class="tc mt-3">
							<input type="button" id="bShowRegisterScreen" value="�����������" class="regbtn">
						</div>
						<div class="tc mt-3">
							<input type="button" id="bShowResetScreen" value="����� ������" class="regbtn">
						</div>
					</div>
					
				</div>
				
				
				<!-- end -->
				
			</div>
		
			
			<h3>Form register </h3>
			<div class="lformw" id="regForm" style="display:none">
				
				<div  class="lform regf">
					<div class="regTitle" id="hRegisterTitle">�����������</div>
					
					<div class="twocol">
						<div class="lcol">
							<div class="iwrapper rel">
								<input type="text" id="register_form[name]" placeholder="���" class="username-r username-r-name">
							</div>
							<div class="iwrapper rel">
								<input type="text" id="register_form[surname]" placeholder="�������" class="username-r">
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
								<input type="password" id="register_form[passwordRepeat]" placeholder="������ ��������" class="password-r">
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
							<!-- TODO ����� ������ ������ �������� ��� ����� �����-->
							<label for="register_form[agree]">
								<span id="hIAgree">I agree with</span>
								<a target="_blank" href="/files/Politika_zashity_i_obrabotki_personalnyh_dannyh_2019-04-12.doc">
									<span id="hPolicyNameRE">��������� ������������������</span>
								</a>
							</label>
						</div>
						<div class="cbwrapper tl ml-2 arial">
							<input type="checkbox" id="register_form[isSubscribed]">
							<label for="register_form[isSubscribed]" id="hIAgreeFromAuthor">
								� �������� �������� email �������� �� ������ �����
							</label>
						</div>
					</div>
				
					<div class="regbtnplace">
						<div>
							<input type="button" id="bRegisterNow" value="����������������" class="regbtn">
						</div>
						<div class="tc mt-3">
							<input type="button" id="bShowAuthScreen" value="� ����� ������" class="regbtn">
						</div>
					</div>
					
				</div>
				
				<!-- end -->
				
				
			</div>
		
		
		
			<h3>Reset</h3>
			<div class="lformw" id="rsetForm" style="display:none">
				
				<div  class="lform qenter">
					<div class="regTitle">����� ������</div>
					<div class="iwrapper rel" >
						<img src="./i/lock.png">
						<input type="email" id="reset_password_form[email]" placeholder="E-mail" class="emailRE">
						<input type="hidden" id="apptype" value="drv">
						<input type="hidden" id="reset_password_form[_token]" value="x">
					</div>					
					<div class="reset-spacer">&nbsp;</div>
					<div class="regbtnplace">
						<div>
							<input type="button" id="bReset" value="�������� ������" class="regbtn">
						</div>
						<div class="tc mt-3">
							<input type="button" id="bShowAuthScreen2" value="� ����� ������"" class="regbtn">
						</div>
					</div>
					
				</div>
				
				<!-- end -->
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
q("RecentDir");
