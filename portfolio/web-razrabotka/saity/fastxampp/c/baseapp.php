<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mysql.php';
require_once __DIR__ . '/utils.php';

class BaseApp {
	/** @proerty string $table */
	public $table;
	
	/** @proerty array $request */
	public $request = [];
	
	public function req($key, $field = '', $varname = 'REQUEST') {
		$field = $field ? $field : $key;
		$this->$field = req($key, $varname);
		$this->request[$field] = $this->$field;
		return $this->$field;
	}
	
	public function tsreq($key, $field = '', $varname = 'REQUEST') {
		$field = $field ? $field : $key;
		$this->$field = strip_tags( trim(req($key, $varname) ) );
		$this->request[$field] = $this->$field;
		return $this->$field;
	}
	
	public function treq($key, $field = '', $varname = 'REQUEST') {
		$field = $field ? $field : $key;
		$this->$field = trim(req($key, $varname) );
		$this->request[$field] = $this->$field;
		return $this->$field;
	}
	
	public function ireq($key, $field = '', $varname = 'REQUEST') {
		$field = $field ? $field : $key;
		$this->$field = ireq($key, $varname);
		$this->request[$field] = $this->$field;
		return $this->$field;
	}
	
	public function breq($key, $field = '', $varname = 'REQUEST') {
		$field = $field ? $field : $key;
		$s = $this->tsreq($key, $field, $varname);
		$this->$field = ($s == 'true' ? true : false);
		return $this->$field;
	}
	
	/**
	 * @see db db_createInsertQuery
	*/
	public function insertQuery($config = [], &$options = null) {
		return db_createInsertQuery($this->request, $this->table, $config, $options);
	}
	
	/**
	 * @see db db_createInsertQueryExt
	*/
	public function insertQueryExt(&$data, $config = [], &$options = null) {
		$data = $this->request;
		return db_createInsertQueryExt($data, $this->table, $config, $options);
	}
	
	/**
	 * @see db db_createUpdateQuery
	*/
	public function updateQuery($condition, $config = [], &$options = null) {
		return db_createUpdateQuery($this->request, $this->table, $condition, $config, $options);
	}
	
	/**
	 * @see db db_createUpdateQueryExt
	*/
	public function updateQueryExt($condition, $config = [], &$options = null) {
		return db_createUpdateQueryExt($this->request, $this->table, $condition, $config, $options);
	}
	
	public function jsonError($messsage) {
		echo json_encode(['error' => $messsage]);
		exit;
	}
}

