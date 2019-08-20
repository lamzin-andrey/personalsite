<?php
//It run 15 every hour:05:00

require_once __DIR__ . '/../../../q/q/config.php';
require_once __DIR__ . '/../../../q/q/mysql.php';
require_once __DIR__ . '/../../../q/q/utils.php';
require_once __DIR__ . '/classes/pagetop10compiler.php';
//require_once __DIR__ . '/classes/pageusercompiler.php';

if (!function_exists('getallheaders')) {
	function getallheaders(){
		return [];
	}
}
/**
 * @class Task_TrollKillerTop10 - генерация страницы /portfolio/web/userscripts/trollkiller/list/ и всех 10 страниц
 * 	/portfolio/web/userscripts/trollkiller/user/N пользователей, входящих в Топ 10
*/
class Task_TrollKillerTop10 {
	public $uid = 0;
	
	public function __construct()
	{
		global $sLangDir;
		$sLangDir = __DIR__ . '/../../lang';
		$this->table = 'trollkiller';
		$sql = 'SELECT t.uid, COUNT(t.id) AS cnt, tui.name, tui.imgpath, u.name AS dname, u.surname
			FROM trollkiller AS t 
			
			LEFT JOIN trollkiller_userinfo AS tui ON tui.uid = t.uid
			
			LEFT JOIN ausers AS u ON u.id = t.uid
			
			GROUP BY uid ORDER BY cnt DESC LIMIT 10';
		
		$aData = query($sql);
		$oCompiler = new PageTop10Compiler();
		$oCompiler->aData = &$aData;
		$oCompiler->compile();
	}
	
	
}
new Task_TrollKillerTop10();
