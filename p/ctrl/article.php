<?php
require __DIR__ . '/adminauthjson.php';
/**
 * @class ArticlesPage  - получение списка страниц для страницы админки
*/
class Article extends AdminAuthJson {
	public $uid = 0;
	
	public function __construct() {
		$this->table = 'pages';
		parent::__construct();
		$this->ireq('id');
		$article = $this->rec();
		if ($article['logo'] > 0) {
			$article['logo'] = dbvalue("SELECT path FROM logos WHERE id = {$article['logo']}");
		} else {
			$article['logo'] = DEFAULT_ARTICLE_LOGO;
		}
		if (!$article['logo']) {
			$article['logo'] = DEFAULT_ARTICLE_LOGO;
		}
		if ($article['og_image'] == '') {
			$article['og_image'] = DEFAULT_ARTICLE_LOGO;
		}
		json_ok_arr($article);
	}
}
