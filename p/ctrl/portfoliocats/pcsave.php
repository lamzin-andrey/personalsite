<?php
include __DIR__ . '/../adminauthjson.php';

class ProudctCategoryPost extends AdminAuthJson {
	/** @property string */
	//public $ = '';
	
	
	public function __construct() {
		parent::__construct();
		$this->table = 'portfolio_categories';
		
		$this->ireq('parent_id');
		$this->tsreq('name', 'category_name');
		$this->ireq('id');
		
		$errors = [];
		
		
		if ($this->_validate($errors)) {
			
			if (!$this->category_name) {
				$this->request['category_name'] = $this->category_name = $this->request['name'] = 'New Item';
			}
			
			$id = $this->id;
			//$this->_setLogo();
			$sql = '';
			if ($id) {
				$sql = $this->updateQuery(('id = ' . $id), ['updated_at' => now()]);
				//die($sql);
			} else {
				$sql = $this->insertQuery([]);
			}
			
			$newId = query($sql);
			if (!$id) {
				$id = $newId;
			}
			json_ok('id', $id, 'name', $this->category_name, 'parent_id', $this->parent_id);
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
		//id
		$this->_setRequiredError('id', 'id', $errors);
		
		//parent_id
		$this->_setRequiredError('parent_id', 'Parent id', $errors);
		
		//content_block
		//$this->_setRequiredError('name', 'Category Name', $errors);
		
		
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
