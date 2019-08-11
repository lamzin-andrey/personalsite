<?php
/**
 * @class AdminAuth общая логика для всех страниц админки, на которых админ авторизован
*/
class AdminAuth extends BaseApp {	
	
	public $pageHeading = 'Blank Page';
	
	public $uid = 0;
	
	//Переменные связанные с алертом новостей
	
	/** @property  string $countUnreadNews количество новостей, которые пользователь не прочёл. */
	public $sCountUnreadNews = '';
	
	/** @property  array $aNews массив новостей - только те, которые отображаются в шапке при клике на колокольчике*/
	public $aNews = [];
	
	/** @property int  $totalNewsCount общее количество новостей (прочитанных и нет) */
	public $totalNewsCount = 0;
	
	/** @property int $displayInTopListNewsCount количество новостей (прочитанных и нет) отображаемых в списке открывающимся при нажатии на колокольчик в шапке сверху*/
	public $displayInTopListNewsCount = 0;
	
	// / Переменные связанные с алертом новостей
	
	
	
	//Переменные связанные с алертом непрочтённых сообщений
	
	/** @property  string $countUnreadMessages количество сообщений, которые пользователь не прочёл. */
	public $sCountUnreadMessages = '';
	
	/** @property  array $aMessages массив сообщений - только те, которые отображаются в шапке при клике на конверте в шапке сверху*/
	public $aMessages = [];
	
	/** @property int  $totalMessagesCount общее количество сообщений (прочитанных и нет) */
	public $totalMessagesCount = 0;
	
	/** @property int $displayInTopListNewsCount количество сообщений (прочитанных и нет) отображаемых в списке открывающимся при нажатии на конверте в шапке сверху*/
	public $displayInTopListMessagesCount = 0;
	
	// / Переменные связанные с алертом непрочтённых сообщений
	
	
	public function __construct()
	{
		$this->uid = $uid = Auth::getUid();
		if (!$uid) {
			header('location: /p/signin/');
			die;
		}
		if (!Auth::isAdmin()) {
			utils_302('/');
		}
		$this->table = 'ausers';
		parent::__construct();
		
		$url = explode('?', $_SERVER['REQUEST_URI']);
		$this->_baseUrl = $url[0];
		
		//get alerts
	}
	
	/**
	 * @description Вернет строку active если запрошенный url совпадает с запрошеным (для пунктов меню сайдбара)
	 * @return string
	*/
	protected function _activeMenuItem(string $href) : string
	{
		if ($this->_baseUrl == $href) {
			return ' active';
		}
		return '';
	}
	/**
	 * @description Вернёт html строки меню левого сайдбара
	 * @return string
	*/
	public function drawMenuItem(string $href, string $text) : string
	{
		return '<a class="collapse-item'. $this->_activeMenuItem($href) . '" href="' . $href . '">' . l($text) . '</a>';
	}
}
