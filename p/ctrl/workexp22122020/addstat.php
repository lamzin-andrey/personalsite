<?php

require_once __DIR__ . '/openapp.php';

class AddStat extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		
		
		
		$this->ireq('userId', 'user_id');
		$this->tsreq('source');
		$this->ireq('has_mark_model');
		$this->ireq('has_date_expired');
		$this->ireq('estimation_has_mark_model');
		
		$errors = [];
		
		
		if ($this->_validate($errors)) {
			$this->request['created_time'] = now();
			$this->request['updated_time'] = now();
			$sql = $this->insertQuery([]);
			
			$newId = query($sql);
			json_ok('id', $newId);
		}
		json_error_arr(['msg' => $errors]);
	}
	/**
	 * @description Проверка, заполнены ли поля
	*/
	private function _validate(array &$errors) : bool
	{
		$token = treq('token');
		if ($token == 'kLfIdls49nfkljghn58WL9HfKezrl55dfkjbdfk'){
			return true;
		}
		$errors = [
			'auth' => 'wrong token'
		];
		
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
