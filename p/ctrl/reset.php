<?php
require_once __DIR__ . '/../../q/q/SampleMail.php';
require_once __DIR__ . '/../../q/q/Request.php';

class Reset extends BaseApp {
	
	public $isAuthform = false;
	
	public $isResetform = true;
	
	public $sFormBgImageCss = 'bg-password-image';
	
	
	public function __construct() {
		$this->formHeading = 'Reset-Password';
		$this->table = 'ausers';
		parent::__construct();
		$url = $_SERVER['REQUEST_URI'];
		if ($url == '/p/reset.jn/' && count($_POST)) {
			$this->resetJson();
		}
	}
	/**
	 * @description 
	*/
	public function resetJson()
	{
		$this->tsreq('email');
		
		if(!utils_isJs()) {
			$pwdData = dbrow("SELECT id, name, surname FROM {$this->table} WHERE email = '{$this->email}' LIMIT 1", $nR);
			if(!$nR) {
				json_error_arr(['errors' => ['email' => l('user-not-found')]]);
			} else {
				$pwd = Auth::generatePassword();
				$hPwd = Auth::hash($pwd);
				query("UPDATE {$this->table} SET password = '{$hPwd}' WHERE id = '{$pwdData['id']}'");
				$req = new Request();
				//$resp = $req->execute('http://gazel.me/egate', ['pwd' => 'rfk6pAnIuR', 'body' => 'Ваш пароль в нашем чате ' . $pwd . ', и не теряйте его больше!', 'email' => $this->email, 'subject' => l('reset-password')]);
				$resp = $req->execute('http://fastxampp.org/b/egate.php', ['pwd' => 'rfk6pAnIuR', 'body' => 'Ваш забытый пароль ' . $pwd . '. И не теряйте его больше!', 'email' => $this->email, 'subject' => l('reset-password')]);
				$r = false;
				if ($resp->responseStatus == 200) {
					$data = json_decode($resp->responseText);
					if ($data->r == true) {
						$r = true;
					}
				}
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
