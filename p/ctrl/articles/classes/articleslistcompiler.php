<?php
//PHP 5.6!
require_once __DIR__ . '/../../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/cpagecompiler.php';
require_once DOC_ROOT . '/q/q/treealg.php';

require_once DOC_ROOT . '/p/lang/ru.php';
require_once DOC_ROOT . '/q/q/lang.php';
//require_once DOC_ROOT . '/p/ctrl/articles/classes/articlesrightmenucompiler.php';
require_once DOC_ROOT . '/p/ctrl/portfolio/classes/rightmenucompiler.php';


class ArticlesListCompiler extends CPageCompiler {

	/** @property int nCategory Идентификатор категории*/
	public $nCategory = 0;
	
	/** @property bool $bPreprocesContent*/
	public $bPreprocesContent = false;
	

	public function __construct()
	{
		$this->tpl = DOC_ROOT . '/p/view/site/masters/articlelist.html';
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
		$this->_parseList();//content, seo-tags
		$s = parent::compile();//попробуем так
		return $s;
	}
	/**
	 * @description Set Bread Crumbs
	 * @return string
	*/
	private function _parseList()
	{
		$aData = &$this->aData;
		$sItemsHtml = '<ul class="list-group">';
		$sDescription = '';
		$aKeywords = [];
		$sItemTpl = file_get_contents(DOC_ROOT . '/p/view/site/articles/listitem.html');
				
		$bSourcePreprocessFlag = $this->bPreprocesContent;
		foreach ($aData as $aRow) {
			$this->url = $aRow['url'];
			$s = str_replace('{heading}', $this->_parseHeading($aRow['heading'], $this->url), $sItemTpl);
			
			$this->content = $aRow['content_block'];
			$this->bPreprocesContent = true;
			$this->_preprocessContent();
			
			$sContent = $this->_closeHtmlTag( substr($this->content, 0, 248) );
			
			
			$s = str_replace('{shortdesc}', ($sContent), $s);
			$s = str_replace('{logo}', $this->_parseLogo($aRow['logo']), $s);
			//STOP HERE
			$s = str_replace('{rating}', $aRow['rating'], $s);
			$sDate = utils_dateE2R($aRow['updated_at']);
			if ($sDate == '00:00 01.01.1970') {
				$sDate = utils_dateE2R($aRow['created_at']);
			}
			$s = str_replace('{updated_at}', $sDate, $s);
			$sItemsHtml .= $s;
			$sDescription .= $aRow['description'];
			$aKeywords[] = $aRow['keywords'];
		}
		$this->bPreprocesContent = $bSourcePreprocessFlag;
		$sItemsHtml .= '</ul>';
		$this->content = $sItemsHtml;
		$this->description = $sDescription;
		$this->keywords = join(',', $aKeywords);
	}
	/**
	 * @description Set link on heading
	 * @param int $nTime (in hours)
	 * @return string
	*/
	private function _parseHeading($sHeading, $sLink)
	{
		$s = '<a href="' . $sLink . '">' . $sHeading . '</a>';
		return $s;
	}
	/**
	 * @description Set time text if time > 0
	 * @param int $nTime (in hours)
	 * @return string
	*/
	private function _parseLogo($nLogoId)
	{
		$sLogo = dbvalue('SELECT path FROM logos WHERE id = ' . $nLogoId);
		return $sLogo;
	}
	
	/**
	 * TODO
	 * @description Set Bread Crumbs
	 * @return string
	*/
	private function _setBC()
	{
		if (!$this->nCategory) {
			return '';
		}
		
		$aBcData = $this->_getBcData();
		/*print_r($aBcData);
		die;/**/
		
		$tpl = DOC_ROOT . '/p/view/site/bc/bc.html';
		$s = file_get_contents($tpl);
		$dcItemTpl = file_get_contents( DOC_ROOT . '/p/view/site/bc/bc_item.html' );
		
		$aBcStrings = [];
		$nSz = count($aBcData) - 1;
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
		$oData = $aBcData[$nSz];
		$s = str_replace('{BC_ITEMS}', join('', $aBcStrings), $s);
		$s = str_replace('{MENU_ACTIVE_ITEM}', $oData->category_name, $s);
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
	public function compileMainList()
	{
		$this->_setRightMenu();
		$_REQUEST['noxhr'] = true;
		$this->aData = query('SELECT * FROM pages WHERE is_deleted != 1 AND is_hidden != 1 AND hidden_in_list != 1 ORDER BY rating DESC, delta ASC');
		$this->title = l('Andrey\'blog', true);
		$url = '/blog/';
		$this->outputFile = DOC_ROOT . '/blog/index.html';
		$this->canonicalUrl = $url;
		$this->heading = l('Blog records', true);
		$this->og_image = '';
		$this->nCategory = 0;
		
		$this->og_title = '';//TODO
		$this->og_description = '';//TODO
		$this->compile();
		unset($_REQUEST['noxhr']);
	}
	/**
	 * TODO это портфолио, перепигшшешь позже
	 * @description Компилирует списки работ на всех уровнях категорий портфолио, в которые вложена  $nCategoryId
	 * @param int $nCategoryId
	*/
	public function compileLevelsLists($nCategoryId)
	{
		$sCategoryIdList = $nCategoryId;
		$nTopCategory = $nCategoryId;
		$aCategoryData = query('SELECT id, parent_id, category_name AS cname FROM portfolio_categories');
		$aTree = TreeAlgorithms::buildTreeFromFlatList($aCategoryData);
		$oTree = isset($aTree[0]) ? $aTree[0] : null;
		$aPath = TreeAlgorithms::getNodesByNodeId($oTree, $nCategoryId);
		$sCompilerUrl = '/';
		foreach ($aPath as $oItem) {
			$sCompilerUrl .= utils_translite_url($oItem->cname) . '/';
		}
		$sCompilerUrl .= '0/';
		$allCategories = [$nCategoryId];
		$oCallback = new StdClass();
		$this->_aChildList = [];
		$oCallback->f = 'agregateChildsIdList';
		$oCallback->context = $this;
		while (true) {
			$_REQUEST['noxhr'] = true;
			$this->aData = query('SELECT * FROM portfolio WHERE category_id IN(' . $sCategoryIdList . ') AND is_deleted != 1 AND hide_from_productlist != 1 ORDER BY delta');
			$this->title = l('Andrey\'s portfolio', true) . (isset($aCategoryData['cname']) ? (' ' . $aCategoryData['cname'] . ' ') : '');
			$aUrl = explode('/', $sCompilerUrl);
			unset($aUrl[ count($aUrl) - 1 ]);
			unset($aUrl[ count($aUrl) - 1 ]);
			$url = join('/', $aUrl) . '/';
			$this->url = $url;
			$this->outputFile = DOC_ROOT . $url . 'index.html';
			$this->canonicalUrl = $url;
			$this->heading = l('My works', true);
			$this->og_image = '';
			$this->nCategory = $nTopCategory;
			
			$this->og_title = '';//TODO
			$this->og_description = '';//TODO
			
			$this->compile();
			
			$oNode = TreeAlgorithms::findById($oTree, $nTopCategory);
			if ($oNode && isset($oNode->parent_id)) {
				$nParentId = $oNode->parent_id;
			} else {
				$nParentId = 0;
			}
			if ($nParentId == 2402 || $nParentId == 0) {
				break;
			}
			
			$sCompilerUrl = $url;
			
			$nTopCategory = $nParentId;
			$oNode = TreeAlgorithms::findById($oTree, $nParentId);
			if ($oNode /*&& isset($oNode->children)*/) {
				TreeAlgorithms::walkAndExecuteAction($oNode, $oCallback);
				//$aCategories = array_column($oNode->children, 'id');
				//$allCategories = array_unique( array_merge($allCategories, $aCategories, [$nParentId]) );
				$allCategories = array_unique( array_merge($allCategories, $this->_aChildList, [$nParentId]) );
			} else {
				break;
			}
			$sCategoryIdList = join(',', $allCategories);
		}
		
		unset($_REQUEST['noxhr']);
		/**/
	}
	/**
	 * @description Собрать все дочерние компоненты
	 * @param $oNode
	*/
	public function agregateChildsIdList($oNode) 
	{
		$this->_aChildList[$oNode->id] = $oNode->id;
	}
	/**
	 * @description Закрыть все незакрытые html теги
	 * @return string
	*/
	public function _closeHtmlTag($s) 
	{
		$nSz =  strlen($s);
		$inTagBody = 0;
		$aTagMap = [];
		$ts = '';
		for ($i = 0; $i < $nSz; $i++) {
			$ch = $s[$i];
			if ($ch == '<') {
				$inTagBody = 1;
			}
			if ($inTagBody) {
				$ts .= $ch;
			}
			if ($ch == '>') {
				$inTagBody = 0;
				$oTagInfo = $this->_extractTagName($ts);
				$sTagName = $oTagInfo->name;
				if ($oTagInfo->isOpen) {
					$aTagMap[$sTagName] = 1;
				} else {
					unset($aTagMap[$sTagName]);
				}
				$ts = '';
			}
		}
		if ($inTagBody) {
			$ts .= '>';
			$s .= '>...';
			$oTagInfo = $this->_extractTagName($ts);
			$sTagName = $oTagInfo->name;
			if ($oTagInfo->isOpen) {
				$aTagMap[$sTagName] = 1;
			} else {
				unset($aTagMap[$sTagName]);
			}
		} else {
			$s .= '...';
		}
		
		$aTagMap = array_reverse($aTagMap);
		foreach ($aTagMap as $sw => $n) {
			$s .= '</' . $sw . '>' ;
		}
		return $s;
	}
	/**
	 * @param string $s
	 * @return StdClass {name, isOpen}
	*/
	private function _extractTagName($s)
	{
		$r = new StdClass();
		$r->name = '';
		$r->isOpen = true;
		$s = trim($s);
		$nSz = strlen($s);
		
		for ($i = 1; $i < $nSz; $i++) {
			$ch = $s[$i];
			if ($ch == '/') {
				$r->isOpen = false;
				continue;
			}
			if ($ch != ' ' && $ch != "\n" && $ch != "\t"  && $ch != '>') {
				$r->name .= $ch;
			} else {
				break;
			}
		}
		return $r;
	}
	/**
	 * @description 
	*/
	private function _setRightMenu()
	{
		$this->rightMenu = '';
		//$oRightMenuCompiler = new ArticlesRightMenuCompiler();
		$oRightMenuCompiler = new RightMenuCompiler();
		$oRightMenuCompiler->loadData();
		$this->rightMenu = $oRightMenuCompiler->compile(false);
	}
}
