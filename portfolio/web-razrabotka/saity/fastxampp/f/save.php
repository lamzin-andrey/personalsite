<?php
require_once __DIR__ . '/q/config.php';
require_once __DIR__ . '/q/mysql.php';
require_once __DIR__ . '/q/utils.php';
require_once __DIR__ . '/q/baseapp.php';
require_once __DIR__ . '/q/auth.php';
//require_once __DIR__ . '/q/SampleMail.php';
require_once __DIR__ . '/uapp.php';
require_once __DIR__ . '/q/lang.php';

class Save extends BaseApp {
	public function __construct() {
		$this->table = 'apk_apps';
		$this->_actionPost();
	}
	/**
	 * @description запрос слова
	*/
	public function _actionPost() {
		$token = Auth::getToken();
		$root = ROOT;
		$script = '';
		$uid  = Auth::getUid();
		$userApp = new UserApp();
		$apid = $userApp->getAppId($uid);
		if (!$apid) {
			json_error('msg', __('unable-upload-y-app-try-again'));
		}
		$this->tsreq('display', 'display_name');
		$this->tsreq('name', 'title');
		if (!$this->_validate($report)) {
			$report['info'] = 'validate';
			json_error_arr($report);
		}
		$this->request['process_status'] = 1;
		$this->id = $apid;
		$this->rec();
		$oInfo = json_decode($this->filelist);
		$oInfo->d = $this->request['display_name'];
		$oInfo->n = $this->request['title'];
		$this->request['filelist'] = json_encode($oInfo);
		$query = $this->updateQuery('id = ' . $apid);
		query($query, $m, $n);
		
		/*Перенесено в то, где статус получен
		$mail = new SampleMail();
		$mail->setSubject('Новая компиляция');
		$mail->setPlainText( 'Кто-то что-то пытается собрать, поздравляю! (' . $oInfo->d . ', id: ' . $this->id . ')');
		$e = 'lamzin.a.m.d@yandex.ru';//'lamzin80@mail.ru';
		$mail->setAddressTo([$e => $e]);
		$ADMIN_EMAIL = 'admin@andryuxa.ru';//'admin@firstcode.ru';
		$mail->setAddressFrom([$ADMIN_EMAIL => $ADMIN_EMAIL]);
		$r = $mail->send();
		file_put_contents(__DIR__ . '/mail.log', print_r($r, 1) . "\n", FILE_APPEND);*/
		json_ok();
		require_once __DIR__ . '/index.tpl.php';
	}
	
	/**
	 * @description
	 * @return bool
	*/
	private function _validate(&$report = []) {
		if (trim($this->display_name) && trim($this->title)) {
			return true;
		}
		if (!trim($this->display_name)) {
			$report[] = __('display-name-required');
		}
		if (!trim($this->title)) {
			$report[] = __('sys-name-required');
			
		}
		return false;
	}
	
}
new Save();
