<?php

require_once __DIR__ . '/q/config.php';
require_once __DIR__ . '/q/utils.php';
require_once __DIR__ . '/q/baseapp.php';


class ApkUpload extends BaseApp {
	private $_table = 'apk_apps';
	
	public function __construct() {
		$this->table = 'apk_apps';
		$this->_uploadAction();
	}
	
	public function _uploadAction() {
		$this->ireq('id');
		if ($this->_appExists()) {
			$this->_dropOldFiles();
			$this->_moveUploadedApk();
			query("UPDATE {$this->table} SET process_status = 3 WHERE id = {$this->id}");
		}
	}
	
	private function _moveUploadedApk(){
		$folder = DOC_ROOT . ROOT . 'u/out/' . $this->id;
		if (file_exists($folder) && isset($_FILES['file']['tmp_name'])) {
			move_uploaded_file($_FILES["file"]["tmp_name"], $folder . '/' . $_FILES["file"]["name"]);
		} else {
			$stdout = req('stdout');
			$stderr = req('stderr');
			if ($stdout) {
				file_put_contents($folder . '/stdout.log', $stdout);
			}
			if ($stderr) {
				file_put_contents($folder . '/stderr.log', $stderr);
			}
		}
	}
	
	private function _dropOldFiles() {
		$folder = DOC_ROOT . ROOT . 'u/out/' . $this->id;
		$ls = utils_scandir($folder);
		foreach ($ls as $f) {
			$aI = pathinfo($f);
			if (isset($aI['extension']) && ($aI['extension'] == 'apk' || $aI['extension'] == 'log') ) {
				$file = $folder . '/' . $f;
				@unlink($file);
			}
		}
	}
	
	private function _appExists() {
		$id = $this->id;
		$folder = DOC_ROOT . ROOT . 'u/out/' . $id;
		$row = $this->rec();
		if (file_exists($folder) && sz($row)) {
			return true;
		}
		return false;
	}
}
new ApkUpload();
