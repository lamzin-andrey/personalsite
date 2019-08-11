<?php
require_once __DIR__ . '/../q/baseapp.php';
require_once __DIR__ . '/../q/auth.php';

class Profile extends BaseApp {
	public $photos = [];
	public function __construct() {
		$this->table = 'users';
		parent::__construct();
		$this->uid = $userId = Auth::getUid();
		if($userId) {
			$this->id = $userId;
			$this->rec();
			$this->photos = query("SELECT * FROM photos WHERE user_id = {$userId} AND is_deleted = 0 ORDER BY delta ASC");
		}
		BaseApp::setDataObject($this);
		$url = $_SERVER['REQUEST_URI'];
		if ($url == '/profile.jn' && count($_POST)) {
			$this->profileJson();
		}
	}
	/**
	 * @description 
	*/
	public function profileJson() {
		$this->tsreq('name');
		$this->tsreq('surname');
		//$this->tsreq('email');
		$this->tsreq('passwordLC', 'passwordLC');
		$this->tsreq('passwordC', 'passwordC');
		$this->tsreq('passwordL', 'password');
		$this->tsreq('agree');
		if (!$this->_validate($report)) {
			$report['info'] = 'validate';
			json_error_arr($report);
		}
		if(!utils_isJs()) {
			$userId = Auth::getUid();
			if(!$userId) {
				$userId = Auth::createUid();
			}
			if (trim($this->password)) {
				$this->request['password'] = md5($this->password);
			} else {
				unset($this->request['password']);
			}
			$cmd = $this->updateQuery('id = ' . $userId);
			
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
		/*if(!checkMail($this->email)) {
			$errors['email'] = l('invalid-email');
		}*/
		if (trim($this->passwordC)) {
			if ($this->passwordLC != $this->password) {
				$errors['passwordLC'] = l('passwords-is-not-equ');
			}
			$isValid = utils_checkPassword($this->password);
			if (!$isValid) {
				$errors['passwordL'] = l('password-very-easy');
			}
			if (!isset($errors['passwordLC']) && !isset($errors['passwordL'])) {
				if(!utils_isJs()) {
					$hash = dbvalue("SELECT password FROM users WHERE id = {$this->uid}");
					/*$errors['hash'] = $hash;
					$errors['hash2'] = Auth::hash($this->passwordC);
					$errors['pC'] = $this->passwordC;
					$errors['UIDd'] = $this->uid;*/
					if (!$hash || $hash != Auth::hash($this->passwordC)) {
						$errors['passwordC'] = l('user-wrong-password');
					} else {
						if (!$this->password) {
							$errors['passwordL']  = l('field-required', l('Password'));
						}
						if (!$this->passwordLC) {
							$errors['passwordLC'] = l('field-required', l('Password_repeat'));
						}
					}
				}
				
			}
		}
		if (count($errors) > 0) {
			$report['info'] = 'validate';
			$report['errors'] = $errors;
			json_error_arr($report);
			return false;
		}
		if (
				$this->name && $this->surname
				/*&& $this->email
				&& $this->passwordLC
				&& $this->password*/
		   ) {
			return true;
		}
		if (!$this->name) {
			$errors['name']       = l('field-required', l('First_name'));
		}
		if (!$this->surname) {
			$errors['surname']    = l('field-required', l('Last_name'));
		}
		/*if (!$this->email) {
			$errors['email']      = l('field-required', l('Email'));
		}*/
		
		$report['errors'] = $errors;
		json_error_arr($report);
		return false;
	}
}
//$app = new Profile();
//$app->run();
