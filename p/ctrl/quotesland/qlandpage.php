<?php
include __DIR__ . '../../adminauth.php';

class QLandPage extends AdminAuth {
	
	public $pageHeading = '';
	
	/** @property array $quotes список цитат*/
	public array $quotes = [];
	
	public function __construct() {
		$this->pageHeading = L('Quotes');
		parent::__construct();
		$this->table = 'qland_list';
		//$this->_setJsonData();
		$this->_loadData();
	}
	/**
	 * @description Получить данные о цитатах
	*/
	private function _loadData()
	{
		$this->quotes = $this->getPage('page', 100);
	}
	
	/**
	 * @description Возвращает страницу запрошенную в request в аргументе $getvarname
	 * Если _GET пуст, то пытается получить номер страницы из /$getvarname/n в основном url
	 * Игнорирует is_deleted = 1
	 * @param string $getvarname = 'page'
	 * @param int $perpage = 10
	 * @param string $sFields = '*'
	 * @param int $forceOffset = -1 - если передано значение отличное от -1, вычисление offset от $page не происходит (page offset взаимозаменяемые)
	 * @return array
	*/
	public function getPage(string $getvarname = 'page', int $perpage = 10, string $sFields = '*', int $forceOffset = -1) : array
	{
		if ($this->table) {
			if ($forceOffset == -1) {
				$page = ireq($getvarname, 'GET');
				if (!$page) {
					$aUrl = explode('/', $_SERVER['REQUEST_URI']);
					if ($s = a($aUrl, count($aUrl) - 2) ) {
						if ($s == $getvarname) {
							$page = intval( a($aUrl, count($aUrl) - 1) );
						}
					}
				}
				if (!$page) {
					$page = 1;
				}
				$offset = ($page - 1) * $perpage;
			} else {
				$offset = $forceOffset;
			}
			$command = "SELECT L.id, L._text, s.authors, s.source_title, s.source_type 
						FROM {$this->table} AS L 
						INNER JOIN qland_source AS s
						 ON s.qland_list_id = L.id
						WHERE L.is_banned != 1 
						AND L.is_moderate = 1
						AND s.is_moderate != 1
						ORDER BY L._rate DESC, s.delta DESC
						LIMIT {$offset}, {$perpage}";
			//var_dump($command);	die(__file__ . __line__);
			$rows = query($command);
			
			// Оставляем самые новые
			$result = [];
			foreach ($rows as $r) {
				if (!isset($result[$r['id']])) {
					$result[$r['id']] = $r;
				}
			}
			
			return $result;
		}
		return [];
	}
	
}
