<?php
class Route {
	public function __construct() {
		$url  = $_SERVER['REQUEST_URI'];
		$aUrl = explode('?', $url);
		$baseUrl = $aUrl[0];

		$this->master = __dir__ . '/master.tpl.php';
		$handler = __dir__ . '/ctrl/main.php';
		$this->view    = __dir__ . '/view/home.tpl.php';
		
		if ($baseUrl == '/p/') {
			$this->master = __dir__ . '/master.tpl.php';
			$handler = __dir__ . '/ctrl/main.php';
			$this->view    = __dir__ . '/view/home.tpl.php';
			require_once $handler;
			$this->app = new ArticleEditor();
		}
		if ($baseUrl == '/p/articleslist.jn/') {
			$handler = __dir__ . '/ctrl/articlespage.php';
			require_once $handler;
			$this->app = new ArticlesPage();
		}
		if ($baseUrl == '/p/articlesave.jn/') {
			$handler = __dir__ . '/ctrl/articlesave.php';
			require_once $handler;
			$this->app = new ArticlePost();
		}
		//Локализация для DataTables
		if ($baseUrl == '/p/datatablelang.jn/') {
			$handler = __dir__ . '/ctrl/datatablelang.php';
			require_once $handler;
			$this->app = new DataTablesLang();
		}
		if ($baseUrl == '/p/removearticle.jn/') {
			$handler = __dir__ . '/ctrl/removearticle.php';
			require_once $handler;
			$this->app = new RemoveArticle();
		}
		if ($baseUrl == '/p/signin/') {
			$handler = __dir__ . '/ctrl/signin.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/signinform.tpl.php';
			$this->master    = __dir__ . '/view/masters/loginmaster.tpl.php';
			$this->app = new Signin();
		}
		if ($baseUrl == '/p/signin.jn/') {
			$handler = __dir__ . '/ctrl/signin.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/signinform.tpl.php';
			$this->app = new Signin();
		}
		if ($baseUrl == '/p/signup/') {
			$handler = __dir__ . '/ctrl/signup.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/signupform.tpl.php';
			$this->master    = __dir__ . '/view/masters/loginmaster.tpl.php';
			$this->app = new Signup();
		}
		if ($baseUrl == '/p/signup.jn/') {
			$handler = __dir__ . '/ctrl/signup.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/signupform.tpl.php';
			$this->app = new Signup();
		}
		if ($baseUrl == '/p/articlelogoupload.jn/') {
			$handler = __dir__ . '/ctrl/articlelogoupload.php';
			require_once $handler;
			$this->app = new ArticleLogoUpload();
		}
		if ($baseUrl == '/p/articleogimageupload.jn/') {
			$handler = __dir__ . '/ctrl/articleogimageupload.php';
			require_once $handler;
			$this->app = new ArticleOgImageUpload();
		}
		if ($baseUrl == '/p/articleinlineimageupload.jn/') {
			$handler = __dir__ . '/ctrl/articleinlineimageupload.php';
			require_once $handler;
			$this->app = new ArticleInlineImageUpload();
		}
		if ($baseUrl == '/p/reset/') {
			$handler = __dir__ . '/ctrl/reset.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/resetform.tpl.php';
			$this->master    = __dir__ . '/view/masters/loginmaster.tpl.php';
			$this->app = new Reset();
		}
		if ($baseUrl == '/p/reset.jn/') {
			$handler = __dir__ . '/ctrl/reset.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/resetform.tpl.php';
			$this->app = new Reset();
		}
		if ($baseUrl == '/p/logout/') {
			$handler = __dir__ . '/ctrl/logout.php';
			require_once $handler;
			$this->app = new Logout();
		}
	}
}
$route = new Route();
$app = $route->app;
