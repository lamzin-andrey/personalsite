<?php
require_once __DIR__ . '/q/config.php';

class UserApp {
	
	private $_table = 'apk_apps';
	
	public $id = 0;
	
	public function getAppId($uid) {
		$id = 0;
		$uid = (int)$uid;
		if (isset($_COOKIE[APP_COOKIE_NAME])) {
			$t = $this->_table;
			$cname = $_COOKIE[APP_COOKIE_NAME];
			$this->id = $id = (int)dbvalue("SELECT id FROM {$t} WHERE uid = {$uid} AND  app_cookie = '{$cname}'");
		}
		return $id;
	}
	/**
	 * 
	*/
	public function createApp($uid) {
		if ($uid) {
			$now = now();
			$table = $this->_table;
			$appCookie = (int)dbvalue("SELECT max(app_сookie) FROM {$table} WHERE uid = {$uid}");
			$appCookie++;
			$query = "INSERT INTO {$table}
			 (
			 uid,
			 date_create,
			 date_update,
			 app_cookie
			 )
			 VALUES
			 (
			 '{$uid}',
			 '{$now}',
			 '{$now}',
			 {$appCookie}
			 
			 )";
			$id = query($query);
			if ($id) {
				mkdir(DOC_ROOT . ROOT . 'u/res/' . $id, 0777);
				mkdir(DOC_ROOT . ROOT . 'u/ant/' . $id, 0777);
				mkdir(DOC_ROOT . ROOT . 'u/out/' . $id, 0777);
				setcookie(APP_COOKIE_NAME, $appCookie, time() + 365 * 24 * 3600, '/');
			}
			$this->id = $id;
			return $id;
		}
	}
	
	public function addInQueue() {
		if ($this->id) {
			$files = $this->_createFileLIst();
			query("UPDATE {$this->_table} SET process_status = 1, filelist = '{$files}' WHERE id = {$this->id}", $n, $a);
			if ($a > 0) {
				return true;
			} else {
				$v = dbvalue("SELECT process_status FROM {$this->_table} WHERE id = {$this->id}");
				if ($v == 1) {
					return true;
				}
			}
		}
		return false;
	}
	
	public function dropFromQueue() {
		if ($this->id) {
			$files = $this->_createFileLIst();
			query("UPDATE {$this->_table} SET process_status = 0, filelist = '{$files}' WHERE id = {$this->id}", $n, $a);
			if ($a > 0) {
				return true;
			} else {
				$v = dbvalue("SELECT process_status FROM {$this->_table} WHERE id = {$this->id}");
				if ($v == 0) {
					return true;
				}
			}
		}
		return false;
	}
	
	private function _createFileList() {
		if ($this->id) {
			$pre = DOC_ROOT . ROOT . 'u/ant/' . $this->id;
			$rc  = DOC_ROOT . ROOT . 'u/res/' . $this->id;
			$arr = [
				'id' => $this->id,
				'n' => '',
				'd' => '',
				'j' => '0',
				'l' => $this->_grabDir($pre . '/', $pre),
				'z' => $this->_grabDir($rc . '/', $rc),
			];
			
			return json_encode($arr);
		}
		return '{}';
	}
	
	private function _grabDir($root, $dir, $result = []) {
		$list = utils_scandir($dir);
		foreach($list as $f) {
			$file = $dir . '/' . $f;
			if (is_dir($file)) {
				$result = $this->_grabDir($root, $file, $result);
			} else {
				$result[] = str_replace($root, '', $file);
			}
		}
		return $result;
	}
}
