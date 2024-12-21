<?php
require_once __DIR__ . '/q/config.php';
require_once __DIR__ . '/q/mysql.php';
require_once __DIR__ . '/q/utils.php';
require_once __DIR__ . '/q/baseapp.php';
require_once __DIR__ . '/q/auth.php';
require_once __DIR__ . '/q/unzip.php';
require_once __DIR__ . '/q/lang.php';
require_once __DIR__ . '/uapp.php';

class Upload extends BaseApp {
	public function __construct() {
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
		
		$processZipFailMessage = __('unable-process-your-zip-archive');
		if (!isset($_FILES['iFile']) && !$_FILES['iFile']['error']) {
			json_error('no file!');
		}
		$path = md5_file($_FILES['iFile']['tmp_name']) . md5(time());
		$sharedTmpPath = $path = $_SERVER['DOCUMENT_ROOT'] . ROOT . 'u/tmp/' . $path;
		
		if ($uid && $apid) {
			$path = $_SERVER['DOCUMENT_ROOT'] . ROOT . 'u/ant/' . $apid;
		}else {
			mkdir($path, 0777);
		}
		$this->_clearTmpDir($path);
		$success = $this->_validate($path, $report, $_FILES['iFile']['tmp_name']);
		$tmpFileShort = 'tmp.zip';
		if (!$success) {
			//$this->sendReport($report);//json_ok
			$report['status'] = 'error';
			$report['info']   = 'validate';
			json_error_arr($report);
		}
		if (!$uid) {
			$uid = Auth::createUid();
		}
		if (!$apid) {
			$apid = $userApp->createApp($uid);
			$destPath = $_SERVER['DOCUMENT_ROOT'] . ROOT . 'u/ant/' . $apid;
			$success = copy("{$path}/{$tmpFileShort}", "{$destPath}/{$tmpFileShort}");
			if (!$success) {
				json_error('msg', $processZipFailMessage);
			}
		} else {
			$tmpFolder = DOC_ROOT . ROOT . 'u/ant/' . $apid . '/tmp';
			$this->_clearTmpDir($tmpFolder);
			@unlink($tmpFolder);
		}
		$destPath = $_SERVER['DOCUMENT_ROOT'] . ROOT . 'u/ant';
		$path = $_SERVER['DOCUMENT_ROOT'] . ROOT . 'u/ant/' . $apid;
		$success = copy("{$path}/{$tmpFileShort}", "{$destPath}/{$apid}.zip");
		if (!$success) {
			json_error('msg', $processZipFailMessage);
		}
		
		$tmpZip = DOC_ROOT . ROOT . 'u/ant/' . $apid . '/tmp.zip';
		if (file_exists($tmpZip)) {
			@unlink($tmpZip);
		}
		
		$this->_clearTmpDir("{$destPath}/{$apid}");
		$this->_clearTmpDir("{$sharedTmpPath}");
		if (file_exists($sharedTmpPath)) {
			rmdir($sharedTmpPath);
		}
		$this->_unzip("{$destPath}/{$apid}.zip");
		
		@unlink("{$destPath}/{$apid}.zip");
		$success = $this->_validate($destPath . "/{$apid}", $report, $tmpFileShort, false);
		if (!$success) {
			//$this->sendReport($report);//json_ok 
			$report['status'] = 'error';
			$report['info']   = 'validate2';
			json_error_arr($report);
		}
		$success = $this->_moveResources("{$destPath}/{$apid}/res", $apid);
		$this->_clearTmpDir("{$destPath}/{$apid}/res");
		@unlink("{$destPath}/{$apid}/res");
		@rmdir("{$destPath}/{$apid}/res");
		if (!$success) {
			json_error('msg', __('Не удалось переместить ресурсы') );
		}
		
		$success = $userApp->dropFromQueue();
		if (!$success) {
			json_error('msg', __('Не удалось поставить ваше приложение в очередь на сборку, попробуйте позже'));
		}
		json_ok();
		require_once __DIR__ . '/index.tpl.php';
	}
	/**
	 * @description
	 * @return bool
	*/
	private function _moveResources($srcFolder, $apid) {
		$destRoot = DOC_ROOT . ROOT . 'u/res/' . $apid;
		if (file_exists($srcFolder) && file_exists($destRoot)) {
			$this->_clearTmpDir($destRoot);
			$ls = utils_scandir($srcFolder);
			foreach ($ls as $f) {
				$file = $srcFolder . '/' . $f;
				if (!is_dir($file)) {
					$dest = $destRoot . '/' . $f;
					if (!copy($file, $dest)) {
						return false;
					}
				}
			}
		}
		return true;
	}
	/**
	 * @description
	 * @return bool
	*/
	private function _unzip($zip) {
		return unzip($zip);
	}
	/**
	 * @description
	 * @return bool
	*/
	private function _validate($path, &$report, $tmpFileName, $moveUploaded = true) {
		if (!file_exists($path) || !is_dir($path)) {
			$report[] = __('Каталог') . ' ' . $path . __('не найден или не является каталогом');
			return false;
		}
		$zip = $path . '/tmp.zip';
		if ($moveUploaded) {
			$s = move_uploaded_file($tmpFileName, $zip);
			if (!$s) {
				$report[] = __('Не удалось переместить загруженный файл');
				return false;
			}
			return true;
			/*$this->_unzip($zip);
			$path .= '/tmp';*/
		}
		return false;
		if (!file_exists($path . '/index.html')) {
			$report[] = __('В корне архива не найден файл index.html');
			return false;
		}
		if (!file_exists($path . '/res') || !is_dir($path . '/res')) {
			$report[] = __('not-found-images-for-google-play');
			return false;
		}
		$aRequireFiles = [36, 48, 96, 72];
		//Это значит, что есди файл с таким именем есть, он непременно должен быть такого же размера.
		$list = utils_scandir($path . '/res');
		foreach ( $list as $f ) {
			if(intval($f)) {
				foreach ($aRequireFiles as $n) {
					if ($f == $n . '.png') {
						$sz = getImageSize($path . '/res/' . $n . '.png');
						if (!isset($sz[0]) || !isset($sz[1]) || $sz[0] != $n || $sz[1] != $n) {
							$report[] = __('File') . ' ' . $n . '.png ' . __('must-have-size') . ' ' . $n .'  x ' . $n . ' '. __('pixels');
						}
					}
				}
			} else {
				$aInfo = pathinfo($path . '/res/' . $f);
				$aPair = explode('x', $aInfo['basename']);
				if (sz($aPair) == 2) {
					$w = intval($aPair[0]);
					$h = intval($aPair[1]);
					if ($w && $h) {
						$sz = getImageSize($path . '/res/' . $f);
						if (!isset($sz[0]) || !isset($sz[1]) || $sz[0] != $w || $sz[1] != $h) {
							$report[] = __('File') . ' ' . $n . '.png  ' . __('must-have-size') .  $w .'  x ' . $h . ' ' . __('pixels');
						}
					}
				}
			}
		}
		if (sz($report)) {
			return false;
		}
		return true;
	}
	/**
	 * @description Удалить все к чертям
	*/
	private function _clearTmpDir($path) {
		if (!file_exists($path) || !is_dir($path)) {
			return false;
		}
		$list = utils_scandir($path);
		foreach ($list as $f) {
			$file = $path . '/' . $f;
			if (file_exists($file)) {
				if (is_dir($file)){
					$this->_clearTmpDir($file);
					rmdir($file);
				} else {
					@unlink($file);
				}
				if (file_exists($file)) {
					return false;
				}
			}
		}
		return true;
	}
}
new Upload();
