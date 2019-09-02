<?php
//PHP 5.6!
require_once __DIR__ . '/../../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/cpagecompiler.php';
require_once DOC_ROOT . '/q/q/treealg.php';

require_once DOC_ROOT . '/p/lang/ru.php';
require_once DOC_ROOT . '/q/q/lang.php';


class PortfolioCompiler extends CPageCompiler {

	/** @property int nCategory Идентификатор категории*/
	public $nCategory;
	
	/** @property bool $bPreprocesContent*/
	public $bPreprocesContent = true;

	public function __construct()
	{
		$this->tpl = DOC_ROOT . '/p/view/site/masters/b4first.html';
		$this->displayDate = l('Updated', true) . date(' d.m.Y ') . l('at', true) . date(' H:i');
	}
	/**
	 * @description
	 * @param array $aData
	 * @return string
	*/
	public function compile($bSaveNow = true)
	{
		$this->bc = $this->_setBC();
		$s = parent::compile();
		return $s;
	}
	
	/**
	 * @description Set Bread Crumbs
	 * @return string
	*/
	private function _setBC()
	{
		$aBcData = $this->_getBcData();
		/*print_r($aBcData);
		die;/**/
		
		$tpl = DOC_ROOT . '/p/view/site/bc/bc.html';
		$s = file_get_contents($tpl);
		$dcItemTpl = file_get_contents( DOC_ROOT . '/p/view/site/bc/bc_item.html' );
		
		$aBcStrings = [];
		$nSz = count($aBcData);
		$sLink = '/';
		$aUrlData = $this->_getUrlArr();
		
		for ($i = 0; $i < $nSz; $i++) {
			$oData = $aBcData[$i];
			$sItem = str_replace('{TEXT}', $oData->category_name, $dcItemTpl);
			$sLinkPart = isset($aUrlData[$i]) ? $aUrlData[$i] : utils_translite_url(utils_utf8($oData->category_name));
			$sLink = $sLink . $sLinkPart . '/';
			$sItem = str_replace('{LINK}', $sLink, $sItem);
			$aBcStrings[] = $sItem;
		}
		$s = str_replace('{BC_ITEMS}', join('', $aBcStrings), $s);
		$s = str_replace('{MENU_ACTIVE_ITEM}', $this->heading, $s);
		return $s;
	}
	/**
	 * @description Load data use TreeAlgorithm
	*/
	private function _getBcData()
	{
		$_REQUEST['noxhr'] = true;
		$aData = query('SELECT * FROM portfolio_categories WHERE is_deleted != 1 ORDER BY id');
		$oTree = TreeAlgorithms::buildTreeFromFlatList($aData);
		$oTree = isset($oTree[0]) ? $oTree[0] : null;
		unset($_REQUEST['noxhr']);
		if ($oTree) {
			return TreeAlgorithms::getNodesByNodeId($oTree, $this->nCategory);
		}
		return [];
	}
	/**
	 * @description Разбивает переданный url страницы по частям, сохраняет только не пустые
	*/
	private function _getUrlArr()
	{
		$a = explode('/', $this->url);
		$aR = [];
		foreach ($a as $s) {
			$s = trim($s);
			if ($s) {
				$aR[] = $s;
			}
		}
		return $aR;
	}
}
