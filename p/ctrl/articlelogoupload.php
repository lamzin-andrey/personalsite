<?php
require __DIR__ . '/adminauthfileupload.php';

class ArticleLogoUpload extends AdminAuthFileUpload {
	
	/** @property string _fileId  */
	protected $_fileId = 'logotype';
	
	public function __construct() {
		parent::__construct();
	}
	
	protected function _savePhoto(array $photo)
	{
		$errors = [];
		if ($this->_validateFile($photo, $errors)) {
			$success = move_uploaded_file($photo['tmp_name'], $this->_path);
			if ($success) {
				utils_resizeAndAddBg($this->_path, $this->_path, 64, 64, [255, 255, 255], breq('alpha'));
				$s = str_replace(DOC_ROOT, '', $this->_path);
				json_ok('path', $this->_url, 'srcname', $photo['name']);
				return;
			}
		}
		if ($errors) {
			json_error_arr($errors);
			return;
		}
		json_error('msg', l('unable-upload-file'));
	}
}
