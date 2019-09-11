<?php
require __DIR__ . '/adminauthjson.php';
/**
 * @class ArticlesMoveToPage  - перемещение записи-страницы в списке с одной страницы на другую
*/
class ArticlesMoveToPage extends AdminAuthJson {
	public $uid = 0;
	
	
	public function __construct() {
		$this->table = 'pages';
		parent::__construct();
		
		$nId = ireq('id');
		$this->tsreq('d');
		
		$aResult = [
			'srcId' => $nId,
		];
		if ($nId && ($this->d == 'u' || $this->d == 'd')) {
			$nPos = dbvalue('SELECT delta FROM ' . $this->table . ' WHERE id = ' . $nId . ' LIMIT 1');
			$sOperation = $this->d == 'd' ? '>=' : '<=';
			$sDirection = $this->d == 'd' ? 'ASC' : 'DESC';
			$aRows = query('SELECT id, url, heading, delta FROM ' . $this->table . ' WHERE delta ' . $sOperation .  ' ' . $nPos . ' AND is_deleted != 1 ' . ' ORDER BY '. $this->orderField . ' ' . $sDirection  . ' LIMIT 2', $nRows);
			if ($nRows == 2) {
				$aRows = array_column($aRows, null, 'id');
				$aMovingRow = ($aRows[$nId] ?? []);
				/*var_dump($nId);
				var_dump($aMovingRow);
				var_dump($aRows);
				die;/**/
				unset($aRows[$nId]);
				$aRow = current($aRows);//it next or prew row
				if ($aMovingRow['delta'] == $aRow['delta']) {
					if ($this->d == 'd') {
						$aMovingRow['delta']++;
					} else {
						$aRow['delta']++;
					}
				}
				$this->_swapRecords($nId, $aMovingRow['delta'], $aRow['id'], $aRow['delta']);
				$aResult['newRec'] = $aRow;
				json_ok_arr($aResult);
			} else {
				$aResult['msg'] = l('Record is ' . ($this->d == 'd' ? 'last' : 'top'));
			}
		}
		$aResult['msg'] = l('Record is WTF!!!!');
		//utils_header_utf8();
		json_error_arr($aResult);
		
		
	}
	/**
	 * @description Меняет позиции двух записей
	 * @param int $nId1
	 * @param int $nPos1
	 * @param int $nId2
	 * @param int $nPos2
	*/
	public function _swapRecords(int $nId1, int $nPos1, int $nId2, int $nPos2)
	{
		$sql = 'UPDATE ' . $this->table . ' SET delta = ' . $nPos2 . ' WHERE id = ' . $nId1;
		query($sql);
		$sql = 'UPDATE ' . $this->table . ' SET delta = ' . $nPos1 . ' WHERE id = ' . $nId2;
		query($sql);
	}
}
