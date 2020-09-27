<?php

require_once __DIR__ . '/openapp.php';

class GetNewPdfTextTask extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		
		
		$data = query("SELECT * FROM {$this->table} WHERE `state` = 1 ORDER BY id LIMIT 10;");
		if (!is_array($data)) {
			$data = [];
		}
		
		$result = [];
		foreach ($data as $row) {
			$resultItem = [
				'insurance_policy_request_id' => $row['insurance_policy_request_id'],
				'links' => explode("\n", $row['links'])
			];
			$result[] = $resultItem;
		}
		
		json_ok_arr(['list' => $result]);
	}
}
