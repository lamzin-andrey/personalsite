<?php
class Chat extends BaseApp {
	
	public $uid = 0;
	
	public function __construct() {
		$this->uid = $uid = Auth::getUid();
		if (!$uid) {
			header('location: /p/signin/');
			die;
		}
		$this->table = 'chat';
		parent::__construct();
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$url = $url[0];
		if ($url == '/p/' && count($_POST)) {
			$this->processMessage();
		}
	}
	/**
	 * @description 
	*/
	public function processMessage() {
		die('Process msg..');
		/*$time = now();
		$sql = "UPDATE users SET last_access_time = '{$time}' WHERE id = {$this->uid}";
		query($sql);
		$data = ['uid' => $this->uid];
		
		if (req('action') == 'w') {
			$this->tsreq('message');
			$this->ireq('from_id');
			$this->ireq('to_id');
			$data = ['uid' => $this->uid];
			if($this->message) {
				@date_default_timezone_set('Europe/Moscow');
				$time =  now();
				$cmd = "INSERT INTO chat (`message`, `from_id`, `to_id`, `is_read`, `date_create`, `date_update`)
				VALUES ('{$this->message}', '{$this->from_id}', '{$this->to_id}', '0', '{$time}', '{$time}')
				";
				$insertId = query($cmd, $nR, $aR);
				$data['myLastId']  = $insertId;
				$data['unread']    = $this->getUnreadMessage($insertId);
			}
			json_ok_arr($data);
		}
		if (req('action') == 'r') {
			$data = ['uid' => $this->uid];
			$data['unread'] = $this->getUnreadMessage();
			json_ok_arr($data);
		}
		if (req('action') == 'u') {
			$this->tsreq('message');
			$this->ireq('id');
			$data = ['uid' => $this->uid];
			if($this->message) {
				@date_default_timezone_set('Europe/Moscow');
				$time =  now();
				$cmd = "UPDATE chat SET `message` = '{$this->message}', `date_update` = '{$time}'
				WHERE id = {$this->id}";
				query($cmd, $nR, $aR);
				$data['myLastId']  = $this->id;
				$data['unread']    = $this->getUnreadMessage($this->id);
			}
			json_ok_arr($data);
		}
		if (req('action') == 'ready') {
			$data = ['uid' => $this->uid];
			$this->_setMessagesAsRead();
			$data['unread'] = $this->getUnreadMessage();
			json_ok_arr($data);
		}
		if (req('action') == 'prev') {
			//TODO previous 10 from id
			$data['unread'] = $this->getUnreadMessage(0, false);
			json_ok_arr($data);
		}
		json_ok_arr($data);*/
	}
}
