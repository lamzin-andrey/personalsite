<?php
require __DIR__ . '/classes/recordmovetopage.php';
/**
 * @class ArticlesMoveToPage  - перемещение записи-страницы в списке с одной страницы на другую
*/
class ArticlesMoveToPage extends RecordMoveToPage {
	public function __construct() {
		$this->table = 'pages';
		parent::__construct();
	}
}
