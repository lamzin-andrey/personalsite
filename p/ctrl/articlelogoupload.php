<?php
require __DIR__ . '/adminauthfileupload.php';

class ArticleLogoUpload extends AdminAuthFileUpload {
	
	/** @property string _fileId  */
	protected $_fileId = 'logotype';
	
	public function __construct() {
		parent::__construct();
	}
}
