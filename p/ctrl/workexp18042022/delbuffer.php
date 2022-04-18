<?php

require_once __DIR__ . '/openapp.php';

class DelBuffer extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		
		if (!$_POST) {
			json_error('msg', 'post');
		}
		
		
		$this->tsreq('ls');
		$a = explode(',', $this->ls);
		$q = [];
		$sz = count($a);
		$nsz = 0;
		for ($i = 0; $i < $sz; $i++) {
			$n = intval($a[$i]);
			if ($n > 0) {
				$q[] = $n;
				++$nsz;
			}
		}
		$sql = 'Empty set';
		if ($nsz) {
			$sql = 'DELETE FROM exp_18042022 WHERE id IN(' . implode(',', $q) . ')';
			query($sql);
		}
		
		json_ok('sql', $sql);
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
