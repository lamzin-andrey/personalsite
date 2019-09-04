<?php

require __DIR__ . '/../adminauthjson.php';
require __DIR__ . '/classes/portfoliolistcompiler.php';

/** 
 * @class PortfolioReorder  - переупорядочивание списка работ на одной странице
*/
class PortfolioReorder extends AdminAuthJson {
	public $uid = 0;
	
	public function __construct() {
		$this->table = 'portfolio';
		parent::__construct();
		$aIds = areq('a', 'intval');
		utils_header_utf8();
		if (count($aIds)) {
			$sIdList = join(',', $aIds);
			$min = intval( dbvalue('SELECT MIN(delta) FROM ' . $this->table . ' WHERE id IN(' . $sIdList . ')') );
			foreach ($aIds as $id) {
				query('UPDATE ' . $this->table . ' SET delta = ' . $min . ' WHERE id = ' . $id);
				$min++;
			}
			
			//TODO recompile
			
			//Прекомпилировать для основного листа портфолио
			//main portfolio list
			$oCompiler = new PortfoliolistCompiler();
			$oCompiler->compileMainList();
			
			//TODO Перекомпилировать для листов категорий
			
			//Получить категории всех переставляемых работ
			$aRowsData = query('SELECT DISTINCT category_id FROM ' . $this->table . ' WHERE id IN(' . $sIdList . ')' );
			//$aCategoryIdList = array_column($aRowsData, 'category_id');
			
			foreach ($aRowsData as $aRow) {
				//die('O-O');
				$oCompiler->compileLevelsLists($aRow['category_id']);
			}
			json_ok();
		}
		json_error('msg', l('Unable reorder article list: empty data'), 'a', print_r($aIds, 1) );
		
		
	}
}
