<?php
class Route {
	public function __construct() {
		$url  = $_SERVER['REQUEST_URI'];
		$aUrl = explode('?', $url);
		$this->_baseUrl =  $baseUrl = $aUrl[0];

		//Default
		$this->master = __dir__ . '/master.tpl.php';
		$handler = __dir__ . '/ctrl/main.php';
		$this->view    = __dir__ . '/view/home.tpl.php';
		//Локализация для DataTables
		if ($baseUrl == '/p/datatablelang.jn/') {
			$handler = __dir__ . '/ctrl/datatablelang.php';
			require_once $handler;
			$this->app = new DataTablesLang();
		}
		
		$this->_p();
		$this->_acategories();
		$this->_portfolio();
		$this->_portfoliocats();
		$this->_treedemo();
		$this->_larincevareciever();
		$this->_trollkiller();
		$this->_stat();
		$this->_phd();
		$this->_apipdf();
		$this->_work_exp22122020();
		$this->_work_exp18042022();
		
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
		
		if ($baseUrl == '/p/ua/') {
			echo '<!DOCTYPE html><meta name="viewport" content="	initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width,height=device-height,shrink-to-fit=no">' .  $_SERVER['HTTP_USER_AGENT'];
			die;
		}
		
		
		//временно, пока в консоли php 5
		if ($baseUrl == '/p/task_recompilemainlists/') {
			$handler = __dir__ . '/ctrl/articles/task_recompilemainlists.php';
			require_once $handler;
			die;
			//$this->app = new Logout();
		}
		///ctrl/articles/task_recompilemainlists.php
		
		
		if ($baseUrl == '/p/gts1/') {
			$handler = __dir__ . '/ctrl/ps/gts1.php';
			require_once $handler;
			$this->app = new GetTs1();
		}
		
		if ($baseUrl == '/p/sts1/') {
			$handler = __dir__ . '/ctrl/ps/sts1.php';
			require_once $handler;
			$this->app = new SetTs1();
		}
		
		if ($baseUrl == '/p/gts2/') {
			$handler = __dir__ . '/ctrl/ps/gts2.php';
			require_once $handler;
			$this->app = new GetTs2();
		}
		
		if ($baseUrl == '/p/sts2/') {
			$handler = __dir__ . '/ctrl/ps/sts2.php';
			require_once $handler;
			$this->app = new SetTs2();
		}
		
	}
	
	/**
	 * @description Маршруты для страницы /p/phd/
	*/
	protected function _apipdf()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		
		if ($baseUrl == '/p/api/newpdftotext') {
			$handler = $sCtrlDir . 'getnewpdftexttask.php';
			require_once $handler;
			$this->app = new GetNewPdfTextTask();
		}
	}
	
	
	/**
	 * @description Маршруты для страницы /p/work18042022/
	*/
	protected function _work_exp18042022()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		
		if ($baseUrl == '/p/work18042022/') {
		    $handler = $sCtrlDir . 'addbuffer.php';
		    require_once $handler;
		    $this->app = new AddBuffer();
		}
	}
	
	
	/**
	 * @description Маршруты для страницы /p/work22122020/
	*/
	protected function _work_exp22122020()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		
		if ($baseUrl == '/p/work22122020') {
			$handler = $sCtrlDir . 'addstat.php';
			require_once $handler;
			$this->app = new AddStat();
		}
	}
	
	/**
	 * @description Маршруты для страницы /p/phd/
	*/
	protected function _phd()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		
		if ($baseUrl == '/p/phd/email.jn/') {
			$handler = $sCtrlDir . 'phdemail.php';
			require_once $handler;
			$this->app = new TrollKillerLogin();
		}
	}
	/**
	 * @description Маршруты для страницы /p/trollkiller/
	*/
	protected function _trollkiller()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		
		if ($baseUrl == '/p/trollkiller/login.jn/') {
			$handler = $sCtrlDir . 'tklogin.php';
			require_once $handler;
			$this->app = new TrollKillerLogin();
		}
		if ($baseUrl == '/p/trollkiller/logout.jn/') {
			$handler = $sCtrlDir . 'tklogout.php';
			require_once $handler;
			$this->app = new TrollKillerLogout();
		}
		
		if ($baseUrl == '/p/trollkiller/checkauth.jn/') {
			$handler = $sCtrlDir . 'tkcheckauth.php';
			require_once $handler;
			$this->app = new TrollKillerCheckAuth();
		}
		
		if ($baseUrl == '/p/trollkiller/savelist.jn/') {
			$handler = $sCtrlDir . 'tksavelist.php';
			require_once $handler;
			$this->app = new TrollKillerSaveList();
		}
		
		if ($baseUrl == '/p/trollkiller/createlist.jn/') {
			$handler = $sCtrlDir . 'tkcreatelist.php';
			require_once $handler;
			$this->app = new TrollKillerCreateList();
		}
		if ($baseUrl == '/p/trollkiller/addrel.jn/') {
			$handler = $sCtrlDir . 'tkaddrel.php';
			require_once $handler;
			$this->app = new TrollKillerAddRelation();
		}
		if ($baseUrl == '/p/trollkiller/delrel.jn/') {
			$handler = $sCtrlDir . 'tkdelrel.php';
			require_once $handler;
			$this->app = new TrollKillerDeleteRelation();
		}
		if ($baseUrl == '/p/trollkiller/search.jn/') {
			$handler = $sCtrlDir . 'tksearch.php';
			require_once $handler;
			$this->app = new TrollKillerSearch();
		}
	}
	/**
	 * @description Маршруты для страницы /p/portfoliocats/
	*/
	protected function _treedemo()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		if ($baseUrl == '/p/treedemo/dcatsave.jn/') {
			$handler = $sCtrlDir . 'dcatsave.php';
			require_once $handler;
			$this->app = new DemoCategoryPost();
		}
		if ($baseUrl == '/p/treedemo/dcatdelte.jn/') {
			$handler = $sCtrlDir . 'dcatremove.php';
			require_once $handler;
			$this->app = new DemoCategoryRemove();
		}
		if ($baseUrl == '/p/treedemo/tree.jn/') {
			$handler = $sCtrlDir . 'dtree.php';
			require_once $handler;
			$this->app = new DemoCategoriesList();
		}
	}
	/**
	 * @description Маршруты для страницы /p/larlog
	*/
	protected function _larincevareciever()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		if ($baseUrl == '/p/larlog/save.jn/') {
			$handler = $sCtrlDir . 'larsave.php';
			require_once $handler;
			$this->app = new LarincevalogPost();
		}
		/*if ($baseUrl == '/p/treedemo/dcatdelte.jn/') {
			$handler = $sCtrlDir . 'dcatremove.php';
			require_once $handler;
			$this->app = new DemoCategoryRemove();
		}
		if ($baseUrl == '/p/treedemo/tree.jn/') {
			$handler = $sCtrlDir . 'dtree.php';
			require_once $handler;
			$this->app = new DemoCategoriesList();
		}*/
	}
	/**
	 * @description Маршруты для страницы /p/portfoliocats/
	*/
	protected function _portfoliocats()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		if ($baseUrl == '/p/portfoliocats/pcsave.jn/') {
			$handler = $sCtrlDir . 'pcsave.php';
			require_once $handler;
			$this->app = new ProudctCategoryPost();
		}
		if ($baseUrl == '/p/portfoliocats/pcdelte.jn/') {
			$handler = $sCtrlDir . 'pcremove.php';
			require_once $handler;
			$this->app = new PortfoliCategoryRemove();
		}
	}
	/**
	 * @description Маршруты для страницы /p/articlecategories/
	*/
	protected function _acategories()
	{
		$baseUrl = $this->_baseUrl;
		if ($baseUrl == '/p/articlecategories/') {
			$this->master = __dir__ . '/master.tpl.php';
			$handler = __dir__ . '/ctrl/acategories/list.php';
			$this->view    = __dir__ . '/view/acategories/acategorieslist.tpl.php';
			require_once $handler;
			$this->app = new ArticleCategoriesEditor();
		}
		if ($baseUrl == '/p/acategories/acatslist.jn/') {
			$handler = __dir__ . '/ctrl/acategories/acatslist.php';
			require_once $handler;
			$this->app = new ArticlesCategories();
		}
		if ($baseUrl == '/p/acategories/category.jn/') {
			$handler = __dir__ . '/ctrl/acategories/category.php';
			require_once $handler;
			$this->app = new ArticlesCategory();
		}
		if ($baseUrl == '/p/acategories/removecategory.jn/') {
			$handler = __dir__ . '/ctrl/acategories/categoryremove.php';
			require_once $handler;
			$this->app = new CategoryRemove();
		}
		if ($baseUrl == '/p/acategories/categorysave.jn/') {
			$handler = __dir__ . '/ctrl/acategories/categorysave.php';
			require_once $handler;
			$this->app = new ArticleCategoryPost();
		}
	}
	/**
	 * @description Маршруты для страницы /p/stat/
	*/
	protected function _stat()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		if ($baseUrl == '/p/stat/c.jn/') {
			//$this->master = __dir__ . '/master.tpl.php';
			$handler = $sCtrlDir . 'statcount.php';
			//$this->view    = __dir__ . '/view/portfolio.tpl.php';
			require_once $handler;
			$this->app = new StatCount();
		}
	}
	/**
	 * @description Маршруты для страницы /p/portfolio/
	*/
	protected function _portfolio()
	{
		$s = str_replace('_', '', __FUNCTION__);
		$sCtrlDir = __dir__ . '/ctrl/' . $s . '/';
		
		$baseUrl = $this->_baseUrl;
		
		if ($baseUrl == '/p/portfoliovdt/') {
			$this->master = __dir__ . '/master.tpl.php';
			$handler = $sCtrlDir . 'list.php';
			$this->view    = __dir__ . '/view/portfolio.tpl.php';
			require_once $handler;
			$this->app = new ProductsEditor/*PortfolioListVdt*/();
		}
		
		if ($baseUrl == '/p/portfolio/') {
			$this->master = __dir__ . '/master.tpl.php';
			$handler = $sCtrlDir . 'list.php'; //from list
			$this->view    = __dir__ . '/view/portfolio.tpl.php';
			require_once $handler;
			$this->app = new ProductsEditor();
		}
		if ($baseUrl == '/p/portfolio/list.jn/') {
			$handler = $sCtrlDir . 'plist.php';
			require_once $handler;
			$this->app = new PortfolioList();
		}
		if ($baseUrl == '/p/portfolio/product.jn/') {
			$handler = $sCtrlDir . 'product.php';
			require_once $handler;
			$this->app = new Product();
		}
		if ($baseUrl == '/p/portfolio/removeproduct.jn/') {
			$handler = $sCtrlDir . 'productremove.php';
			require_once $handler;
			$this->app = new ProductRemove();
		}
		if ($baseUrl == '/p/portfolio/psave.jn/') {
			$handler = $sCtrlDir . 'psave.php';
			require_once $handler;
			$this->app = new ProudctPost();
		}
		if ($baseUrl == '/p/portfolio/productupload.jn/') {
			$handler = $sCtrlDir . 'productupload.php';
			require_once $handler;
			$this->app = new PortfolioFileUpload();
		}
		if ($baseUrl == '/p/portfolio/sha256remove.jn/') {
			$handler = $sCtrlDir . 'sha256remove.php';
			require_once $handler;
			$this->app = new ProductSha256FileRemove();
		}
		if ($baseUrl == '/p/portfolio/reorder.jn/') {
			$handler = $sCtrlDir . 'reorder.php';
			require_once $handler;
			$this->app = new PortfolioReorder();
		}
		if ($baseUrl == '/p/portfoio/move.jn/') {
			$handler = __dir__ . '/ctrl/portfolio/portfoliomovetopage.php';
			require_once $handler;
			$this->app = new PortfolioMoveToPage();
		}
	}
	/**
	 * @description Маршруты для страницы /p/
	*/
	protected function _p()
	{
		$baseUrl = $this->_baseUrl;
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
		if ($baseUrl == '/p/article.jn/') {
			$handler = __dir__ . '/ctrl/article.php';
			require_once $handler;
			$this->app = new Article();
		}
		if ($baseUrl == '/p/articlesave.jn/') {
			$handler = __dir__ . '/ctrl/articlesave.php';
			require_once $handler;
			$this->app = new ArticlePost();
		}
		
		if ($baseUrl == '/p/removearticle.jn/') {
			$handler = __dir__ . '/ctrl/removearticle.php';
			require_once $handler;
			$this->app = new RemoveArticle();
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
		if ($baseUrl == '/p/articlesreorder.jn/') {
			$handler = __dir__ . '/ctrl/articlesreorder.php';
			require_once $handler;
			$this->app = new ArticlesReorder();
		}
		if ($baseUrl == '/p/articles/move.jn/') {
			$handler = __dir__ . '/ctrl/articlesmovetopage.php';
			require_once $handler;
			$this->app = new ArticlesMoveToPage();
		}
	}
}
$route = new Route();
$app = $route->app;
