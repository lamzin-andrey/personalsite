<?php
include __DIR__ . '/../adminauthjson.php';

class ProductSha256FileRemove extends AdminAuthJson {
	/** @property string */
	//public $ = '';
	
	
	public function __construct() {
		parent::__construct();
		$this->table = 'portfolio';
		
		$this->ireq('id');
		$this->tsreq('path');
		
		$errors = [];
		
		if ($this->id && $this->path) {
			$sFilePath = DOC_ROOT . '/' . $this->path;
			if (file_exists($sFilePath)) {
				@unlink($sFilePath);
			}
			if (file_exists($sFilePath)) {
				json_ok('wrn', l('Database clear, but unable drop phisical file'));
			}
			query('UPDATE ' . $this->table . ' SET product_file = ' . SQL_EMPTY_STR . ', sha256 = ' . SQL_EMPTY_STR . ' WHERE id = ' . $this->id);
			json_ok();
		}
		json_error('msg', l('Not found work id or file path'));
	}
}
