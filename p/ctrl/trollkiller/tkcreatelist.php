<?php
require_once __DIR__ . '/openapp.php';
require_once __DIR__ . '/classes/jsongen.php';

/**
 * @class TrollKillerLogin  - Логин в TrollKiller
*/
class TrollKillerCreateList extends OpenApp {
	public $uid = 0;
	
	public function __construct() {
		parent::__construct();
		$uid = Auth::getUid();
		
		if ($uid) {
			JSONGen::create($uid);
			json_ok();
		}
		json_error('msg', l('User not found'));
	}
}
