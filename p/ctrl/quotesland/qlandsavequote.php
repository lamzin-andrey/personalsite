<?php
include __DIR__ . '../../adminauthjson.php';


class CApplication {
	static public function getUid(){
		return sess('nuid');
	}
}

class QLandPost extends AdminAuthJson {
	public int $foundId = 0;
	public string $hash = '';
	public string $titleHash = '';
	public bool $sourceFound = false;
	
	public function __construct() {
		parent::__construct();
		$this->table = 'qland_list';
		$this->tsreq('text');
		$this->tsreq('author');
		$this->tsreq('title');
		$this->tsreq('type');
		
		$errors = [];
		
		if ($this->_validate($errors)) {
			db_escape($this->text);
			$this->searchQuote();
			$id = $this->foundId;
			$sql = '';
			
			if ($id) {
				query("UPDATE {$this->table} SET _rate = _rate + 1 WHERE id = $id;");
				$insId = $id;
			} else {
				$now = now();
				$sql = "INSERT INTO {$this->table} (`_text`, `text_hash`, `user_id`, `created_time`)
						VALUES(
							'{$this->text}',
							'{$this->hash}',
							'{$this->uid}',
							'{$now}'
						)";
				$insId = query($sql);	
			}
			
			db_escape($this->title);
			$this->searchTitle();
			if (!$this->sourceFound) {
				db_escape($this->author);
				db_escape($this->type);
				$sql = "INSERT INTO qland_source (`qland_list_id`, `authors`, `source_title`, `text_hash`, `source_type`, `created_time`)
						VALUES(
							$insId,
							'{$this->author}',
							'{$this->title}',
							'{$this->titleHash}',
							'{$this->type}',
							'{$now}'
						)";
				$insId = query($sql);
			}
			
			
			global $dberror;
			json_ok('id', $id, 'dberror', $dberror);
		}
		json_error_arr(['errors' => $errors]);
	}
	
	private function searchQuote(): void
	{
		$hash = $this->createHash($this->text);
		$sql = "SELECT id FROM {$this->table} WHERE `text_hash` = '{$hash}' LIMIT 1;";
		$this->foundId = intval(dbvalue($sql));
		$this->hash = $hash;
	}
	
	private function searchTitle(): void
	{
		$hash = $this->createHash($this->title);
		$sql = "SELECT id FROM qland_source WHERE `text_hash` = '{$hash}' LIMIT 1;";
		$id = intval(dbvalue($sql));
		$this->sourceFound = ($id > 0);
		$this->titleHash = $hash;
	}
	
	private function createHash(string $text): string
	{
		$e = 'UTF-8';
		$text = mb_strtolower($text, $e);
		$abc = 'abcdefghijklmnopqrstuvwxyzабвгдеёжзийклмнопрстуфхцчшщъыьэюя';
		$abc .= '0123456789';
		$sz = mb_strlen($text, $e);
		$q = '';
		for ($i = 0; $i < $sz; $i++) {
			$ch = mb_substr($text, $i, 1, $e);
			if (mb_strpos($abc, $ch, 0, $e) !== false) {
				$q .= $ch;
			}
		}
		
		return md5($q);
	}
	
	/**
	 * @description Проверка, заполнены ли поля TODO test it
	*/
	private function _validate(array &$errors) : bool
	{
		$this->_setRequiredError('text', 'Quote text', $errors);
		$this->_setRequiredError('author', 'Author', $errors);
		$this->_setRequiredError('title', 'Author', $errors);
		
		
		if (count($errors)) {
			return false;
		}
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
