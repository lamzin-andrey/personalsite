<?php
//PHP 5.6!
require_once __DIR__ . '/../../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/cpagecompiler.php';
require_once DOC_ROOT . '/q/q/swearsword.php';

require_once DOC_ROOT . '/p/lang/ru.php';
require_once DOC_ROOT . '/q/q/lang.php';


class PageUserTrollsCompiler extends CPageCompiler {

	public function __construct()
	{
		$this->tpl = DOC_ROOT . '/p/view/site/masters/trollkiller.html';
		$this->displayDate = l('Updated') . date(' d.m.Y ') . l('at') . date(' H:i');
	}
	/**
	 * @description
	 * @param array $aData
	 * @return string
	*/
	public function compile($bSaveNow = true)
	{
		$sName = $this->sName;
		$nUid = $this->nUid;
		$this->title = l('Trolls banned by %', false, $sName);
		$this->canonicalUrl = '/portfolio/web/userscripts/trollkiller/user/' . $nUid . '/';
		$this->bc = $this->_setBC();
		$this->heading = l('Trolls banned by user % using TrollKiller', false, $sName);
		
		$aData = query('SELECT * FROM trollkiller WHERE uid = ' . $nUid . ' ORDER BY id DESC');
		$this->_setContent($aData);
		$s = parent::compile(false);
		$s = str_replace('{ALERT}', str_replace('{NAME}', $sName, file_get_contents(DOC_ROOT . '/p/view/site/trollkiller/useralert.html') ), $s);
		$sFolder = DOC_ROOT . '/portfolio/web/userscripts/trollkiller/user/' . $nUid;
		//utils_createDir($sFolder);
		//file_put_contents($sFolder . '/index.html', $s);
		$this->outputFile = $sFolder . '/index.html';
		$this->_save($s);
		return $s;
	}
	
	/**
	 * @description Set Content
	 * @return string
	*/
	private function _setContent($aData)
	{
		$sContentTpl = file_get_contents(DOC_ROOT . '/p/view/site/trollkiller/content.html');
		$s = $sLiTpl = file_get_contents(DOC_ROOT . '/p/view/site/trollkiller/userli.html');
		$sUlContent = '';
		foreach ($aData as $aRow) {
			$s = str_replace('{NAME}', $this->_swearFilter($aRow['nick']), $sLiTpl);//TODO Filter
			$s = str_replace('{MRID}', $aRow['a_mail_id'], $s);
			$s = str_replace('{ID}', $aRow['id'], $s);
			$s = str_replace('{REASON}', $this->_swearFilter($aRow['reason']), $s);;
			$sUlContent .= $s;
		}
		$this->content = str_replace('{TOP10LIST}', $sUlContent, $sContentTpl);
	}
	/**
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
