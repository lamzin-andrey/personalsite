<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mysql.php';
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/captcha/captcha.php';
require_once __DIR__ . '/SampleMail.php';

class App {
	public function __construct() {
		$this->_actionPost();
	}
	/**
	 * @description ������ �����
	*/
	public function _actionPost() {
		$subject = req('subject');
		$subject = trim($subject);
		
		$email = req('email');
		$email = trim($email);
		
		
		$toAllChecked = '';
		$toAll = ireq('toAll');
		$toAll = $toAll ? 1 : 0;
		$toAllChecked = $toAll ? ' checked="checked" ' : '';
		
		
		$body = req('body');
		$body = trim($body);
		
		$num = req('code');
		$num = trim($num);
		
		$date = req('date');
		$date = trim($date);
		if (!$date) {
			date_default_timezone_set('Europe/Moscow');
			$date = date('Y-m-d', time() + 72*3600);
		}
		$message = $error = '';
		if (count($_POST)) {
			$error = $this->_validate($subject, $email, $toAll, $body, $num, $date);
			if (!$error) {
				$data = [
					'subject'	=>	utils_utf8($subject),
					'email'	=>	$email,
					'body'	=>	utils_utf8($body),
					'to_all'	=>	$toAll,
					'send_date'	=>	$date . ' 00:00:00'
				];
				$s = db_createInsertQuery($data, 'future_mails');
				query($s);
				/*CREATE TABLE IF NOT EXISTS `future_mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '��������� ����.',
  `email` varchar(64) DEFAULT NULL COMMENT 'email',
  `subject` varchar(128) DEFAULT NULL COMMENT 'Subject message',
  `is_deleted` int(11) DEFAULT '0' COMMENT '������ ��� ���.',
  `send_date` datetime DEFAULT NULL COMMENT '����� ��������',
  `date_create` datetime DEFAULT NULL COMMENT '����� ��������',
  `delta` int(11) DEFAULT NULL COMMENT '�������.',
  `body` text DEFAULT NULL COMMENT '����� ������',
  `surname` varchar(64) DEFAULT NULL COMMENT '������� ������������',
  `to_all` tinyint(1) DEFAULT '0' COMMENT '��������� ���� ��� ������ �� email',
  
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;*/
				$message = '���� ������ ���������� � ������� �� �������� � ���� ������ ����!';
				
				$mail = new SampleMail();
				$mail->setSubject(utils_utf8('����� ��������� �� ����'));
				$mail->setPlainText( $data['body'] );
				$e = 'lamzin80@mail.ru';
				$mail->setAddressTo([$e => $e]);
				$mail->setAddressFrom([ADMIN_EMAIL => ADMIN_EMAIL]);
				$r = $mail->send();
			}
		}
		
		require_once __DIR__ . '/index.tpl.php';
	}
	
	private function _validate($subject, $email, $toAll, $body, $num, $date) {
		if (!$subject) {
			return "�� ��������� ���� ����";
		}
		if (!$email && !$toAll) {
			return "�� ��������� ���� E-mail � �� ������� �������� ���� ��������!";
		}
		$r = checkMail($email);
		if (!$r) {
			return "E-mail {$email} �� ���������� email �����";
		}
		if (!$body) {
			return "�� ��������� ���� ����� ������";
		}
		if (!$num) {
			return "���� ������ ���, ����� �������� ��� �� �� ������ �����";
		}
		@session_start();
		$sesskey = sess('capcode');
		if ($num != $sesskey) {//TODO
			return "�������� ���!";
		}
		if (!$date) {
			return "�� ������� ���� ��������!";
		}
		$pattern = "#^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$#";
		if (!preg_match($pattern, $date)) {
			return "���� �������� ������ ���� ������� � ������� ����-��-��!";
		}
		@date_default_timezone_set('Europe/Moscow');
		$ts = strtotime($date . ' 00:00:00');
		$now = time() + 48*3600 - SUMMER_TIME;
		/*var_dump($date);
		var_dump($now);
		var_dump('now: ' . date('Y-m-d H:i:s', $now));
		var_dump($ts);
		var_dump('selected date: ' . date('Y-m-d H:i:s', $ts));*/
		if ($now > $ts) {
			$sDate = date('d.m.Y', $now + 24*3600);
			return '������ ����� ���� ���������� �� ������ ��� ' . $sDate;
		}
		return '';
	}
}
new App();
