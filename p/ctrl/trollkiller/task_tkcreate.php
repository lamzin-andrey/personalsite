<?php
require_once __DIR__ . '/../../../q/q/config.php';
require_once __DIR__ . '/../../../q/q/mysql.php';
require_once __DIR__ . '/../../../q/q/utils.php';
require_once __DIR__ . '/classes/jsongen.php';

if (!function_exists('getallheaders')) {
	function getallheaders(){
		return [];
	}
}

/**
 * @class Task_TrollKillerCreateList - генерация JSON файлов для всех пользователей
*/
class Task_TrollKillerCreateList {
	public $uid = 0;
	
	public function __construct()
	{
		$this->table = 'trollkiller';
		
		$aRows = query('SELECT DISTINCT uid FROM ' . $this->table . ' ORDER BY id');
		$offset = $this->getOffset();
		for ($i = $offset; $i < count($aRows); $i++) {
			$nUid = $aRows[$i]['uid'];
			if ($nUid) {
				JSONGen::create($nUid);
			}
			$this->setOffset($i);
		}
		$this->setOffset(0);
	}
	
	private function setOffset($n)
	{
		$file = $this->getOffsetFileName();
		file_put_contents($file, $n);
	}
	
	private function getOffset()
	{
		$file = $this->getOffsetFileName();
		if (!file_exists($file)) {
			return 0;
		}
		return intval(file_get_contents($file));
	}
	
	private function getOffsetFileName()
	{
		return __DIR__ . '/cache/offset.txt';
	}
}
new Task_TrollKillerCreateList();
