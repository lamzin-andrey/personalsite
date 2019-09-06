<?php
//PHP 5.6!
require_once __DIR__ . '/../../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/cpagecompiler.php';
require_once DOC_ROOT . '/q/q/treealg.php';

require_once DOC_ROOT . '/p/lang/ru.php';
require_once DOC_ROOT . '/q/q/lang.php';


class PortfoliolistCompiler extends CPageCompiler {

	/** @property int nCategory Идентификатор категории*/
	public $nCategory;
	
	/** @property bool $bPreprocesContent*/
	public $bPreprocesContent = false;
	
	/** @property string $_sOuterWorkLinkHtml шаблон ссылки на внешнюю страницу с работой*/
	private $_sOuterWorkLinkHtml = '';
	
	/** @property string $_sWorkLinkHtml шаблон внутренней ссылки на работу */
	private $_sWorkLinkHtml = '';
	
	/** @property string $_sArticleItemHtml шаблон ссылки на статью */
	private $_sArticleItemHtml = '';
	
	/** @property string $_sArticlesHtml шаблон группы ссылок на связанные статьи */
	private $_sArticlesHtml = '';
	
	/** @property string $_sTimeHtml шаблон затраченного времени работы */
	private $_sTimeHtml = '';

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
		$sItemTpl = file_get_contents(DOC_ROOT . '/p/view/site/portfolio/listitem.html');
		
		foreach ($aData as $aRow) {
			$this->url = $aRow['url'];
			$s = str_replace('{heading}', $this->_parseHeading($aRow['heading'], intval($aRow['dont_create_page']), $this->url), $sItemTpl);
			$s = str_replace('{shortdesc}', $aRow['shortdesc'], $s);
			$s = str_replace('{outer_work_link}', $this->_parseOuterWorkLink($aRow['outer_link'], $aRow['outer_link_text'], $aRow['custom_download_content']), $s);
			$s = str_replace('{work_link}', $this->_parseWorkLink($aRow['product_file'], $aRow['product_file_textlink'], $aRow['sha256'], $aRow['custom_download_content']), $s);
			$s = str_replace('{custom_download_content}', $aRow['custom_download_content'], $s);
			$s = str_replace('{articles}', $this->_parseArticles(intval($aRow['id'])), $s);
			$s = str_replace('{time}', $this->_parseTime(intval($aRow['hours'])), $s);
			$s = str_replace('{logo}', $this->_parseLogo($aRow['logo']), $s);
			$sItemsHtml .= $s;
			$sDescription .= $aRow['description'];
			$aKeywords[] = $aRow['keywords'];
		}
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
	private function _parseHeading($sHeading, $nDontCreate, $sLink)
	{
		if (!$nDontCreate) {
			$s = '<a href="' . $sLink . '">' . $sHeading . '</a>';
			return $s;
		}
		return $sHeading;
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
	 * @description Set time text if time > 0
	 * @param int $nTime (in hours)
	 * @return string
	*/
	private function _parseTime($nTime)
	{
		if ($nTime) {
			if (!$this->_sTimeHtml) {
				$this->_sTimeHtml = file_get_contents(DOC_ROOT . '/p/view/site/portfolio/time.html');
			}
			$sTime = $nTime . ' ' . utils_getSuffix($nTime, '', l('one_hour', true), l('two_hours', true), l('many_hours', true));
			$s = str_replace('{hours}', $sTime, $this->_sTimeHtml);
			return $s;
		}
		return '';
	}
	/**
	 * @description Set related article list
	 * @return string
	*/
	private function _parseArticles($nWorkId)
	{
		$n = 0;
		$aData = query('SELECT p.url, p.heading FROM portfolio_pages AS pp 
			LEFT JOIN pages as p ON p.id = pp.page_id
			WHERE pp.work_id = ' . $nWorkId, $n);
		if ($n) {
			if (!$this->_sArticlesHtml) {
				$this->_sArticlesHtml = file_get_contents(DOC_ROOT . '/p/view/site/portfolio/articles.html');
				$this->_sArticleItemHtml = file_get_contents(DOC_ROOT . '/p/view/site/portfolio/articleitem.html');
			}
			$sItems = '';
			foreach ($aData as $aRow) {
				$s = str_replace('{heading}', $aRow['heading'], $this->_sArticleItemHtml);
				$s = str_replace('{url}', $aRow['url'], $s);
				$sItems .= $s;
			}
			$s = str_replace('{articleitems}', $sItems, $this->_sArticlesHtml);
			return $s;
		}
		return '';
	}
	/**
	 * @description Set inner link to product
	 * @return string
	*/
	private function _parseWorkLink($sLink, $sText, $sha256, $sCustomDownloadContent)
	{
		if ($sCustomDownloadContent) {
			return '';
		}
		$sLink = trim($sLink);
		$sha256 = trim($sha256);
		if ($sLink && $sha256 ) {
			
			$filePath = DOC_ROOT . $sLink;
			if (!file_exists($filePath)) {
				return '';
			}
			
			$sText = trim($sText);
			if (!$this->_sWorkLinkHtml) {
				$this->_sWorkLinkHtml = file_get_contents(DOC_ROOT . '/p/view/site/portfolio/worklink.html');
			}
			$sTpl = $this->_sWorkLinkHtml;
			$s = str_replace('{product_file}', $sLink, $sTpl);
			if (!$sText) {
				$sText = $sLink;
			}
			$s = str_replace('{product_file_textlink}', $sText, $s);
			$s = str_replace('{sha256}', $sha256, $s);
			return $s;
		}
		return '';
	}
	/**
	 * @description Set outer link to product
	 * @return string
	*/
	private function _parseOuterWorkLink($sLink, $sText, $sCustomDownloadContent)
	{
		if ($sCustomDownloadContent) {
			return '';
		}
		$sLink = trim($sLink);
		if ($sLink) {
			$sText = trim($sText);
			if (!$this->_sOuterWorkLinkHtml) {
				$this->_sOuterWorkLinkHtml = file_get_contents(DOC_ROOT . '/p/view/site/portfolio/outerworklink.html');
			}
			$sTpl = $this->_sOuterWorkLinkHtml;
			$s = str_replace('{outer_link}', $sLink, $sTpl);
			if (!$sText) {
				$sText = $sLink;
			}
			$s = str_replace('{outer_link_text}', $sText, $s);
			return $s;
		}
		return '';
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
		$this->aData = query('SELECT * FROM portfolio WHERE is_deleted != 1 AND hide_from_productlist != 1 ORDER BY delta');
		$this->title = l('Andrey\'s portfolio', true);
		$url = '/portfolio/';
		$this->outputFile = DOC_ROOT . '/portfolio/index.html';
		$this->canonicalUrl = $url;
		$this->heading = l('My works', true);
		$this->og_image = '';
		$this->nCategory = 0;
		
		$this->og_title = '';//TODO
		$this->og_description = '';//TODO
		$this->compile();
		unset($_REQUEST['noxhr']);
	}
	/**
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
			file_put_contents('/home/andrey/log.log', print_r($this->aData, true) . "\n", FILE_APPEND);
			$this->title = l('Andrey\'s portfolio', true) . (isset($aCategoryData['cname']) ? (' ' . $aCategoryData['cname'] . ' ') : '');
			$aUrl = explode('/', $sCompilerUrl);
			unset($aUrl[ count($aUrl) - 1 ]);
			unset($aUrl[ count($aUrl) - 1 ]);
			$url = join('/', $aUrl) . '/';
			$this->url = $url;
			$this->outputFile = DOC_ROOT . $url . 'index.html';
			file_put_contents('/home/andrey/log.log', 'will save in ' . $this->outputFile . "\n", FILE_APPEND);
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
			file_put_contents('/home/andrey/log.log', $sCategoryIdList . "\n", FILE_APPEND);
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
	 * @description 
	*/
	private function _setRightMenu()
	{
		$this->rightMenu = '';
		$oRightMenuCompiler = new RightMenuCompiler();
		$oRightMenuCompiler->loadData();
		$this->rightMenu = $oRightMenuCompiler->compile(false);
	}
}
