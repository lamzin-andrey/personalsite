<?php
require_once __DIR__ . '/openapp.php';
/**
 * @class TrollKillerLogin  - Логин в TrollKiller
*/
class TrollKillerLogin extends OpenApp {
	public $uid = 0;
	
	public function __construct() {
		
		parent::__construct();
		
		$this->tsreq('l');
		$this->tsreq('p');
		
		$sql = "SELECT id, password, guest_id FROM {$this->table} WHERE email = '{$this->l}'";
		$row = dbrow($sql);
		//print_r($this->l, 1); die;
		$sHash = a($row, 'password');
		if ($row && $sHash) {
			if (Auth::hash($this->p) == $sHash) {
				Auth::setCookie($row['guest_id']);
				json_ok('msg', l('Login success'), 'uid', $row['id']);
			}
			
		}
		json_error('msg', l('User not found'));
	}
}
