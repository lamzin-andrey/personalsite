<?php

require_once __DIR__ . '/openapp.php';

class DrvEmailWanterPost extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		$this->table = 'drv_want_emails';
		
		$this->tsreq('email');
		
		
		$errors = [];
		
		
		if ($this->_validate($errors)) {
			$sql = "INSERT IGNORE INTO drv_want_emails            (`email`)           VALUES('{$this->email}');";
			query($sql);
			
			json_ok('msg', l('Thank-for-want-web-usb'), 'sql', $sql);
		}
		$aErr = [];
		foreach ($errors as $key => $sText) {
			$aErr[] = "<p>{$sText}</p>";
		}
		$errors = join(',', $aErr);
		json_error_arr(['msg' => $errors]);
	}
	/**
	 * @description Проверка, заполнены ли поля
	*/
	private function _validate(array &$errors) : bool
	{
		//email
		$this->_setRequiredError('email', 'email', $errors);
		
		$success = checkMail($this->email);
		if (!$success) {
			$errors['email'] = l('invalid-email', 0, l('email', 1));
		}
		
		$a = explode('.', $this->email);
		$dom = $a[count($a) - 1];
		if ('ru' !== $dom) {
			$errors['email'] = l('inostr-email', 0, l($this->email, 1));
		}
		
		if (count($errors)) {
			return false;
		}
		return true;
	}
	/**
	 * @description Установить ошибку Поле обязательно
	 * @param string $varname
	 * @param string $localizeKey
	 * @param array &$errors
	*/

	private function _setRequiredError(string $varname, string $localizeKey, array &$errors)
	{
		if (!strlen($this->$varname) ) {
			$errors[$varname] = l('field-required', 0, l($localizeKey, 1));
		}
	}
}
