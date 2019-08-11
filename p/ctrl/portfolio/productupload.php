<?php
require __DIR__ . '/../adminauthfileupload.php';

class PortfolioFileUpload extends AdminAuthFileUpload {
	
	/** @property string _fileId  */
	protected $_fileId = 'productfile';
	
	public function __construct() {
		parent::__construct();
	}
	
	protected function _validateFile(array $photo, array &$report = []/*{}*/) : bool
	{
		$errors = [];
		if ($photo['error'] > 0) {
			$report['errors']['file'] = l('file_too_big');
			return false;
		}
		$isImg = false;
		$this->_path = utils_getFilePath(DOC_ROOT . '/d', $photo['tmp_name'], $photo['name'], $isImg, false, false);
		$this->_url = str_replace(DOC_ROOT, '', $this->_path);
		$aInfo = pathinfo($this->_path);
		$k = $aInfo['extension']; 
		if (in_array($k, $this->_dangerExtensions)) {
			$_REQUEST['xhr'] = 1;
			$report['errors']['file'] = l('file_is_executing_upload_denied');
			return false;
		}
		$this->_isImg = $isImg;
		return true;
	}
}
