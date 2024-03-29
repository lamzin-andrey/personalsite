<?php
//PHP 5.6!
require_once __DIR__ . '/../../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/cpagecompiler.php';
require_once DOC_ROOT . '/q/q/treealg/src/treealgorithms.php';
use \Landlib\TreeAlgorithms;

require_once DOC_ROOT . '/p/lang/ru.php';
require_once DOC_ROOT . '/q/q/lang.php';
require_once __DIR__ . '/rightmenucompiler.php';

require_once DOC_ROOT . '/p/ctrl/classes/liveinternet.php';


class PortfolioCompiler extends CPageCompiler {

	/** @property int nCategory Идентификатор категории*/
	public $nCategory;
	
	/** @property int nCategory Идентификатор работы */
	public $nProductId;
	
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
		$this->_setRightMenu();
		$s = parent::compile(false);
		$s = str_replace('{rid}', $this->nProductId, $s);
		$this->_save($s);
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
		$aUrlData = $this->_getUrlArr();//ODO it need
		
		for ($i = 0; $i < $nSz; $i++) {
			$oData = $aBcData[$i];
			$sItem = str_replace('{TEXT}', $oData->category_name, $dcItemTpl);
			$sLinkPart = utils_translite_url(utils_utf8($oData->category_name));
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
	/**
	 * @description 
	*/
	private function _setRightMenu()
	{
		$this->rightMenu = '';
		$oRightMenuCompiler = new RightMenuCompiler();
		$oRightMenuCompiler->nProductId = $this->nProductId;
		$oRightMenuCompiler->loadData();
		$this->rightMenu = $oRightMenuCompiler->compile(false);
	}
	/**
     * @description Вернуть код счетчика liveInternet
     * @return string
    */
    protected function _getLiveinternetCounterHtml()
    {
		return Liveinternet::getHtml();
	}
}
