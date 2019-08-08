<?php


class OpenApp extends BaseApp {
	/** @property string */
	//public $ = '';
	
	
	public function __construct() {
		$_REQUEST['xhr'] = 1;
		utils_header_utf8();
		utils_crossOrigin();
		parent::__construct();
		
		$this->table = 'demo_tree_table';
	}
	/**
	 * @description Вернет истину когда запрос направлен в часть сайта не требующую csrf
	*/
	protected function _isNoCsrfPage(string $sPostToken) : bool
	{
		return $this->_isDemoTreeRequest($sPostToken);
	}
	
	/**
	 * @description Для демо не требуем csrf токена
	*/
	private function _isDemoTreeRequest(string $sPostToken) : bool
	{
		if ($sPostToken != '1cdslkjhs4dfjkhs8fdjkhsg') {
			return false;
		}
		$aUrl = explode('?', $_SERVER['REQUEST_URI']);
		$aUrl = explode('/', $aUrl[0]);
		if (a($aUrl, 1) == 'p' && a($aUrl, 2) == 'treedemo') {
			return true;
		}
		return false;
	}
}
