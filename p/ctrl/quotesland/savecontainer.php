<?php
include __DIR__ . '../../adminauthjson.php';
//include __DIR__ . '/articles/classes/articleslistcompiler.php';
//include_once DOC_ROOT . '/p/ctrl/classes/cstaticpagescompiler.php';

class CApplication {
	static public function getUid(){
		return sess('nuid');
	}
}

class ContainerPost extends AdminAuthJson {	
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		$this->table = 'hstor_container';
		$this->treq('name');
		$this->treq('color');
		
		$errors = [];
		
		if ($this->_validate($errors)) {
			$id = ireq('id');
			$sql = '';
			if ($id) {
				$sql = $this->updateQuery(('id = ' . $id));
				//die($sql);
			} else {
				$sql = $this->insertQuery(['user_id' => $this->uid]);
			}
			
			$newId = query($sql);
			if (!$id) {
				$id = $newId;
			}
			
			
			global $dberror;
			json_ok('id', $id);
		}
		json_error_arr(['errors' => $errors]);
	}
	
	/**
	 * @description Проверка, заполнены ли поля TODO test it
	*/
	private function _validate(array &$errors) : bool
	{
		//name
		$this->_setRequiredError('name', 'Container name', $errors);
		$this->_setRequiredError('color', 'Container color', $errors);
		
		
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
