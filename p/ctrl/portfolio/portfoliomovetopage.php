<?php
require __DIR__ . '/../classes/recordmovetopage.php';
/**
 * @class PortfolioMoveToPage  - перемещение записи-страницы в списке с одной страницы на другую
*/
class PortfolioMoveToPage extends RecordMoveToPage {
	public function __construct() {
		$this->table = 'portfolio';
		parent::__construct();
	}
}
