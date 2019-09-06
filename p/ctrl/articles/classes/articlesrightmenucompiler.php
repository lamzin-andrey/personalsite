<?php
//PHP 5.6!
require_once __DIR__ . '/../../classes/rightmenucompilerbase.php';

class ArticlesRightMenuCompiler extends RightMenuCompilerBase {
	public function __construct() {
		$this->sFolder = 'articles';
		parent::__construct();
	}
}
