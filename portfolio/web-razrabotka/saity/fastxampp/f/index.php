<?php
require_once __DIR__ . '/q/config.php';
require_once __DIR__ . '/q/mysql.php';
require_once __DIR__ . '/q/utils.php';
require_once __DIR__ . '/q/auth.php';
require_once __DIR__ . '/q/lang.php';

class App {
	public function __construct() {
		$this->_actionPost();
	}
	/**
	 * @description запрос слова
	*/
	public function _actionPost() {
		$token = Auth::getToken();
		$root = '/portfolio/web-razrabotka/saity/fastxampp/f/';
		$script = '';
		/*if (isset($_COOKIE[AUTH_COOKIE_NAME])) {
			$id = (int)dbvalue("SELECT id FROM secure_pad_users WHERE auth_id = '{$_COOKIE['uid']}'");
			if ($id) {
				$script = '<script>window.uid = ' . $id . ';</script>';
			}
		}*/
		require_once __DIR__ . '/index.tpl.php';
	}
	
	
}
new App();
