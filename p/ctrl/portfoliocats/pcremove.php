<?php
require __DIR__ . '/../adminauthjson.php';
/**
 * @class ProductRemove  - удаление продукта (работы портфолио)
*/
class PortfoliCategoryRemove extends AdminAuthJson {
	public $uid = 0;
	
	public function __construct() {
		$this->table = 'portfolio_categories';
		parent::__construct();
		
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = $url[0];
		
		$a = areq('idList', 'intval');
		
		utils_header_utf8();
		if ($a) {
			$sIdList = join(',', $a);
			query("DELETE FROM {$this->table}  WHERE id IN({$sIdList})");
			json_ok('msg', l('Record was removed'), 'ids', $a);
		}
		json_error('msg', l('Wrong arguments'));
	}
}
