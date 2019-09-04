<?php
require __DIR__ . '/adminauthjson.php';
/**
 * @class ArticlesReorder  - переупорядочивание списка статей на одной странице
*/
class ArticlesReorder extends AdminAuthJson {
	public $uid = 0;
	
	public function __construct() {
		$this->table = 'pages';
		parent::__construct();
		$aIds = areq('a', 'intval');
		utils_header_utf8();
		if (count($aIds)) {
			$sIdList = join(',', $aIds);
			$min = intval( dbvalue('SELECT MIN(delta) FROM ' . $this->table . ' WHERE id IN(' . $sIdList . ')') );
			foreach ($aIds as $id) {
				query('UPDATE ' . $this->table . ' SET delta = ' . $min . ' WHERE id = ' . $id);
				$min++;
			}
			json_ok();
		}
		json_error('msg', l('Unable reorder article list: empty data'), 'a', print_r($aIds, 1) );
		
		
	}
}
