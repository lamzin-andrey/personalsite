<?php

require_once __DIR__ . '/../q/baseapp.php';

class PayApp extends BaseApp{
	
	public $table = 'paytransactions';
	
	public function __construct()
	{
		//
		//parent::__construct(); Потому что токен пока не нужен
		$this->_actionPay();
	}
	
	private function _actionPay()
	{
		global $dberror;
		$this->treq('email');
		$this->treq('comment');
		$this->ireq('sum');
		
		$nEmailId = dbvalue("SELECT id FROM email WHERE `email` = '{$this->email}' LIMIT 1");
		if ($dberror) {
			die($dberror);
		}
		if (!$nEmailId) {
			$nEmailId = query("INSERT INTO emails (`email`) VALUES('{$this->email}') ON DUPLICATE KEY UPDATE email = '{$this->email}'");
			if ($dberror) {
				die($dberror);
			}
		}
		$sql = $this->insertQuery();
		if ($dberror) {
			die($dberror);
		}
		$nTrId = query($sql);
		json_ok('num', YAM, 'itr', $nTrId);
	}
}
new PayApp();
