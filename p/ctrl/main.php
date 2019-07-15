<?php
include __DIR__ . '/adminauth.php';

class ArticleEditor extends AdminAuth {
	
	public $pageHeading = 'Articles';
	
	public function __construct() {
		$this->table = 'articles';
		$this->pageHeading = l('Articles');
		parent::__construct();
	}
	
}
