<?php

require_once __DIR__ . '/../../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/cpagecompiler.php';

require_once DOC_ROOT . '/p/lang/ru.php';
require_once DOC_ROOT . '/q/q/lang.php';

require_once __DIR__ . '/pageusertrollscompiler.php';
require_once __DIR__ . '/rightmenucompiler.php';

class PageTop10Compiler extends CPageCompiler {

	/** @const SCORE_IMG_LIMIT  Рисуем не более SCORE_IMG_LIMIT "звёздочек" */
	const SCORE_IMG_LIMIT = 5;
	public function __construct()
	{
		$this->tpl = DOC_ROOT . '/p/view/site/masters/trollkiller.html';
		$this->canonicalUrl = '/portfolio/web/userscripts/trollkiller/list/';
		$this->title = l('Top 10 Troll Killers');
		$this->bc = $this->_setBC();
		$this->heading = l('List a most active Troll Killers');
		$this->displayDate = date('d.m.Y ') . l('at') . date(' H:i');
		//
	}
	/**
	 * @description
	 * @param array $aData
	 * @return string
	*/
	public function compile($bSaveNow = true)
	{
		$aData = $this->aData;
		$this->_setContent($aData);
		$this->_setRightMenu();
		
		$this->outputFile = DOC_ROOT . '/portfolio/web/userscripts/trollkiller/list/index.html';
		$s = parent::compile(false);
		$s = str_replace('{ALERT}', '', $s);
		$this->_save($s);
		return $s;
	}
	/**
	 * @description Set Right Menu Html
	 * @return string
	*/
	private function _setRightMenu()
	{
		$this->rightMenu = '';
		$oRightMenuCompiler = new RightMenuCompiler();
		$oRightMenuCompiler->loadData();
		$this->rightMenu = $oRightMenuCompiler->compile(false);
	}
	
	/**
	 * @description Set Content
	 * @return string
	*/
	private function _setContent($aData)
	{
		$sContentTpl = file_get_contents(DOC_ROOT . '/p/view/site/trollkiller/content.html');
		$s = $sLiTpl = file_get_contents(DOC_ROOT . '/p/view/site/trollkiller/top10li.html');
		$sUlContent = '';
		$oUserPageCompiler = new PageUserTrollsCompiler();
		foreach ($aData as $aRow) {
			$sName = $this->_getTrollKillerName($aRow);
			$s = str_replace('{NAME}', $sName, $sLiTpl);
			$s = str_replace('{QNT}', $this->_getTrollKillerPoints($aRow['cnt']), $s);
			$s = str_replace('{IMGRATING}', $this->_getTrollKillerRatingView($aRow['cnt']), $s);
			$s = str_replace('{UID}', $aRow['uid'], $s);
			$s = str_replace('{AVATAR}', $aRow['imgpath'], $s);
			$sUlContent .= $s;
			$oUserPageCompiler->sName = $sName;
			$oUserPageCompiler->nUid = $aRow['uid'];
			$oUserPageCompiler->compile();//TODO working method
		}
		$this->content = str_replace('{TOP10LIST}', $sUlContent, $sContentTpl);
	}
	/**
	 * @description Отрисовываает "звёздочки" от 1 до 5
	 * @param int $nCnt
	 * @return string
	*/
	private function _getTrollKillerRatingView($nCnt)
	{
		$sTpl =  file_get_contents(DOC_ROOT . '/p/view/site/trollkiller/top10li_scoreimg.html');
		$sR = '';
		for ($i = 0; $i < static::SCORE_IMG_LIMIT; $i++) {
			$sRepl = 'g';
			if ($i < $nCnt) {
				$sRepl = '';
			}
			$s = str_replace('{BLACK}', $sRepl, $sTpl);
			$sR .= $s;
		}
		return $sR;
	}
	/**
	 * @description Если забаненых троллей более 5 вернуть html badge с числом забаненных
	 * @param int $nCnt
	 * @return string
	*/
	private function _getTrollKillerPoints($nCnt)
	{
		if ($nCnt > static::SCORE_IMG_LIMIT) {
			$s =  file_get_contents(DOC_ROOT . '/p/view/site/trollkiller/top10li_qnt.html');
			$s = str_replace('{SCORE}', $nCnt, $s);
			return $s;
		}
		return '';
	}
	/**
	 * @description Если определено имя из стороннего сервиса, вернет его, иначе вернет логин и фамилию из учётки сайта
	 * @param array $aRow
	 * @return string
	*/
	private function _getTrollKillerName($aRow)
	{
		$sName = trim($aRow['name']);
		if ($sName) {
			return $sName;
		}
		return utils_getUserDisplayName($aRow, 'dname');
	}
	
	
	/**
	 * @description Set Bread Crumbs
	 * @return string
	*/
	private function _setBC()
	{
		$tpl = DOC_ROOT . '/p/view/site/trollkiller/bc.html';
		$s = file_get_contents($tpl);
		$s = str_replace('{BC_ITEMS}', '', $s);
		$s = str_replace('{MENU_ACTIVE_ITEM}', l('Top 10 active Troll Killers'), $s);
		return $s;
	}
}
