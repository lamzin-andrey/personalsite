<?php
require_once __DIR__ . '/SampleMail.php'; 
require_once __DIR__ . '/config.php'; 

class CRemindAction {
	public $id;
	public $login;
	public function __construct() {
		if ($_POST['pwd'] != EGATE_PWD) {
			echo '{r:false}';
			exit;
		}
		$mail = new SampleMail();
		$mail->setAddressFrom([SITE_EMAIL => SITE_EMAIL]);
		$mail->setSubject($_POST['subject']);
		$mail->setAddressTo([$_POST['email'] => $_POST['email']]);
		$mail->setPlainText($_POST['body']);
		$r = $mail->send();
		echo json_encode(['r' => $r]);
		exit;
	}
	
}

$rform = new CRemindAction();
