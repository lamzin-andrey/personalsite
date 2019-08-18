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
		$uid = Auth::getUid();
		if (!$uid) {
			json_error('msg', l('Signin'));
		}
		$this->d = utils_utf8($this->d);
		$aData = json_decode($this->d);
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
}
