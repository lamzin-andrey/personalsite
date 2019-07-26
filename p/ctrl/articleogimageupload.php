<?php
require __DIR__ . '/adminauthfileupload.php';

class ArticleOgImageUpload extends AdminAuthFileUpload {
	
	/** @property string _fileId  */
	protected $_fileId = 'og_image';
	
	public function __construct() {
		parent::__construct();
	}
}
