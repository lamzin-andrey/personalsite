<?php
/**
 * @class ArticlesPage  - получение списка страниц для страницы админки
*/
class AdminAuthJson extends BaseApp {	
	
	public $uid = 0;
	
	
	public function __construct() {
		$this->uid = $uid = Auth::getUid();
		if (!$uid) {
			json_error('You have not access rights for this page!');//TODO localize!
		}
		if (!Auth::isAdmin()) {
			json_error('You have not access rights for this page!');
		}
		parent::__construct();
	}
}
