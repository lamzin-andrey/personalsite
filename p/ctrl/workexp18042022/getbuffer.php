<?php

require_once __DIR__ . '/openapp.php';

class GetBuffer extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		
		$sql = 'SELECT id, `value`, `astr` FROM ' . $this->table . ' ORDER BY id ASC LIMIT 50;';
		$rows = query($sql);
		
		json_ok_arr(['list' => $rows]);
		
		return;
		
		$errors = [];
		
		
		if ($this->_validate($errors)) {
			$this->request['created_time'] = now();
			$this->request['updated_time'] = now();
			
			
			if ( strval($this->request['has_mark_model']) == '') {
				$this->request['has_mark_model'] = null;
			}
			
			if ( strval($this->request['has_date_expired']) == '') {
				$this->request['has_date_expired'] = null;
			}
			
			if ( strval($this->request['estimation_has_mark_model']) == '') {
				$this->request['estimation_has_mark_model'] = null;
			}
			
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
