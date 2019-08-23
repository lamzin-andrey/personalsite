<?php
require_once __DIR__ . '/openapp.php';
/**
 * @class TrollKillerAddRelation  - Подписка на блэклист пользователя
*/
class TrollKillerAddRelation extends OpenApp {
	public $uid = 0;
	
	public function __construct() {
		
		parent::__construct();
		$this->table = 'trollkiller_banlists_rel';
		$uid = Auth::getUid();
		$this->ireq('n', 'subject_id');
		
		$sql = 'INSERT INTO ' . $this->table . ' (`client_id`, `subject_id`) 
		VALUES(' . $uid . ', ' . $this->subject_id. ')
		ON DUPLICATE KEY UPDATE `subject_id` = `subject_id`';
		
		$nId = query($sql);
		
		if ($nId) {
			json_ok();
		}
		json_error('msg', l('Need auth'));
	}
}
