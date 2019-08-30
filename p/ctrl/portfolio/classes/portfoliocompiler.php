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

	public function __construct()
	{
		$this->tpl = DOC_ROOT . '/p/view/site/masters/b4first.html';
		$this->displayDate = l('Updated') . date(' d.m.Y ') . l('at') . date(' H:i');
	}
	/**
	 * @description
	 * @param array $aData
	 * @return string
	*/
	public function compile($bSaveNow = true)
	{
		$this->bc = $this->_setBC();//TODO build from ->nCategory
		$s = parent::compile();
		/*$sFolder = DOC_ROOT . '/portfolio/web/userscripts/trollkiller/user/' . $nUid;
		$this->outputFile = $sFolder . '/index.html';
		$this->_save($s);*/
		return $s;
	}
	
	/**
	 * TODO
	 * @description Set Bread Crumbs
	 * @return string
	*/
	private function _setBC()
	{
		$tpl = DOC_ROOT . '/p/view/site/trollkiller/bc.html';
		$s = file_get_contents($tpl);
		$dcItemTpl = file_get_contents( DOC_ROOT . '/p/view/site/trollkiller/bc_item.html' );
		$sItem = str_replace('{TEXT}', l('Top 10 active Troll Killers'), $dcItemTpl);
		$sItem = str_replace('{LINK}', '/portfolio/web/userscripts/trollkiller/list/', $sItem);
		$s = str_replace('{BC_ITEMS}', $sItem, $s);
		$s = str_replace('{MENU_ACTIVE_ITEM}', l('Users_trolls', false, $this->sName), $s);
		return $s;
	}
	
	private function _swearFilter($s)
	{
		$o = new SwearSword();
		return $o->sanitize($s);
	}
}
