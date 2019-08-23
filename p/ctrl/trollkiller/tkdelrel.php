<?php
require_once __DIR__ . '/openapp.php';
/**
 * @class TrollKillerAddRelation  - Подписка на блэклист пользователя
*/
class TrollKillerDeleteRelation extends OpenApp {
	public $uid = 0;
	
	public function __construct() {
		
		parent::__construct();
		$this->table = 'trollkiller_banlists_rel';
		$uid = Auth::getUid();
		$this->ireq('n', 'subject_id');
		
		$sql = 'DELETE FROM ' . $this->table . ' WHERE
		
		`subject_id` = ' . $this->subject_id. '
		AND `client_id` = ' . $uid;
		
		query($sql);
		
		if ($uid) {
			json_ok();
		}
		json_error('msg', l('Need auth'));
	}
}
