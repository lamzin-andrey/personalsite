<?php

/**
 * @class Task_TrollKillerCreateList - генерация JSON файлов для всех пользователей
*/
class JSONGen {
	public $uid = 0;
	
	/**
	 * @description Генерирует json файл для пользователя trollkiller $nUid
	 * @param int $nUid
	*/
	public static function create($nUid)
	{
		$table = 'trollkiller';
		$uid = $nUid;
		
		$sql = 'SELECT * FROM ' . $table . ' WHERE uid = ' . $uid;
		$sql = static::_patchSql($sql, $uid);
		$_REQUEST['xhr'] = 1;
		$aRows = query($sql);
		$sTmpFile = __DIR__ . '/../../../../portfolio/web/userscripts/trollkiller/d/' . $uid . '_tmp.json'; 
		$sFile = __DIR__ . '/../../../../portfolio/web/userscripts/trollkiller/d/' . $uid . '.json'; 
		if ($aRows) {
			$s = json_encode($aRows);
			file_put_contents($sTmpFile, $s);
		} else {
			file_put_contents($sTmpFile, '[]');
		}
		rename($sTmpFile, $sFile);
	}
	/**
	 * @description
	 * @param string $defaultSql
	 * @param int $nUid
	 * @return string
	*/

	private static function _patchSql($defaultSql, $nUid)
	{
		$aRows = query('SELECT subject_id AS id FROM trollkiller_banlists_rel WHERE client_id = ' . $nUid, $nRows);
		if ($nRows) {
			$aRows = array_column($aRows, 'id', 'id');
			$aRows[$nUid] = $nUid;
			$s = join(',', $aRows);
			$sql = 'SELECT * FROM ' . $table . ' WHERE uid IN(' . $s . ')';
			return $sql;
		}
		return $defaultSql;
	}
}
