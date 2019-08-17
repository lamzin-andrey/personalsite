<?php
require_once __DIR__ . '/openapp.php';
/**
 * @class TrollKillerLogin  - Логин в TrollKiller
*/
class TrollKillerCheckAuth extends OpenApp {
	public $uid = 0;
	
	public function __construct() {
		parent::__construct();
		$uid = Auth::getUid();
		
		if ($uid) {
			json_ok('msg', l('Login success'), 'uid', $uid);
		}
		json_error('msg', l('User not found'));
	}
}
