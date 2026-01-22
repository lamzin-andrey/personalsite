<?php
//PHP 5.6!
require_once __DIR__ . '/../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/cpagecompiler.php';

require_once DOC_ROOT . '/p/lang/ru.php';
//require_once DOC_ROOT . '/q/q/lang.php';


class RightMenuCompilerBase extends CPageCompiler {
	
	/** const LIMIT_ITEMS (in right menu) */
	const LIMIT_ITEMS = 5;

	/** @property int nProductId продукт, который надо пропустить, потому что находимся на его странице */
	public $nProductId;
	
	/** @property int nArticleId продукт, который надо пропустить, потому что находимся на его странице */
	public $nArticleId;
	
	/** @property array aPortfolioData */
	public $aPortfolioData = [];
	
	/** @property array articlesData */
	public $articlesData = [];
	
	/** 
	 * @override Можно указать путь к папке портфолио или статей 
	 * @property string $sFolder
	*/
	public $sFolder = 'portfolio';
	

	public function __construct()
	{
		$this->tpl = DOC_ROOT . '/p/view/site/right_menu/' . $this->sFolder . '/layout.html';
		$this->itemTpl = DOC_ROOT . '/p/view/site/right_menu/' . $this->sFolder . '/item.html';
		$this->secondaryTpl = DOC_ROOT . '/p/view/site/right_menu/' . $this->sFolder . '/secondary.html';
		$this->displayDate = '';
	}
	/**
	 * @description
	 * @param array $aData
	 * @return string
	*/
	public function compile($bSaveNow = true)
	{
		$sLayoutTpl = file_get_contents($this->tpl);
		$sItemTpl = file_get_contents($this->itemTpl);
		$sProductsHtml = '';
		foreach ($this->aPortfolioData as $aRow) {
			$s = str_replace('{logo}', $this->_parseLogo($aRow['logo']), $sItemTpl);
			if ($aRow['url']) {
				$s = str_replace('{url}', $aRow['url'], $s);
			} else {
				$s = str_replace('{url}', '/portfolio/', $s);
			}
			$s = str_replace('{heading}', $this->_parseHeading($aRow['right_menu_heading'], $aRow['heading']), $s);
			$s = str_replace('{description_secondary}', $this->_parseSecondaryText($aRow['right_menu_secondary_text']), $s);//TODO 
			$sProductsHtml .= $s;
		}
		
		$sArticlesHtml = '';
		foreach ($this->articlesData as $aRow) {
			$s = str_replace('{logo}', $this->_parseLogo($aRow['logo']), $sItemTpl);
			$s = str_replace('{heading}', $this->_parseHeading($aRow['menu_heading'], $aRow['heading']), $s);
			$s = str_replace('{url}', $aRow['url'], $s);
			$s = str_replace('{description_secondary}', $this->_parseSecondaryText($aRow['right_menu_secondary_text']), $s);
			$sArticlesHtml .= $s;
		}
		$s = str_replace('{PRODUCTS}', $sProductsHtml, $sLayoutTpl);
		$s = str_replace('{ARTICLES}', $sArticlesHtml, $s);
		return $s;
	}
	/**
	 * @description Get secondary template and set text
	 * @param string $heading
	 * @return string
	*/
	private function _parseSecondaryText($heading)
	{
		$heading = trim($heading);
		if ($heading) {
			$sTpl = file_get_contents($this->secondaryTpl);
			$s = str_replace('{description_secondary}', $heading, $sTpl);
			return $s;
		}
		return '';
	}
	/**
	 * @description Get link text
	 * @param string $specialHeading
	 * @param string $defaultHeading
	 * @return string
	*/
	private function _parseHeading($specialHeading, $defaultHeading)
	{
		$specialHeading = trim($specialHeading);
		if ($specialHeading) {
			return $specialHeading;
		}
		return $defaultHeading;
	}
	/**
	 * @description Set Logotype
	 * @return string
	*/
	private function _parseLogo($nLogoId)
	{
		$s = dbvalue('SELECT path FROM logos WHERE id = ' . $nLogoId);
		return $s;
	}
	
	
	public function loadData()
	{
		$_REQUEST['noxhr'] = 1;
		$sql = 'SELECT * FROM pages
				WHERE is_deleted != 1 AND is_hidden != 1 AND hidden_in_list != 1 
				ORDER BY (rating + `force`) DESC, delta ASC LIMIT ' . static::LIMIT_ITEMS;
		if ($this->nArticleId > 0) {
			$sql = 'SELECT * FROM pages WHERE is_deleted != 1 AND hidden_in_list != 1 AND is_hidden != 1 AND id != ' . $this->nArticleId
			 . ' ORDER BY (rating + `force`) DESC, delta ASC LIMIT ' . static::LIMIT_ITEMS;
		}
		$this->articlesData = query($sql);
		
		$sql = 'SELECT * FROM portfolio WHERE is_deleted != 1 AND hide_from_productlist != 1
					ORDER BY rating DESC, delta ASC LIMIT ' . static::LIMIT_ITEMS;
		if ($this->nProductId > 0) {
			$sql = 'SELECT * FROM portfolio WHERE is_deleted != 1 AND hide_from_productlist != 1 AND id != ' . $this->nProductId
			 . ' ORDER BY rating DESC, delta ASC LIMIT ' . static::LIMIT_ITEMS;
		}
		$this->aPortfolioData = query($sql);
		unset($_REQUEST['noxhr']);
	}
}
