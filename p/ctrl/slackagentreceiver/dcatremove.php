<?php
require_once __DIR__ . '/openapp.php';
/**
 * @class DemoCategoryRemove  - удаление продукта (demo)
*/
class DemoCategoryRemove extends OpenApp {
	public $uid = 0;
	
	public function __construct() {
		
		parent::__construct();
		
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = $url[0];
		
		$a = areq('idList', 'intval');
		
		if ($a) {
			$sIdList = join(',', $a);
			
			$check = dbvalue("SELECT id FROM {$this->table} WHERE id IN({$sIdList}) AND system = 1 LIMIT 1");
			if ($check) {
				json_error('msg', l('Owner of the site do not allow for delete  this category.  You can delete only you created  categories.'));
			}
			
			query("DELETE FROM {$this->table}  WHERE id IN({$sIdList})");
			json_ok('msg', l('Record was removed'), 'ids', $a);
		}
		json_error('msg', l('Wrong arguments'));
	}
}
