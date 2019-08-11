<?php
require_once __DIR__ . '/../q/baseapp.php';
require_once __DIR__ . '/../q/auth.php';
require_once __DIR__ . '/../classes/chatlib.php';
//require_once __DIR__ . '/cross/signupvalidator.php';

define('T', 120);
define('PP_ID1', 8);//8
define('PP_ID2', 7); //18 for local test

class ChatPPSession extends BaseApp {
	
	public $uid = 0;
	
	
	public function __construct() {
		$this->uid = $uid = Auth::getUid();
		if (!$uid) {
			header('location: /signin');
			die;
		}
		$this->table = 'p2psimple';
		parent::__construct();
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = $url[0];
		if ($url == '/mt.jn' && count($_POST)) {
			$this->_processRequestConnection();
		}
	}
	/**
	 * @description 
	*/
	private function _processRequestConnection() {
		if ($this->ireq('meet') == 1) {
			$this->_processMeet();
		}
		if ($this->ireq('flush') == 1) {
			$this->_processFlush();
		}
		if ($this->treq('offer')) {
			$this->_processOffer();
		}
		if ($this->treq('answer')) {
			$this->_processAnswer();
		}
	}
	/**
	 * @description Затирает данные ползователей
	 * @return @see _processMeet
	*/
	private function _processFlush() {
		$this->offer = $this->answer = '';
		if ($this->uid == PP_ID1 || $this->uid == PP_ID2) {
			query("UPDATE {$this->table} SET answer = '', offer = '' WHERE uid IN(" . PP_ID1 . "," . PP_ID2 . ")");
		}
		$this->_processMeet();
	}
	/**
	 * @description Если есть данные о том. что оба пользователя в сети, (с момента предыдущей активности прошло менее T секунд) 
	 *  и в запросе есть данные оoffer сохраняет их в БД
	 * @return @see _processMeet
	*/
	private function _processOffer() {
		$s = $this->offer;
		$uid = $this->uid;
		$aData = $this->_processMeet(false);
		$this->uid = $uid;
		//die('uid = ' . $this->uid);
		if ($this->uid == $aData[1]) {
			$this->id = $this->_getByUid($this->uid);
			$this->offer = $s;
			if ($this->id) {
				$sql = $this->updateQuery("id = " . $this->id);
			} else {
				$sql = $this->insertQuery();
			}
			//file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/data/tmp/sql.sql', $sql . "\n\n", FILE_APPEND);
			query($sql);
		}
		$aData[3] = $s;
		json_ok_arr($aData);
	}
	/**
	 * @description Если есть данные о том. что оба пользователя в сети, (с момента предыдущей активности прошло менее T секунд) 
	 *  и в запросе есть данные ответа на offer сохраняет их в БД
	 * @return @see _processMeet
	*/
	private function _processAnswer() {
		$answer = $this->answer;
		$uid = $this->uid;
		$aData = $this->_processMeet(false);
		$this->uid = $uid;
		if ($this->uid == $aData[2]) {
			$this->id = $this->_getByUid($this->uid);
			$this->answer = $answer;
			if ($this->id) {
				$sql = $this->updateQuery("id = " . $this->id);
			} else {
				$sql = $this->insertQuery();
			}
			//file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/data/tmp/sql.sql', $sql . "\n\n", FILE_APPEND);
			query($sql);
		}
		$aData[4] = $answer;
		json_ok_arr($aData);
	}
	/**
	 * @description Если есть данные о том. что оба пользователя в сети, (с момента предыдущей активности прошло менее T секунд) возвращает 1 в первом элементе массива
	 * @return array of integer первый элемент 1 если оба пользователя в сети, второй id того пользователя, который проявил активность первым, третий id второго пользователя
	*/
	private function _processMeet($doCreateJsonResponse = true) {
		$aData = $this->_getActiveResult();
		$i2 = '';
		
		//оба пользователя в сети и это - запрос от пользователя инициирующего разговор
		$uid = $this->uid;
		file_put_contents(__DIR__ . '/' . $uid . '.txt', $_SERVER['REMOTE_ADDR']);
		if ($aData[0] && $uid == $aData[1]) {
			//значит ему мы должны отправить ответ получившего предложение, если он есть
			$this->id = $this->_getByUid($aData[2]);//TODO только id return!
			$this->rec();
			$aData[3] = '';
			$aData[4] = isset($this->answer) ? $this->answer : '';
		}
		//оба пользователя в сети и это - запрос от пользователя ожидающего инициации разговора
		if ($aData[0] && $uid == $aData[2]) {
			//значит ему мы должны отправить предложение, если он есть
			$this->id = $this->_getByUid($aData[1]);
			$this->rec();
			$aData[3] = isset($this->offer) ? $this->offer : '';
			$aData[4] = '';
		}
		if ($uid == PP_ID1 && file_exists(__DIR__  . '/' . PP_ID2 . '.txt')) {
			$aData['uip'] = file_get_contents(__DIR__  . '/' . PP_ID2 . '.txt');
		}
		if ($doCreateJsonResponse) {
			json_ok_arr($aData);
		}
		return $aData;
	}
	/**
	 * @description Если есть данные о том. что оба пользователя в сети, (с момента предыдущей активности прошло менее T секунд) возвращает 1 в первом элементе массива
	 * @return array of integer первый элемент 1 если оба пользователя в сети, второй id того пользователя, который проявил активность первым, третий id второго пользователя
	*/
	private function _getActiveResult() {
		$rows = query('SELECT id, last_access_time FROM users WHERE id IN (' . PP_ID1 . ', ' . PP_ID2 . ') ORDER BY id ASC');
		$time = time();
		$aRes = [1, 0, 0];
		foreach ($rows as $i => $row ) {
			if ($time - strtotime($row['last_access_time']) > T) {
				$aRes[0] = 0;
			}
			$aRes[$i + 1] = $row['id'];
		}
		$aRes[1] = PP_ID1;
		$aRes[2] = PP_ID2;
		return $aRes;
	}
	
	private function _getByUid($id) {
		return (int)dbvalue("SELECT id FROM {$this->table} WHERE uid = {$id}");
	}
}
