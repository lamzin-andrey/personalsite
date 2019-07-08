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
			$this->master = __dir__ . '/index.tpl.php';
			$handler = __dir__ . '/ctrl/main.php';
			$this->view    = __dir__ . '/view/home.tpl.php';
			require_once $handler;
			$this->app = new Chat();
		}
		if ($baseUrl == '/p/signin/') {
			$handler = __dir__ . '/ctrl/signin.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/signinform.tpl.php';
			$this->app = new Signin();
		}
		if ($baseUrl == '/p/signin.jn/') {
			$handler = __dir__ . '/ctrl/signin.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/signinform.tpl.php';
			$this->app = new Signin();
		}
		if ($baseUrl == '/signup') {
			$handler = __dir__ . '/ctrl/signup.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/signupform.tpl.php';
			$this->app = new Signup();
		}
		if ($baseUrl == '/signup.jn') {
			$handler = __dir__ . '/ctrl/signup.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/signupform.tpl.php';
			$this->app = new Signup();
		}
		if ($baseUrl == '/reset') {
			$handler = __dir__ . '/ctrl/reset.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/resetform.tpl.php';
			$this->app = new Reset();
		}
		if ($baseUrl == '/reset.jn') {
			$handler = __dir__ . '/ctrl/reset.php';
			require_once $handler;
			$this->view    = __dir__ . '/view/resetform.tpl.php';
			$this->app = new Reset();
		}
	}
}
$route = new Route();
$app = $route->app;
