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
}
