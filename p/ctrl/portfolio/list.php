<?php
include __DIR__ . '/../adminauth.php';

class ProductsEditor extends AdminAuth {
	
	public $pageHeading = 'Portfolio';
	
	/** @property string jsonData Данные для страницы в формате JSON*/
	public $jsonData = '';
	
	
	public function __construct() {
		$this->table = 'portfolio';
		$this->pageHeading = l('Portfolio');
		parent::__construct();
		$this->_setJsonData();
	}
	/**
	 * @description Установить $this->jsonData
	*/
	private function _setJsonData()
	{
		$a = ['portfolioCategories' => null];
		$safe = ($_REQUEST['xhr'] ?? -1);
		$_REQUEST['xhr'] = true;
		$a['portfolioCategories'] = query("SELECT id, parent_id, category_name AS name FROM portfolio_categories WHERE is_deleted = 0 ORDER BY delta");
		if ($safe == -1) {
			unset($_REQUEST['xhr']);
		} else {
			$_REQUEST['xhr'] = $safe;
		}
		$this->jsonData = json_encode($a);
	}
}
