<?php
//PHP 5.6!

//It run every hour:25:00

require_once __DIR__ . '/../../../q/q/config.php';
require_once DOC_ROOT . '/q/q/mysql.php';
require_once DOC_ROOT . '/q/q/utils.php';
require_once DOC_ROOT . '/p/ctrl/articles/classes/articleslistcompiler.php';
require_once DOC_ROOT . '/p/ctrl/portfolio/classes/portfoliolistcompiler.php';


if (!function_exists('getallheaders')) {
	function getallheaders(){
		return [];
	}
}
/**
 * @class Task_TrollKillerTop10 - генерация страницы /portfolio/web/userscripts/trollkiller/list/ и всех 10 страниц
 * 	/portfolio/web/userscripts/trollkiller/user/N пользователей, входящих в Топ 10
*/
class Task_RecompileMainLists {
	public $uid = 0;
	
	public function __construct()
	{
		global $sLangDir;
		$sLangDir = __DIR__ . '/../../lang';
		
		//Articles Pages
		$oCompiler = new ArticlesListCompiler();
		$oCompiler->compileMainList(true);
		
		//Portfolio pages
		$oCompiler = new PortfolioListCompiler();
		$oCompiler->compileMainList(true);
		$oCompiler->compileLevelsLists(2409);
	}
}
new Task_RecompileMainLists();
