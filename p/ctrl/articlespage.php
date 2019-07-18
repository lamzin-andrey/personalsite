<?php
require __DIR__ . '/adminauthjson.php';
/**
 * @class ArticlesPage  - получение списка страниц для страницы админки
*/
class ArticlesPage extends AdminAuthJson {
	public $uid = 0;
	
	public function __construct() {
		$this->table = 'pages';
		parent::__construct();
		
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = $url[0];
		
		$pages = $this->getPage('page', ireq('length'), 'id, heading, url', ireq('start'));
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
}
