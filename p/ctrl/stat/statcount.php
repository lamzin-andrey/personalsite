<?php
include __DIR__ . '/openapp.php';

class StatCount extends OpenApp {
	/** @property string $url страницы, с котрорй был сделан запрос */
	public $url = '';
	
	/** @property int id идентификатор ресурса */
	public $id = 0;
	
	/** @property int $type тип ресурса, статья или работа в портфолио, см. константы*/
	public $type = 0;
	
	const RES_TYPE_PORTFOLIO = 1;
	
	const RES_TYPE_ARTICLE = 2;
	
	
	public function __construct() {
		parent::__construct();
		
		$this->tsreq('url');
		$nSrcId = $this->ireq('id');
		$this->ireq('type');
		$errors = [];
		
		if ($this->type != static::RES_TYPE_ARTICLE && $this->type != static::RES_TYPE_PORTFOLIO) {
			json_error('msg', l('Undefined res type'));
		}
		
		$this->table = 'portfolio';
		if ($this->type == static::RES_TYPE_ARTICLE) {
			$this->table = 'pages';
		}
		
		if (!$this->id && $this->url) {
			$this->id = intval( dbvalue('SELECT id FROM ' . $this->table . ' WHERE url = \'' . $this->url . '\'') );
		}
		
		if (!$this->id) {
			json_error('msg', l('Undefined res id'), 'id', $nSrcId, 'url', $this->url);
		}
		
		$sCookieName = 's';
		$sCookie = $_COOKIE[$sCookieName] ?? '';
		if (!$sCookie) {
			$sCookie = sha1(time() . uniqid()  . $_SERVER['REQUEST_URI'] . $_SERVER['REMOTE_ADDR']);
			setcookie($sCookieName, $sCookie, time() + 365 * 24 * 3600, '/', '', false, true);
		}
		
		$dt = date('Y-m-d');
		if (!intval( dbvalue('SELECT id FROM stat_filter WHERE cookie = \'' . $sCookie . '\' AND res_id = ' . $this->id . ' AND type = ' . $this->type . ' AND _date = \'' . $dt . '\' LIMIT 1') )) {
			query('INSERT INTO stat_filter (`cookie`, `res_id`, `type`, `_date`) 
						VALUES (\'' . $sCookie . '\', ' . $this->id . ', ' . $this->type . ', \'' . $dt . '\')');
			$sqlUpdate = 'UPDATE ' . $this->table . ' SET rating = rating + 1 WHERE id = ' . $this->id;
			global $dberror;
			$dberror = '';
			query($sqlUpdate);
			json_ok('sql', $sqlUpdate, 'dberror', $dberror);
		}
		json_ok();
	}
	
	public function breq($key, $field = '', $varname = 'REQUEST')
	{
		$field = $field ? $field : $key;
		$s = $this->tsreq($key, $field, $varname);
		$this->$field = ($s == 'true' ? true : false);
		if ($this->$field) {
			$this->request[$field] = $this->$field = 1;
		} else {
			$this->request[$field] = $this->$field = 0;
		}
		return $this->$field;
	}
}
