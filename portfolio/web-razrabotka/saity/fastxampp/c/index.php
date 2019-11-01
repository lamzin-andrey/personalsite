<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mysql.php';
require_once __DIR__ . '/utils.php';
//require_once __DIR__ . '/captcha/captcha.php';

class App {
	public function __construct() {
		$this->_actionPost();
	}
	/**
	 * @description запрос слова
	*/
	public function _actionPost() {
		$token = 'fmdslfsaldgfhskjhdfh';
		$script = '';
		if (isset($_COOKIE['uid'])) {
			$id = (int)dbvalue("SELECT id FROM secure_pad_users WHERE auth_id = '{$_COOKIE['uid']}'");
			if ($id) {
				$script = '<script>window.uid = ' . $id . ';</script>';
			}
		}
		require_once __DIR__ . '/index.tpl.php';
	}
	
	
}
new App();
