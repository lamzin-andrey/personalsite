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
	public static function create(int $nUid)
	{
		$table = 'trollkiller';
		$uid = $nUid;
		$_REQUEST['xhr'] = 1;
		$aRows = query('SELECT * FROM ' . $table . ' WHERE uid = ' . $uid);
		$sTmpFile = __DIR__ . '/../../../../portfolio/web/userscripts/trollkiller/d/' . $uid . '_tmp.json'; 
		$sFile = __DIR__ . '/../../../../portfolio/web/userscripts/trollkiller/d/' . $uid . '.json'; 
		$s = json_encode($aRows);
		//$s = utils_utf8($s);
		file_put_contents($sTmpFile, $s);
		rename($sTmpFile, $sFile);
	}
}
