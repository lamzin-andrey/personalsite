<?php


class Signin extends BaseApp {
	
	public $isAuthform = true;
	public $isResetform = false;
	public $sFormBgImageCss = 'bg-login-image';
	
	public function __construct() {
		if (Auth::getUid()) {
			$url = '/';
			if (Auth::isAdmin()) {
				$url = '/p/';
			}
			utils_302($url);
			return;
		}
		$this->table = 'ausers';
		$this->isAuthform = true;
		$this->formHeading = 'SigninFormLabel';
		parent::__construct();
		$url = $_SERVER['REQUEST_URI'];
		if ($url == '/p/signin.jn/' && count($_POST)) {
			$this->signinJson();
		}
	}
	//die будет работать таким образом:
	//если window.pseudoRequest
	// то пытается декодировать данные в объект обратно, 
	// если получилось, то ок
	// далее вызывает текущий callback pseudoRequest, который по особенностям js может быть в один момент времени только один
	/**
	 * @description 
	*/
	public function signinJson() {
		$this->tsreq('email');
		$this->tsreq('passwordL', 'password');
		$this->breq('rememberMe');
		
		if(!utils_isJs()) {
			$pwdData = dbrow("SELECT password, guest_id FROM {$this->table} WHERE email = '{$this->email}' LIMIT 1", $nR);
			if($nR && Auth::hash($this->password) != $pwdData['password']) {
				json_error_arr(['errors' => ['passwordL' => l('user-wrong-password')]] );
			} elseif(!$nR) {
				json_error_arr(['errors' => ['email' => l('user-not-found')]]);
			} else {
				if ($this->rememberMe) {
					Auth::setCookie($pwdData['guest_id']);
					/*var_dump($_COOKIE);
					die;/**/
				} else {
					Auth::setCookieShort($pwdData['guest_id']);
				}
				if (Auth::isAdmin()) {
					json_ok_arr(['a' => 1]);
				}
				json_ok();
			}
		}
		json_error_arr(['errors' => ['passwordL' => l('user-wrong-password')]] );
	}
}
