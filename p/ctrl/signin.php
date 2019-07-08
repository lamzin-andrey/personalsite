<?php

class Signin extends BaseApp {
	public function __construct() {
		
		$this->table = 'ausers';
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
		
		if(!utils_isJs()) {
			$pwdData = dbrow("SELECT password, guest_id FROM {$this->table} WHERE email = '{$this->email}' LIMIT 1", $nR);
			
			if($nR && Auth::hash($this->password) != $pwdData['password']) {
				json_error_arr(['errors' => ['passwordL' => l('user-wrong-password')]] );
			} elseif(!$nR) {
				json_error_arr(['errors' => ['email' => l('user-not-found')]]);
			} else {
				Auth::setCookie($pwdData['guest_id']);
			}
		}
		json_error_arr(['errors' => ['passwordL' => l('user-wrong-password')]] );
	}
}
