<?php
require __DIR__ . '/../adminauthjson.php';
/**
 * @class ArticlesCategories  - получение списка категорий статей для страницы админки
*/
class ArticlesCategories extends AdminAuthJson {
	public $uid = 0;
	
	
	public function __construct() {
		$this->table = 'pages_categories';
		parent::__construct();
		
		
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = $url[0];
		
		$this->_setSearchCondition();
		
		$pages = $this->getPage('page', ireq('length'), 'id, category_name', ireq('start'));
		$nTotal = $this->getTotal();
		utils_header_utf8();
		$aResult = [
			'recordsTotal' => $nTotal,
			'recordsFiltered' => $nTotal,
			'draw' => ireq('draw'),
			'data' => $pages
		];
		json_ok_arr($aResult);
		
	}
	
	/**
	 * @description Установить фильтрацию по переданому слову
	*/
	public function _setSearchCondition()
	{
		$sWord = ($_GET['search']['value'] ?? '');
		if ($sWord) {
			$sWord = utils_cp1251($sWord);
			$this->condition = " AND (category_name = '{$sWord}' OR category_name LIKE('%{$sWord}%'))";
		}
	}
}
