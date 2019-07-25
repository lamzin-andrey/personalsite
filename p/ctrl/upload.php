<?php
//TODO candidate deprecated

class Upload extends BaseApp {
	/** @property _path */
	private $_path = '';
	
	public function __construct() {
		if (isset($_FILES['photo'])) {
			$this->_savePhoto($_FILES['photo']);
		}
	}
	
	private function _savePhoto($photo) {
		if ($this->_validatePhoto($photo, $errors)) {
			$success = move_uploaded_file($photo['tmp_name'], $this->_path);
			if ($success) {
				$s = str_replace(DOC_ROOT, '', $this->_path);
				$userId = Auth::getUid();
				if(!$userId) {
					$userId = Auth::createUid();
				}
				$time = now();
				$delta = dbvalue("SELECT max(delta) FROM photos");
				$photo['name'] = db_escape($photo['name']);
				$delta =  $delta ? ($delta + 1) : 1;
				query("INSERT INTO photos (`date_create`, `date_update`, `delta`, `name`, `user_id`, `path`) VALUES
				(
					'{$time}',
					'{$time}',
					'{$delta}',
					'{$photo['name']}',
					'{$userId}',
					'{$s}'
				)");
				global $dberror;
				json_ok('path', $s, 'preview', FormView::imguploaded($s), 'error', $dberror);
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
	
	private function _validatePhoto($photo, &$report = []/*{}*/) {
		$errors = [];
		if ($photo['error'] > 0) {
			$report['errors']['photo'] = l('file_too_big');
			return false;
		}
		$isImg = false;
		$this->_path = utils_getFilePath(DOC_ROOT . '/i', $photo['tmp_name'], $photo['name'], $isImg);
		if (!$isImg) {
			$report['errors']['photo'] = l('file-is-not-image');
			return false;
		}
		return true;
	}
}
