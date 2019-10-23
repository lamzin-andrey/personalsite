<?php
require_once __DIR__ . '/q/config.php';
require_once __DIR__ . '/q/mysql.php';
require_once __DIR__ . '/q/utils.php';
require_once __DIR__ . '/q/baseapp.php';
require_once __DIR__ . '/q/auth.php';
require_once __DIR__ . '/q/lang.php';
require_once __DIR__ . '/uapp.php';

class Save extends BaseApp {
	public function __construct() {
		$this->table = 'apk_apps';
		$this->_actionGet();
	}
	/**
	 * @description запрос слова
	*/
	public function _actionGet() {
		$token = Auth::getToken();
		$root = ROOT;
		$script = '';
		$uid  = Auth::getUid();
		$userApp = new UserApp();
		$this->id = $apid = $userApp->getAppId($uid);
		$defaultError = __('state-default-error');
		if (!$apid) {
			json_error('info', 'notFound', 'msg', $defaultError);
		}
		$row = $this->rec();
		if (isset($row['id'])) {
			$msg = '';
			$ts = 0;
			$lastRequestFile = DOC_ROOT . ROOT . 'q/cache/lrq.ts';
			if (file_exists($lastRequestFile)) {
				$ts = time() - filemtime($lastRequestFile);
			}
			switch($this->process_status){
				case 1:
					$msg = __('your-app-waiting-compiler');
					break;
				case 2:
					$msg = __('Compiling');
					break;
				case 3:
					$this->_jsonCompileResult();
					break;
			}
			json_ok('msg', $msg, 'ts', $ts);
		}
		json_error('msg', $defaultError, 'info', 'notFound');
		require_once __DIR__ . '/index.tpl.php';
	}
	
	/**
	 * json
	 * @description Проверить, есть ли в каталоге out/appid/*.apk файл если есть то ок
	 * Если в нем есть файлы error.log out.log тио вывестти соотв. результат
	*/
	private function _jsonCompileResult() {
		$root = DOC_ROOT . ROOT . 'u/out/' . $this->id;
		$list = utils_scandir($root);
		$sLogName      = 'stdout.log';
		$sErrorLogName = 'stderr.log';
		foreach ($list as $f) {
			$aI = pathinfo($f);
			if (isset($aI['extension']) && $aI['extension'] == 'apk') {
				json_ok('msg', __('link-your-application') . ': <a href="' . ROOT . 'u/out/' . $this->id . '/' . $f . '" target="_blank">' . __('Download') . '</a>');
			} else {
				if ($f == $sErrorLogName || $f == $sLogName) {
					json_ok('msg', __('compil-error') . '. <a href="' . ROOT . 'u/out/' . $this->id . '/' . $sErrorLogName . '" target="_blank">' . __('Download error log') . '</a> <a href="' . ROOT . 'u/out/' . $this->id . '/' . $sLogName . '" target="_blank">' . __('Скачать stdout компилятора') . '</a>');
				}
			}
		}
	}
	
}
new Save();
