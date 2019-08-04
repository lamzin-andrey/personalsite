<?php
require __DIR__ . '/../adminauthjson.php';
/**
 * TODO он скорее всего не нужен, данные уже есть, но это удалить послде реализации редактирования портфолио
 * @class ArticlesCategory  - получение данных категории для редактирования
*/
class ArticlesCategory extends AdminAuthJson {
	public $uid = 0;
	
	public function __construct() {
		$this->table = 'pages_categories';
		parent::__construct();
		$this->ireq('id');
		$category = $this->rec();
		json_ok_arr($category);
	}
}
