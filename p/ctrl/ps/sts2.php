<?php

require_once __DIR__ . '/openapp.php';

class SetTs2 extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		
		$file = $_SERVER['DOCUMENT_ROOT'] . '/p/ctrl/ps/ts2.txt';
		@date_default_timezone_set('Europe/Moscow');
		$n = time();
		file_put_contents($file, $n);
		exit;
	}
	/**
	 * @description Проверка, заполнены ли поля
	*/
	private function _validate(array &$errors) : bool
	{
		return true;
		//id
		$this->_setRequiredError('id', 'id', $errors);
		
		//parent_id
		$this->_setRequiredError('parent_id', 'Parent id', $errors);
		
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
