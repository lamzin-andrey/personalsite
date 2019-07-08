<?php
class Signup extends BaseApp {
	
	public $formHeading = '';
	
	public $sFormBgImageCss = 'bg-register-image';
	
	public $isAuthform = false;
	
	public function __construct() {
		
		$this->table = 'ausers';
		$this->formHeading = 'Register_now';
		parent::__construct();
		$url = $_SERVER['REQUEST_URI'];
		if ($url == '/p/signup.jn/' && count($_POST)) {
			$this->signupJson();
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
	public function signupJson() {
		$this->tsreq('name');
		$this->tsreq('surname');
		$this->tsreq('email');
		$this->tsreq('passwordLC', 'passwordLC');
		$this->tsreq('passwordL', 'password');
		$this->tsreq('agree');
		if (!$this->_validate($report)) {
			$report['info'] = 'validate';
			json_error_arr($report);
		}
		if(!utils_isJs()) {
			
			//TODO get id by guest_id from auth.js
			$userId = Auth::getUid();
			if(!$userId) {
				$userId = Auth::createUid();
			}
			//TODO UPDATE record by id
			$this->request['password'] = Auth::hash($this->password);
			$cmd = $this->updateQuery('id = ' . $userId);
			/*$report['info'] = 'validate';
			$report['email'] = $cmd;
			json_error_arr($report);/**/
			query($cmd, $nR, $aR);
		}
		json_ok();
	}
	/**
	 * @description
	 * @return bool
	*/
	private function _validate(&$report = []) {
		$errors = [];
		if(!checkMail($this->email)) {
			$errors['email'] = l('invalid-email');
		} elseif(!utils_isJs()){
			$id = dbvalue('SELECT id FROM ' . $this->table . ' WHERE email = \'' . $this->email . '\' LIMIT 1');
			if ($id) {
				$errors['email'] = l('user-already-exists', l('Email'));
			}
		}
		if ($this->passwordLC != $this->password) {
			$errors['passwordLC'] = l('passwords-is-not-equ');
		}
		$abc  = utils_abc();
		$uAbc = strtoupper($abc);
		$sz   = sz($this->password);
		$eLower = $eUpper = $eSign = 0;
		$isValid = 0;
		for ($i = 0; $i < $sz; $i++) {
			if ($eLower && $eUpper && $eSign) {
				$isValid = 1;
				break;
			}
			$eLower = $eLower ? $eLower : strpos($abc, charAt($this->password, $i));
			$eLower = $eLower === 0 ? 1 : $eLower;
			$eUpper = $eUpper ? $eUpper : strpos($uAbc, charAt($this->password, $i));
			$eUpper = $eUpper === 0 ? 1 : $eUpper;
			$eSign  = $eSign ? $eSign : strpos('0123456789', charAt($this->password, $i));
			$eSign  = $eSign === 0 ? 1 : $eSign;
			
		}
		if (!$isValid) {
			$errors['passwordL'] = l('password-very-easy');
		}
		if ($this->agree != 'true') {
			$errors['agree'] = l('agree-required');
		}
		if (count($errors) > 0) {
			$report['info'] = 'validate';
			$report['errors'] = $errors;
			json_error_arr($report);
			return false;
		}
		if (
				$this->name && $this->surname
				&& $this->email
				&& $this->passwordLC
				&& $this->password
				&& $this->agree
		   ) {
			return true;
		}
		if (!$this->name) {
			$errors['name']       = l('field-required', l('First_name'));
		}
		if (!$this->surname) {
			$errors['surname']    = l('field-required', l('Last_name'));
		}
		if (!$this->email) {
			$errors['email']      = l('field-required', l('Email'));
		}
		if (!$this->password) {
			$errors['passwordL']  = l('field-required', l('Password'));
		}
		if (!$this->passwordLC) {
			$errors['passwordLC'] = l('field-required', l('Password_repeat'));
		}
		if (!$this->agree) {
			$errors['agree']      = l('agree-required');
		}
		$report['errors'] = $errors;
		json_error_arr($report);
		return false;
	}
}
//$app = new Signup();
//$app->run();
