<?php


class OpenApp extends BaseApp {
	/** @property string */
	//public $ = '';
	
	
	public function __construct() {
		$_REQUEST['xhr'] = 1;
		utils_header_utf8();
		utils_crossOrigin();
		parent::__construct();
		
		$this->table = 'ausers';
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
		$aUrl = explode('?', $_SERVER['REQUEST_URI']);
		$aUrl = explode('/', $aUrl[0]);
		if (a($aUrl, 1) == 'p' && a($aUrl, 2) == 'trollkiller') {
			return true;
		}
		return false;
	}
}
