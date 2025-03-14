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
		$a['containers'] = query("SELECT id,  name, color FROM hstor_container WHERE user_id = {$this->uid} AND is_deleted = 0 ORDER BY delta");
		$a['converts'] = query("SELECT id,  name, color FROM hstor_convert WHERE user_id = {$this->uid} AND is_deleted = 0 ORDER BY delta");
		if ($safe == -1) {
			unset($_REQUEST['xhr']);
		} else {
			$_REQUEST['xhr'] = $safe;
		}
		$this->jsonData = json_encode($a);
	}
	
}
