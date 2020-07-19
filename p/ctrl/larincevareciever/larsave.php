<?php

require_once __DIR__ . '/openapp.php';

class LarincevalogPost extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		
		
		$this->tsreq('log');
		
		
		$errors = [];
		
		
		if ($this->_validate($errors)) {//TODO
			
			$now = now();
			$logId = query("INSERT INTO larmessages (dt) VALUES('$now');");//TODO table
			$a = explode("\n", $this->log);
			if (count($a) == 0) {
				$a = explode("\r", $this->log);
			}
			if (count($a) == 0) {
				$a = [$this->log];
			}
			
			$n = 0;
			foreach ($a as $msg) {
				//query("INSERT INTO larmessages dt='$now'");
				$str = db_escape($msg);
				query("INSERT INTO larmessages_records (log_id, msg) VALUES('$logId', '$str');");
				$n++;
			}
			
			json_ok('id', $logId, 'insertReciords', $n);
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
