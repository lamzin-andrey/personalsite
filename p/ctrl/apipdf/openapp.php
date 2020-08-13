<?php


class OpenApp extends BaseApp {
	/** @property string */
	//public $ = '';
	
	
	public function __construct() {
		$_REQUEST['xhr'] = 1;
		utils_header_utf8();
		utils_crossOrigin();
		parent::__construct();
		
		$this->table = 'pdftotext_tasks';
	}
	/**
	 * @description Вернет истину когда запрос направлен в часть сайта не требующую csrf
	*/
	protected function _isNoCsrfPage(string $sPostToken) : bool
	{
		return $this->_isDemoTreeRequest($sPostToken);
	}
	
	/**
	 * @description Для демо не требуем csrf токена. Но вместо него проверяем токен api
	*/
	private function _isDemoTreeRequest(string $sPostToken) : bool
	{
		$token = treq('token');
		if ($token == 'kLfIdls49nfkljghn5855dfkjbdfk'){
			return true;
		}
		return false;
	}
}
