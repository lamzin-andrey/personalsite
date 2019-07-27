<?php
require __DIR__ . '/adminauthfileupload.php';

class ArticleInlineImageUpload extends AdminAuthFileUpload {
	
	/** @property string _fileId  */
	protected $_fileId = 'poster';
	
	public function __construct() {
		parent::__construct();
	}
}
