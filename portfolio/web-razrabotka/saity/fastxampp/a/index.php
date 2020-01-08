<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mysql.php';
require_once __DIR__ . '/utils.php';

class App {
	
	/** @property string _sDal Наименование источника Толковый словарь Даля в кодировке WINDOWS-1251 */
	private $_sDal = '';
	
	/** @property string _sTs Наименование источника Современный толковый словарь в кодировке WINDOWS-1251 */
	private $_sTs = '';
	
	/** @property string _sOzh Наименование источника Толковый словарь Ожегова в кодировке WINDOWS-1251*/
	private $_sOzh = '';
	
	public function __construct()
	{
		$this->_initSourcesString();
		$this->_actionPost();
	}
	/**
	 * @description запрос слова
	*/
	public function _actionPost()
	{
		$word = req('word');
		$word = trim($word);
		$n = 0;
		$rows = [];
		if ($word) {
			$word = str_replace('\'', '`', $word);
			$word2  = $word . ' ..';
			if ($word != 'r') {
				$rows = query("SELECT word, description, source FROM sts WHERE word LIKE('{$word}%')   LIMIT 30", $n);
				$buf = [];
				$length = strlen($word);
				$lim = 10;
				$lim = ($length * 2 > $lim) ? $lim : $length * 2 ;
				foreach ($rows as $row) {
					$aWords = preg_split("#\s#", trim($row['word']));
					foreach ( $aWords as $w) {
						if ( abs($length - strlen(trim($w) ) ) < $lim) {
							$buf[] = $row;
							break;
						}
					}
					
				}
				$rows = $buf;
			} else {
				$rows = query("SELECT word, description, source FROM sts ORDER BY RAND() LIMIT 5", $n);
				foreach ($rows as $i => $row) {
					$rows[$i]['description'] = $row['word'] . ': ' . $row['description'];
				}
			}
			//print_r($rows);die;
		}
		require_once __DIR__ . '/index.tpl.php';
	}
	/**
	 * @description Получить строковое наименование источника. Используется в шаблоне
	 * @return string Windows-1251
	*/
	private function _getSource($s)
	{
		$sR = ($s == 'sts' ? $this->_sTs : $this->_sDal);
		if ($s == 'ozh') {
			$sR = $this->_sOzh;
		}
		return $sR;
	}
	/**
	 * @description Записывает в поля _sDal, _sOzh, _sTs наименования источников конвертированные в Windows-1251
	*/
	private function _initSourcesString()
	{
		$this->_sDal = utils_cp1251('Толковый словарь Даля');
		$this->_sTs  = utils_cp1251('Современный толковый словарь');
		$this->_sOzh = utils_cp1251('Толковый словарь Ожегова');
	}
}
new App();
