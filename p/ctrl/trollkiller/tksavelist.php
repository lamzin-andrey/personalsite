<?php
require_once __DIR__ . '/openapp.php';
/**
 * @class TrollKillerSaveList - 
*/
class TrollKillerSaveList extends OpenApp {
	public $uid = 0;
	
	public function __construct() {
		parent::__construct();
		$this->table = 'trollkiller';
		$this->treq('d');
		$this->treq('u');
		
		$uid = Auth::getUid();
		if (!$uid) {
			json_error('msg', l('Signin'));
		}
		
		$this->d = utils_utf8($this->d);
		$aData = json_decode($this->d);
		
		$this->u = utils_utf8($this->u);
		$oUserData = json_decode($this->u);
		
		$this->_saveUserData($oUserData, $uid);
		
		if (!$aData || ( !is_array($aData) && !is_object($aData) ) ) {
			json_error('msg', l('Invalid data'));
		}
		$nSuccess = 0;
		global $dberror;
		
		$sql = "SELECT * FROM {$this->table} WHERE uid = {$uid}";
		$aExists = query($sql);
		$aIndexed = [];
		foreach ($aExists as $aRow) {
			$aIndexed[ $aRow['a_mail_id'] . '_' . $aRow['uid'] ] = $aRow['id'];
		}
		
		foreach ($aData as $sLink => $oData) {
			$nAMailId = intval(str_replace(['/profile/id', '/'], ['', ''], $sLink) );
			
			if ($nAMailId == 0) {
				continue;
			}
			$oData->reason = utils_cp1251($oData->reason);
			$oData->name   = utils_cp1251($oData->name);
			$sql = 'INSERT INTO trollkiller 
				(`a_mail_id`, `reason`, `nick`, `uid`) 
			VALUES
				(' . $nAMailId . ', \'' . db_safeString($oData->reason) . '\', \'' . db_safeString($oData->name) . '\', ' . $uid . ')
				ON DUPLICATE KEY UPDATE `uid` = `uid`
				';
			$dberror = '';
			query($sql);
			if (!$dberror) {
				unset( $aIndexed[ $nAMailId . '_' . $uid ] );
				$nSuccess++;
			}
		}
		
		if ($aIndexed) {
			$this->deleteByIdList($aIndexed);
		}
		
		json_ok('msg', "nSuccess = {$nSuccess}");
	}
	
	private function _saveUserData(StdClass $oUserData, int $uid)
	{
		$nAMailId = intval(str_replace(['/profile/id', '/'], ['', ''], $oUserData->link) );
		$sName   = utils_cp1251($oUserData->name);
		$sImg = $this->_createImage(strval($oUserData->img), $nAMailId);
		$sTail = ' `uid` = `uid` ';
		
		if ($sImg) {
			$sTail = ' `imgpath` = \'' . $sImg . '\' ';
		}
		
		$sql = 'INSERT INTO trollkiller_userinfo
				(`a_mail_id`, `imgpath`, `name`, `uid`) 
			VALUES
				(' . $nAMailId . ', \'' . $sImg . '\', \'' . db_safeString($sName) . '\', ' . $uid . ')
				ON DUPLICATE KEY UPDATE ' . $sTail;
		query($sql);
	}
	/**
	 * @description
	 * @param string $sImg
	 * @return string
	*/
	private function _createImage(string $sImg, int $nAMailId) : string
	{
		$sImg = trim($sImg);
		if (!$sImg || !$nAMailId) {
			return '';
		}
		$a = explode(',',  $sImg);
		if (a($a, 1)) {
			$data = base64_decode($a[1]);
			$sDir = DOC_ROOT . '/i/' . date('Y') . '/' . date('m') ;
			utils_createDir($sDir);
			$sFilename = $sDir . '/' . $nAMailId . '.tmp' ;
			file_put_contents($sFilename, $data);
			$aInfo = getImageSize($sFilename);
			if ($mime = a($aInfo, 'mime')) {
				$ext = 'jpg';
				switch ($mime) {
					case 'image/png' :
						$ext = 'png';
						break;
					case 'image/gif' :
						$ext = 'gif';
						break;
				}
				$sDestFile = str_replace('.tmp', ('.' . $ext), $sFilename);
				rename($sFilename, $sDestFile);
				return str_replace(DOC_ROOT, '', $sDestFile);
			} else {
				@unlink($sFilename);
			}
		}
		return '';
	}
}
