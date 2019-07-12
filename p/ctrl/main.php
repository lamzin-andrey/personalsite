<?php
include __DIR__ . '/adminauth.php';

class ArticleEditor extends AdminAuth {
	
	public function __construct() {
		$this->table = 'chat';
		parent::__construct();
	}
	
}
