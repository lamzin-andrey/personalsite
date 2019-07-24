<?php

class FileUpload extends BaseApp {
	/** @property _path */
	private $_path = '';
	
	/** @property _url Здесь ссылка на файл (или файловый путь) для чата */
	private $_url = '';
	
	/** @property bool _isImg  */
	private $_isImg = false;
	
	/** @property string _fileId */
	protected $_fileId = '';
	
	/** @property array _dangerExtensions */
	protected $_dangerExtensions = ['php', 'html', 'sh'];
	
	public function __construct() {
		parent::__construct();
		
		$this->table = 'user_media';
		$aFileData = ($_FILES[($this->_fileId . 'FileImmediately')] ?? []);
		if (!$aFileData) {
			$aFileData = ($_FILES[($this->_fileId . 'FileDeffer')] ?? []);
		}
		
		if (isset($aFileData)) {
			$this->_savePhoto($aFileData);
		}
	}
	
	private function _savePhoto(array $photo)
	{
		$errors = [];
		if ($this->_validateFile($photo, $errors)) {
			$success = move_uploaded_file($photo['tmp_name'], $this->_path);
			if ($success) {
				$s = str_replace(DOC_ROOT, '', $this->_path);
				global $dberror;
				json_ok('path', $this->_url, 'error', $dberror);
				return;
			}
		}
		if ($errors) {
			json_error_arr($errors);
			return;
		}
		json_error('msg', l('unable-upload-file'));
		//return;
	}
	
	private function _saveAudioData($photo) {
		$userId = Auth::getUid();
		if(!$userId) {
			$userId = Auth::createUid();
		}
		$time = now();
		$delta = dbvalue("SELECT max(delta) FROM {$this->table}");
		$photo['name'] = db_escape($photo['name']);
		$delta =  $delta ? ($delta + 1) : 1;
		query("INSERT INTO {$this->table} (`date_create`, `date_update`, `delta`, `name`, `user_id`, `path`) VALUES
		(
			'{$time}',
			'{$time}',
			'{$delta}',
			'{$photo['name']}',
			'{$userId}',
			'{$this->_url}'
		)");
	}
	
	private function _validateFile(array $photo, array &$report = []/*{}*/) : bool
	{
		$errors = [];
		if ($photo['error'] > 0) {
			$report['errors']['file'] = l('file_too_big');
			return false;
		}
		$isImg = false;
		$this->_path = utils_getFilePath(DOC_ROOT . '/i', $photo['tmp_name'], $photo['name'], $isImg, false, false);
		$this->_url = str_replace(DOC_ROOT, '', $this->_path);
		$aInfo = pathinfo($this->_path);
		$k = $aInfo['extension']; 
		if (in_array($k, $this->_dangerExtensions)) {
			$report['errors']['file'] = l('file_is_executing_upload_denied');
			return false;
		}
		$this->_isImg = $isImg;
		return true;
	}
}
