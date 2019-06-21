<?php
require_once __DIR__ . '/../q/baseapp.php';
require_once __DIR__ . '/../q/auth.php';
require_once __DIR__ . '/../q/SampleMail.php';
require_once __DIR__ . '/../q/Request.php';

class Reset extends BaseApp {
	public function __construct() {
		$this->table = 'users';
		parent::__construct();
		$url = $_SERVER['REQUEST_URI'];
		if ($url == '/reset.jn' && count($_POST)) {
			$this->resetJson();
		}
	}
	
	//die будет работать таким образом:
	//если window.pseudoRequest
	// то пытается декодировать данные в объект обратно, 
	// если получилось, то ок
	// далее вызывает текущий callback pseudoRequest, который по особенностям js может быть в один момент времени только один
	
	/**
	 * @description 
	*/
	public function resetJson() {
		$this->tsreq('email');
		
		if(!utils_isJs()) {
			$pwdData = dbrow("SELECT id, name, surname FROM {$this->table} WHERE email = '{$this->email}' LIMIT 1", $nR);
			if(!$nR) {
				json_error_arr(['errors' => ['email' => l('user-not-found')]]);
			} else {
				$pwd = Auth::generatePassword();
				$hPwd = Auth::hash($pwd);
				query("UPDATE users SET password = '{$hPwd}' WHERE id = '{$pwdData['id']}'");
				$req = new Request();
				//$resp = $req->execute('http://gazel.me/egate', ['pwd' => 'rfk6pAnIuR', 'body' => 'Ваш пароль в нашем чате ' . $pwd . ', и не теряйте его больше!', 'email' => $this->email, 'subject' => l('reset-password')]);
				$resp = $req->execute('http://fastxampp.org/b/egate.php', ['pwd' => 'rfk6pAnIuR', 'body' => 'Ваш пароль в нашем чате ' . $pwd . ', и не теряйте его больше!', 'email' => $this->email, 'subject' => l('reset-password')]);
				$r = false;
				if ($resp->responseStatus == 200) {
					$data = json_decode($resp->responseText);
					if ($data->r == true) {
						$r = true;
					}
				}
				/*$mail = new SampleMail();
				$mail->setAddressFrom(['admin' => ADMIN_EMAIL]);
				$mail->setSubject(l('reset-password'));
				$mail->setAddressTo([$this->email => $this->email]);
				$mail->setPlainText('Ваш пароль в нашем чате ' . $pwd . ', и не теряйте его больше!');
				$r = $mail->send();*/
				if ($r) {
					json_ok('msg', l('send-reset-success'));
				} else {
					json_error('msg', l('send-reset-fail-try-again'));
				}
			}
		}
		json_ok();
	}
}
