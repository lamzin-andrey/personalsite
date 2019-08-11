<?php
include __DIR__ . '/../adminauth.php';

class ArticleCategoriesEditor extends AdminAuth {
	
	public $pageHeading = 'Article Categories';
	
	/** @property string jsonData Данные для страницы в формате JSON*/
	public $jsonData = '';
	
	
	public function __construct() {
		$this->table = 'pages_categories';
		$this->pageHeading = l('Articles Categories');
		parent::__construct();
		//$this->_setJsonData();
	}
	/**
	 * @description Установить $this->jsonData ??
	*/
	private function _setJsonData()
	{
		$a = ['pagesCategories' => null];
		$safe = ($_REQUEST['xhr'] ?? -1);
		$_REQUEST['xhr'] = true;
		$a['pagesCategories'] = query("SELECT id, category_name AS name FROM pages_categories WHERE is_deleted = 0 ORDER BY delta");
		if ($safe == -1) {
			unset($_REQUEST['xhr']);
		} else {
			$_REQUEST['xhr'] = $safe;
		}
		$this->jsonData = json_encode($a);
	}
	
}
