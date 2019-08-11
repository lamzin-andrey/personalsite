<?php

require_once __DIR__ . '/openapp.php';

class DemoCategoriesList extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		
		
		$data = query("SELECT id, parent_id, name FROM {$this->table} ORDER BY id");
		if (!is_array($data)) {
			$data = [];
		}
		
		json_ok_arr(['list' => $data]);
	}
}
