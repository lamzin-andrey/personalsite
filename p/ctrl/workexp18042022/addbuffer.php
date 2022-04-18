<?php

require_once __DIR__ . '/openapp.php';

class AddBuffer extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		
		$this->tsreq('value');
		$this->request['astr'] = trim(treq('number'));
		
		$rawPost = file_get_contents('php://input');
		//file_put_contents('/home/andrey/log.log', print_r($_POST, 1) . "\n\nRasw post: \n" .  print_r($rawPost, 1) .  "\n");
		$sql = 'CREATE TABLE IF NOT EXISTS exp_18042022(
			`id` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
			`value` VARCHAR(1024),
			`astr`VARCHAR(64)
		)engine=MyIsam;';
		query($sql);
		
		$data = null;
		$sql = $this->insertQuery($data);
		
		query($sql);
		
		
		echo "OK\n";
		die($sql);
		die;
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
