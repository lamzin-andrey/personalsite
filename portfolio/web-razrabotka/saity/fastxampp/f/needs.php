<?php
require_once __DIR__ . '/q/config.php';
require_once __DIR__ . '/q/utils.php';
require_once __DIR__ . '/q/baseapp.php';

class ApkQueue extends BaseApp {
	private $_table = 'apk_apps';
	
	public function __construct() {
		$lastRequestFile = DOC_ROOT . ROOT . 'q/cache/lrq.ts';
		file_put_contents($lastRequestFile, 1);
		$this->table = 'apk_apps';
		$this->condition = ' AND process_status = 1 ';
		$this->_getAction();
	}
	
	public function _getAction() {
		$this->orderDirection = 'desc';
		$list = $this->getPage();
		$a = [];
		$aIds = [];
		foreach ($list as $r) {
			$item = json_decode($r['filelist']);
			if ($item->n && $item->d) {
				$a[] = $item;
				$aIds[] = $item->id;
			}
		}
		if (sz($a)) {
			$sIds = join(',', $aIds);
			$cmd = "UPDATE {$this->table} SET process_status = 2 WHERE id IN({$sIds})";
			//var_dump($cmd); 		die(__file__ . __line__);
			query($cmd);
			echo json_encode($a);
			exit;
		}
		echo json_encode([]);
		exit;
	}
	
}
new ApkQueue();
