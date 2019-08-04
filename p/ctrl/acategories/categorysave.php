<?php
include __DIR__ . '/../adminauthjson.php';

class ArticleCategoryPost extends AdminAuthJson { //TODO у статей ArticlePost не то наследование!
	
	
	/** @property string */
	//public $ = '';
	
	
	public function __construct() {
		parent::__construct();
		$this->table = 'pages_categories';
		$this->treq('category_name');
		
		$errors = [];
		
		if ($this->_validate($errors)) {
			$id = ireq('id');
			
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
			json_ok('id', $id);
		}
		json_error_arr(['errors' => $errors]);
	}
	/**
	 * @description Проверка, заполнены ли поля
	*/
	private function _validate(array &$errors) : bool
	{
		//category_name
		$this->_setRequiredError('category_name', 'Category', $errors);
		
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
