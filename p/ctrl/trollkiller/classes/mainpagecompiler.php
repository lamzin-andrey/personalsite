<?php

require_once __DIR__ . '/../../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/cpagecompiler.php';

require_once DOC_ROOT . '/p/lang/ru.php';
require_once DOC_ROOT . '/q/q/lang.php';



class MainpageCompiler extends CPageCompiler {

	public function __construct()
	{
		$this->tpl = DOC_ROOT . '/p/view/site/masters/trollkiller.html';
		$this->canonicalUrl = '/portfolio/web/userscripts/trollkiller/';
		$this->title = l('TrollKillers altrnative in ff');
		$this->heading = l('TrollKillers altrnative');
		$this->bc = $this->_setBC();
		$this->displayDate = date('d.m.Y ') . l('at') . date(' H:i');
		
		
		
		$this->description = ('TrollKillers description');
		$this->keywords = l('TrollKillers kw');
		$this->ogTitle = l('TrollKillers altrnative');
		$this->ogDescription = 'TrollKillers description';
		$this->ogImage = '/i/logotroll5.jpg';
		
	}
	/**
	 * @description
	 * @param array $aData
	 * @return string
	*/
	public function compile($bSaveNow = true)
	{
		$this->_setContent();
		$this->outputFile = DOC_ROOT . $this->canonicalUrl . 'index.html';
		$s = parent::compile(false);
		$s = str_replace('{ALERT}', '', $s);
		$this->_save($s);
		return $s;
	}
	
	/**
	 * @description Set Content
	 * @return string
	*/
	private function _setContent()
	{
		$this->content = file_get_contents(DOC_ROOT . '/p/view/site/trollkiller/maincontent.html');
	}
	/**
	 * @description Set Bread Crumbs
	 * @return string
	*/
	private function _setBC()
	{
		/*$tpl = DOC_ROOT . '/p/view/site/trollkiller/bc.html';
		$s = file_get_contents($tpl);
		$s = str_replace('{BC_ITEMS}', '', $s);
		$s = str_replace('{MENU_ACTIVE_ITEM}', l('Troll Killers Registration'), $s);*/
		$s = '';
		return $s;
	}
}
