<?php
include __DIR__ . '../../adminauthjson.php';
//include __DIR__ . '/articles/classes/articleslistcompiler.php';
//include_once DOC_ROOT . '/p/ctrl/classes/cstaticpagescompiler.php';

class CApplication {
	static public function getUid(){
		return sess('nuid');
	}
}

class SearchFile extends AdminAuthJson {	
	/** @property string */
	//public $ = '';
	
	public function __construct() {
		parent::__construct();
		$this->table = 'hstor_file';
		$s = $_GET['s'] ?? '';
		$s = strip_tags($s);
		$s = trim($s);
		$this->s = $s;
		$this->ireq('dn');
		$errors = [];
		
		if ($this->_validate($errors)) {
			
			if ($this->s) {
				$rows = $this->_loadBySearchString();
			} else {
				$rows = $this->_loadByDiskName();
			}
			
			global $dberror;
			json_ok('ls', $rows, 'dberror', $dberror);
		}
		json_error_arr(['errors' => $errors]);
	}
	
	
	private function _loadByDiskName(): array
	{
		$id = $this->dn;
		$sql = "SELECT disk_name FROM {$this->table} WHERE id = {$id} LIMIT 1";
		$diskName = dbvalue($sql);
		if (!$diskName) {
			return [];
		}
		
		$sql = "SELECT * FROM {$this->table} WHERE user_id = {$this->uid} AND is_deleted != 1 AND disk_name = '{$diskName}';";
		return query($sql, $nR);
	}
	
	
	private function _loadBySearchString(): array
	{
		$fName = $this->s;
		$sql = "SELECT * FROM {$this->table} WHERE user_id = {$this->uid} AND is_deleted != 1 AND ({cond})";
		$aCond = [];
		$aCond[] = $this->buildFieldCond('name');
		$aCond[] = $this->buildFieldCond('file_name');
		$aCond[] = $this->buildFieldCond('disk_name');
		$aCond[] = $this->buildFieldCond('artists');
		$aCond[] = $this->buildFieldCond('additional_info');
		$aCond[] = $this->buildFieldCond('additional_info_2');
		$sCond = implode(' OR ', $aCond);
		$sql  = str_replace('{cond}', $sCond, $sql);
		//die($sql);
		return query($sql, $nR);
	}
	
	private function buildFieldCond(string $fName): string
	{
		$s = $this->s;
		$r = "`$fName` = '$s' OR `$fName` LIKE('%$s%') OR `$fName` LIKE('$s%') OR `$fName` LIKE('%$s') OR `$fName` LIKE('$s')";
		return $r;
	}
	
	/**
	 * @description Проверка, заполнены ли поля TODO test it
	*/
	private function _validate(array &$errors) : bool
	{
		// TODO check quote!!! $this->s
		return true;
	}
	/**
	 * @description Установить ошибку Поле обязательно
	 * @param string $varname
	 * @param string $localizeKey
	 * @param array &$errors
	*/

	private function _setRequiredError(string $varname, string $localizeKey, array &$errors)
	{
		if (!strlen($this->$varname) ) {
			$errors[$varname] = l('field-required', 0, l($localizeKey, 1));
		}
	}
	
	/**
	 * @description Перевод в дату
	*/
	private function _postDate(string $date, bool $currentTime = false) : string
	{
		if (!$currentTime) {
			return "$date 00:00:00";
		}
		return $date . ' ' . date('H:i:s');
	}
}
