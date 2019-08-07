<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mysql.php';
require_once __DIR__ . '/utils.php';

class BaseApp {
	/** @property string $table */
	public $table;
	
	/** @property int $id */
	public $id;
	
	/** @property array $request */
	public $request = [];
	
	/** @property string $orderDirection */
	public $orderDirection = 'asc';
	
	/** @property string $condition fragment of sql WHERE  */
	public $condition = '';
	
	/** @property string $token Токен csrf  */
	public $token = '';
	
	/** @description  */
	public function __construct() {
		@session_start();
		@date_default_timezone_set('Europe/Moscow');
		$token = sess('autoken');
		if (!$token) {
			$token = md5(time() . $_SERVER['HTTP_USER_AGENT'] . uniqid(strval(time()), true));
			sess('autoken', $token);
		}
		$this->token = $token;
		if (count($_POST)) {
			if (a($_POST, '_token') != $token ) {
				die('csrf');
			}
		}
	}
	/**
	 * @param $key   - имя поля в запросе
	 * @param $field - имя поля в бд
	*/
	public function req($key, $field = '', $varname = 'REQUEST') {
		$field = $field ? $field : $key;
		$this->$field = req($key, $varname);
		if ( defined('DB_ENC_IS_1251') && utils_isXhr() ) {
			$this->$field = utils_utf8($this->$field);
		}
		$this->request[$field] = $this->$field;
		return $this->$field;
	}
	/**
	 * @description trim && strip && safe
	 * @param $key   - имя поля в запросе
	 * @param $field - имя поля в бд
	*/
	public function tsreq($key, $field = '', $varname = 'REQUEST') {
		$field = $field ? $field : $key;
		$this->$field = strip_tags( trim(req($key, $varname) ) );
		db_safeString($this->$field);
		$this->request[$field] = $this->$field;
		return $this->$field;
	}
	public function streq($key, $field = '', $varname = 'REQUEST') {
		return $this->tsreq($key, $field = '', $varname);
	}
	/**
	 * @description
	 * @param string $key - имя переменной в REQUEST
	 * @param string $field = '' имя поля в таблице БД, если не передано, то принимается равным $key
	 * @param string $varname = 'REQUEST'
	 * @return string
	*/
	public function treq(string $key, string $field = '', string $varname = 'REQUEST') : string
	{
		$field = $field ? $field : $key;
		$this->$field = trim(req($key, $varname) );
		$this->request[$field] = $this->$field;
		return $this->$field;
	}
	/**
	 * @description
	 * @param string $key - имя переменной в REQUEST
	 * @param string $field = '' имя поля в таблице БД, если не передано, то принимается равным $key
	 * @param string $varname = 'REQUEST'
	 * @return int
	*/
	public function ireq(string $key, string $field = '', string $varname = 'REQUEST') :int
	{
		$field = $field ? $field : $key;
		$this->$field = ireq($key, $varname);
		$this->request[$field] = $this->$field;
		return $this->$field;
	}
	public function breq($key, $field = '', $varname = 'REQUEST') {
		$field = $field ? $field : $key;
		$s = $this->tsreq($key, $field, $varname);
		$this->$field = ($s == 'true' ? true : false);
		return $this->$field;
	}
	
	/**
	 * @see db db_createInsertQuery
	*/
	public function insertQuery($config = [], &$options = null) {
		return db_createInsertQuery($this->request, $this->table, $config, $options);
	}
	
	/**
	 * @see db db_createInsertQueryExt
	*/
	public function insertQueryExt(&$data, $config = [], &$options = null) {
		$data = $this->request;
		return db_createInsertQueryExt($data, $this->table, $config, $options);
	}
	
	/**
	 * @see db db_createUpdateQuery
	*/
	public function updateQuery($condition, $config = [], &$options = null) {
		return db_createUpdateQuery($this->request, $this->table, $condition, $config, $options);
	}
	
	/**
	 * @see db db_createUpdateQueryExt
	*/
	public function updateQueryExt($condition, $config = [], &$options = null) {
		return db_createUpdateQueryExt($this->request, $this->table, $condition, $config, $options);
	}
	
	public function jsonError($messsage) {
		echo json_encode(['error' => $messsage]);
		exit;
	}
	/**
	 * @description Если известны id и table
	*/
	public function rec() {
		$this->id = intval($this->id);
		if ($this->table && $this->id) {
			$row = dbrow("SELECT * FROM {$this->table} WHERE id = {$this->id}");
			foreach ($row as $key => $field) {
				$this->$key = $field;
			}
			return $row;
		}
		return [];
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
			$command = "SELECT {$sFields} FROM {$this->table} WHERE is_deleted != 1 {$this->condition} ORDER BY id {$this->orderDirection} LIMIT {$offset}, {$perpage}";
			//var_dump($command);	die(__file__ . __line__);
			$rows = query($command);
			return $rows;
		}
		return [];
	}
	/**
	 * @description Возвращает количество страниц в таблице соотв. $this->condition
	 * @return array
	*/
	public function getTotal() : int
	{
		$command = "SELECT COUNT(id) FROM {$this->table} WHERE is_deleted != 1 {$this->condition}";
		return intval(dbvalue($command));
	}
	/**
	 * @description Удалить запись из таблицы БД
	 * @return true if record was removed
	*/
	public function deleteHard() : bool
	{
		$nA = 0;
		query("DELETE FROM {$this->table} WHERE id = {$this->id};", $nR, $nA);
		return ($nA > 0);
	}
	/**
	 * @description formView Может обращатсья к этому объекту для того чтобы считывать значения для форм
	 * $dataObject
	 * 
	*/
	static private $_dataObject;
	/**
	 * @description Установить объект для того, чтобы formview мог с ним работать
	*/
	static public function setDataObject($o) {
		self::$_dataObject = $o;
	}
	/**
	 * @description Получить объект для того, чтобы formview мог с ним работать
	*/
	static public function getDataObject() {
		return self::$_dataObject;
	}
}

