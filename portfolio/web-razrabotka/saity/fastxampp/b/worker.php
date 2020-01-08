<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mysql.php';
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/captcha/captcha.php';
require_once __DIR__ . '/SampleMail.php';

class App {
	public function __construct() {
		$this->_actionSend();
	}
	/**
	 * @description Отправка писем
	*/
	public function _actionSend() {
		@date_default_timezone_set('Europe/Moscow');
		$now = now();
		$rows = query("SELECT id, subject, body, email,  to_all FROM future_mails 
		WHERE is_deleted = 0 AND send_date <= '{$now}' LIMIT 50");
		foreach ($rows as $row) {
			var_dump($row);
			$r = false;
			if (checkMail($row['email'])) {
				$mail = new SampleMail();
				$mail->setSubject($row['subject']);
				$mail->setPlainText($row['body']);
				$mail->setAddressTo([$row['email'] => $row['email']]);
				$mail->setAddressFrom([ADMIN_EMAIL => ADMIN_EMAIL]);
				$r = $mail->send();
				var_dump($r);
			} else if($row['to_all'] == 1){
				$r = true;
			}
			if ($r) {
				query("UPDATE future_mails SET is_deleted = 1 WHERE id = {$row['id']}");
			}
		}
	}
	
	
}
new App();
