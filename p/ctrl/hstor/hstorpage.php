<?php
include __DIR__ . '../../adminauth.php';

class HStorPage extends AdminAuth {
	
	public $pageHeading = '';
	
	/** @property string jsonData Данные для страницы в формате JSON*/
	public $jsonData = '';
	
	
	public function __construct() {
		$this->table = 'hstor_file';
		$this->pageHeading = L('Disks base');
		parent::__construct();
		$this->_setJsonData();
	}
	/**
	 * @description Установить $this->jsonData
	*/
	private function _setJsonData()
	{
		$a = [
			'containers' => null,
			'converts' => null
		];
		$safe = ($_REQUEST['xhr'] ?? -1);
		$_REQUEST['xhr'] = true;
		$a['containers'] = query("SELECT id,  name FROM hstor_container WHERE is_deleted = 0 ORDER BY delta");
		$a['containers'] = query("SELECT id,  name FROM hstor_convert WHERE is_deleted = 0 ORDER BY delta");
		if ($safe == -1) {
			unset($_REQUEST['xhr']);
		} else {
			$_REQUEST['xhr'] = $safe;
		}
		$this->jsonData = json_encode($a);
	}
	
}
