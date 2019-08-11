<?php
require __DIR__ . '/../adminauthjson.php';
/**
 * @class CategoryRemove  - удаление категории статьи
*/
class CategoryRemove extends AdminAuthJson {
	public $uid = 0;
	
	public function __construct() {
		$this->table = 'pages_categories';
		parent::__construct();
		
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = $url[0];
		$this->ireq('i', 'id');
		$bSuccess = $this->deleteHard();
		utils_header_utf8();
		if ($bSuccess) {
			json_ok('msg', l('Record was removed'), 'id', $this->id);
		}
		json_error('msg', l('Record with id not found'), 'id', $this->id);
	}
}
