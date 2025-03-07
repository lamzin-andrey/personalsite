<?php
include __DIR__ . '../../adminauthjson.php';
//include __DIR__ . '/articles/classes/articleslistcompiler.php';
//include_once DOC_ROOT . '/p/ctrl/classes/cstaticpagescompiler.php';

class CApplication {
	static public function getUid(){
		return sess('nuid');
	}
}

class FileDataPost extends AdminAuthJson {	
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		$this->table = 'hstor_file';
		$this->tsreq('name');
		$this->treq('file_name'); //*
		$this->treq('disk_name'); //*
		$this->ireq('convert_id'); //*
		$this->ireq('container_id'); //*
		$this->tsreq('artists');
		$this->tsreq('content_year');
		$this->tsreq('save_date');
		$this->tsreq('additional_info');
		$this->tsreq('additional_info_2');
		$this->breq('do_share');
		
		$errors = [];
		
		if ($this->_validate($errors)) {
			$id = ireq('id');
			$sql = '';
			if ($id) {
				$sql = $this->updateQuery(('id = ' . $id), [
					'content_year' => $this->_postDate($this->content_year),
					'save_date' => $this->_postDate($this->save_date)
				]);
			} else {
				$sql = $this->insertQuery([
					'user_id' => $this->uid,
					'content_year' => $this->_postDate($this->content_year),
					'save_date' => $this->_postDate($this->save_date)
				]);
			}
			
			$newId = query($sql);
			if (!$id) {
				$id = $newId;
			}
			
			
			global $dberror;
			json_ok('id', $id, 'dberror', $dberror);
		}
		json_error_arr(['errors' => $errors]);
	}
	
	/**
	 * @description Проверка, заполнены ли поля TODO test it
	*/
	private function _validate(array &$errors) : bool
	{
		$this->_setRequiredError('file_name', 'File name', $errors);
		$this->_setRequiredError('disk_name', 'Disk name', $errors);
	
		if ($this->convert_id == -1) {
			$errors['convert_id'] = l('You must select an envelope');
		}
		if ($this->container_id == -1) {
			$errors['container_id'] = l('You must select a box with disks');
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
	
	/**
	 * @description Перевод в дату
	*/
	private function _postDate(string $date) : bool
	{
		var_dump($date);
		die;
	}
}
