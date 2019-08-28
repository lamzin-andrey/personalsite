<?php

require_once __DIR__ . '/../../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/cpagecompiler.php';

require_once DOC_ROOT . '/p/lang/ru.php';
require_once DOC_ROOT . '/q/q/lang.php';



class RegistrationCompiler extends CPageCompiler {

	public function __construct()
	{
		$this->tpl = DOC_ROOT . '/p/view/site/masters/trollkiller.html';
		$this->canonicalUrl = '/portfolio/web/userscripts/trollkiller/signup/';
		$this->title = l('Troll Killers Registration');
		$this->heading = l('Troll Killers Registration heading');
		$this->bc = $this->_setBC();
		$this->heading = l('TrollKillers altrnative');
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
		$this->content = file_get_contents(DOC_ROOT . '/p/view/site/trollkiller/registrationcontent.html');
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
		$s = str_replace('{MENU_ACTIVE_ITEM}', l('Troll Killers Registration'), $s);
		return $s;
	}
}
