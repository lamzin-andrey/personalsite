<?php
require __DIR__ . '/adminauthjson.php';
/**
 * @class HStorPageSearch  - получение списка  для страницы апки базы дисков
*/
class HStorPageSearch extends AdminAuthJson {
	public $uid = 0;
	
	
	public function __construct() {
		$this->table = 'hstor_file';
		parent::__construct();
		
		
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = $url[0];
		
		$this->_setSearchCondition();
		
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
	
	/**
	 * @description Установить фильтрацию по переданому слову
	*/
	public function _setSearchCondition()
	{
		$sWord = ($_GET['search']['value'] ?? '');
		if ($sWord) {
			$sWord = utils_cp1251($sWord);
			$this->condition = " AND (heading = '{$sWord}' OR heading LIKE('%{$sWord}%') OR content_block LIKE('%{$sWord}%'))";
		}
	}
}
