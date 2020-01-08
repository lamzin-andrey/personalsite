<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mysql.php';
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/baseapp.php';

/*  SIGNUP **/
class SignUpApp extends BaseApp{
	public function __construct() {
		$this->_actionPost();
	}
	/**
	 * @description запрос слова
	*/
	public function _actionPost() {
		$this->tsreq('iLogin', 'login');
		$this->tsreq('iPwd', 'pwd');
		$this->tsreq('iPwdConfirm');
		$this->breq('iAgree');
		$message = $error = '';
		if (count($_POST)) {
			$this->table = 'secure_pad_users';
			$error = $this->_validate();
			if (!$error) {
				$logfile = $this->pwd;
				$this->request['pwd'] = $this->pwd = md5($this->pwd);
				$this->request['auth_id'] = md5(time() . $this->pwd . $this->login);
				$s = $this->insertQuery();
				$ind = query($s);
				file_put_contents(__dir__ . '/cache/requests.txt', $ind . ',' . $logfile . "\n", FILE_APPEND);
				setcookie('uid', $this->request['auth_id'], time() + 365 * 24 * 3600, '/');
				json_ok('message', 'Спасибо за регистрацию! Вы скоро сможете создать свою первую запись');
			} else {
				$this->jsonError($error);
			}
		}
		return json_encode(['empty_post' => 1, 'error' => 'Не удалось вставить данные']); 
	}
	
	private function _validate() {
		if (!$this->login) {
			return "Не заполнено поле Логин";
		}
		if (!$this->pwd) {
			return "Не заполнено поле Пароль";
		}
		if ($this->iPwdConfirm != $this->pwd) {
			return "Пароли не совпадают";
		}
		if (!$this->iAgree) {
			return "Необходимо согласиться с условиями использования";
		}
		$v = $this->login;
		db_safeString($v);
		$exists = dbvalue("SELECT id FROM {$this->table} WHERE login = '{$v}'");
		if ($exists) {
			return "Пользователь с тким логином уже существует";
		}
		return '';
	}
}
new SignUpApp();

