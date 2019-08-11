<?php
//TODO авторизацию кк-то композиционно проверить...
require __DIR__ . '/fileupload.php';


class AdminAuthFileUpload extends FileUpload {
	
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
