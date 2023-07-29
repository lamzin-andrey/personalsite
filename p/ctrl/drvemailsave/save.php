<?php

require_once __DIR__ . '/openapp.php';
require_once DOC_ROOT . '/q/q/SampleMail.php';

class DrvEmailWanterPost extends OpenApp {
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		$this->table = 'drv_want_emails';
		
		$this->tsreq('email');
		
		
		$errors = [];
		
		
		if ($this->_validate($errors)) {
			$sql = "INSERT IGNORE INTO drv_want_emails            (`email`)           VALUES('{$this->email}');";
			query($sql);
			
			
			$mailer = new SampleMail();
			$mailer->setSubject('Появился новый желающий попробовать Web USB');
			$mailer->setBody("Привет!
			Пользователь {$login} с сайта {$site} будет рад попробовать этот твой WebUSB когда его закончишь.
			");
			$mailer->setFrom('admin@andryuxa.ru');
			$mailer->setTo('lamzin80@mail.ru');
			$mailer->send();
			
			$mailer = new SampleMail();
			$mailer->setSubject('Статус вашей заявки в бесплатном онлайн-сервисе Web USB');
			$mailer->setBody("Здравствуйте!
			Вы получили это письмо, потому что оставили заявку на использование сервиса webUSB.
			Сейчас я не могу предоставить его вам в использование, потому что веду доработки связанные 
			с соблюдением законодательства Российской Федерации.
			Речь идет об обязательном хранении в течении полугода данных, которые пользователи сайтов загружают на сайт.
			Но как только я доработаю этот нюанс, я пришлю вам ссылку на сервис.
			Спасибо за вашу поддержку, знать, что моя работа кому-то нужна очень приятно!
			");
			$mailer->setFrom('admin@andryuxa.ru');
			$mailer->setTo($this->email);
			$mailer->send();
			
			json_ok('msg', l('Thank-for-want-web-usb'));
		}
		$aErr = [];
		foreach ($errors as $key => $sText) {
			$aErr[] = "<p>{$sText}</p>";
		}
		$errors = join(',', $aErr);
		json_error_arr(['msg' => $errors]);
	}
	/**
	 * @description Проверка, заполнены ли поля
	*/
	private function _validate(array &$errors) : bool
	{
		//email
		$this->_setRequiredError('email', 'email', $errors);
		
		$success = checkMail($this->email);
		if (!$success) {
			$errors['email'] = l('invalid-email', 0, l('email', 1));
		}
		
		$a = explode('.', $this->email);
		$dom = $a[count($a) - 1];
		if ('ru' !== $dom) {
			$errors['email'] = l('inostr-email', 0, l($this->email, 1));
		}
		
		if (count($errors)) {
			return false;
		}
		return true;
	}
	/**
	 * @description Установить ошибку Поле обязательно
	 * @param string $varname
	 * @param string $localizeKey
	 * @param array &$errors
	*/

	private function _setRequiredError(string $varname, string $localizeKey, array &$errors)
	{
		if (!strlen($this->$varname) ) {
			$errors[$varname] = l('field-required', 0, l($localizeKey, 1));
		}
	}
}
