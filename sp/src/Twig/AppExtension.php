<?php
namespace App\Twig;

use App\Entity\CrnTasks;
use App\Entity\CrnTaskTags;
use App\Service\AppService;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\TwigFilter;

class AppExtension extends \Twig\Extension\AbstractExtension
{

	/** @property Request _oRequest */
	private $_oRequest = null;

	/** @property string _baseUrl */
	private $_baseUrl = '';

	public function __construct(ContainerInterface $container,  TranslatorInterface $t, AppService $oAppService)
	{
		$this->container = $container;
		$this->translator = $t;
		$this->_oRequest = $container->get('request_stack')->getCurrentRequest();
		$this->_oRouter = $container->get('router');
		$oServer = $this->_oRequest->server ?? null;
		$this->_oAppService = $oAppService;

		if ($oServer) {
			$url = explode('?', $oServer->get('REQUEST_URI'));
			$this->_baseUrl = $url[0];
		}
	}

	public function getFilters() : array
	{
		return [
			new TwigFilter('render_interval', array($this, 'renderInterval')),
			new TwigFilter('get_loginform_input_css', array($this, 'getLoginformInputCss')),
			new TwigFilter('rouble', array($this, 'roubleFilter')),
			new TwigFilter('translite_url', array($this, 'transliteUrl')),
			new TwigFilter('draw_menu_item', array($this, 'drawMenuItem')),
			new TwigFilter('topbar_new_is_read', array($this, 'topbarNewIsRead')),
			new TwigFilter('topbar_message_is_read', array($this, 'topbarMessageIsRead')),
			new TwigFilter('get_auth_user_display_name', array($this, 'getAuthUserDisplayName')),
			new TwigFilter('user_avatar', array($this, 'getAuthUserAvatarImgSrc')),
			new TwigFilter('sidebar_heading', array($this, 'getSidebarHeading')),
			new TwigFilter('show_site_version_link', array($this, 'showSiteVersionLink')),
			new TwigFilter('get_uid', array($this, 'getUid'))
		];
	}
	/**
	 * Устанавливает значение атрибута class для тега input форм логиа и регистрации в теме оформления SBAdmin 2
	 * @param Symfony\Component\Form\FormView $o
	 * @param string $sBaseCss = 'form-control form-control-user'
	 * @return string
	*/
	public function getLoginformInputCss($o, string $sBaseCss = 'form-control form-control-user') : string
	{
		if ($o->vars['valid']) {
			return $sBaseCss;
		}
		return  $sBaseCss . ' is-invalid';
	}
	/**
	 * @param float $v
	 * @return string
	 */
	public function roubleFilter(float $v) : string
	{
		$sUnit = $this->translator->trans('Roubles');

		if (intval($v) == 0 || !$v) {
			$v = 1;
		}
		$v = str_replace('.', ',', $v);
		$a = explode(',', $v);
		$s = $a[0];
		$q = [];
		for ($i = strlen($s) - 1, $j = 1; $i > -1; $i--, $j++) {
			$q[] = $s[$i];
			if ($j % 3 == 0) $q[] = ' ';
		}
		$a[0] = join('', array_reverse($q));
		$sZero = ($a[1] ?? '');
		$sRouble = ' ' . $sUnit . ' ';

		if ($sZero == '00') {
			return $a[0] . $sRouble;
		}
		$v = join('', $a);
		return $v . $sRouble;
	}
	/**
	 * TODO
	 * @param \App\Entity\Main $str
	 * @return string
	 */
	public function transliteUrl(string $str) : string
	{
		return '';
	}
	/**
	 * Изменяет слово "час" в зависмости от количества
	 * @param  int $n
	 * @return string
	 */
	public function pluralizeHours(int $n) : string
	{
		return ($n . ' ' . RusLexicon::getMeasureWordMorph($n, 'час', 'часа', 'часов') );
	}

	public function getUid()
	{
		//TODO return $this->_oViewDataService->getUid();
		return 0;
	}
	/**
	 * Вернёт html строки меню левого сайдбара
	 * @param string $href
	 * @param string $text
	 * @param string $translationDomain = 'sidebar'
	 * @param array $access = [2] коды допустимых уровней доступа
	 * @return string
	*/
	public function drawMenuItem(string $href, string $text, string $translationDomain = 'sidebar', array $access = [2]) : string
	{
		$s =  '<a class="collapse-item'. $this->_activeMenuItem($href) . '" href="' . $href . '">' . $this->translator->trans($text, [], $translationDomain) . '</a>';
		$oUser = $this->container->get('security.token_storage')->getToken()->getUser();
		if (in_array($oUser->getRole(), $access)) {
			return $s;
		}
		return '';
	}
	/**
	 * @description Вернет строку active если запрошенный url совпадает с запрошеным (для пунктов меню сайдбара)
	 * @return string
	*/
	private function _activeMenuItem(string $href) : string
	{
		$href = preg_replace("#/$#", '', $href);
		$this->_baseUrl = preg_replace("#/$#", '', $this->_baseUrl);

		if ($this->_baseUrl == $href) {
			return ' active';
		}
		return '';
	}
	/**
	 * TODO
	 * Вернёт true если новость не прочитана
	 * @param Entity $oNew
	 * @return string
	*/
	public function topbarNewIsRead($oNew) : string
	{
		return ($oNew->getIsRead() ? 'small text-gray-500' : 'font-weight-bold');
	}
	/**
	 * Вернёт отображаемое имя польователя
	 * @param ?Ausers $oUser
	 * @return string
	*/
	public function getAuthUserDisplayName($oUser) : string
	{
		if (!$oUser) {
			return '';
		}
		$sName = trim($oUser->getName());
		$surname = trim($oUser->getSurname());
		return trim( $sName . ' ' . $surname );
	}
	/**
	 * TODO
	 * Вернёт true если сообщение не прочитано
	 * @param Entity $oMessage
	 * @return string
	 */
	public function topbarMessageIsRead($oMessage) : string
	{
		return $oMessage->isRead ? '' : 'font-weight-bold';
	}
	/**
	 * @param array $aIntervalInfo
	 * @param StdClass $oTree
	 * @param ?CrnTasks $oTask
	 * @return string
	*/
	public function renderInterval(array $aIntervalInfo, $oTree, $oTask = null) : string
	{
		/** @var \DateTime $oStartDate */
		$oStartDate = ($aIntervalInfo['startDatetime'] ?? null);
		/** @var \DateTime  $oEndDate*/
		$oEndDate = ($aIntervalInfo['endDatetime'] ?? null);
		$start = '';
		if (!$oStartDate) {
			$start = '*';
		} else {
			$start = $oStartDate->format('H i');
		}
		$sEnd = '';
		if (!$oEndDate) {
			$sEnd = '*';
		} else {
			$sEnd = $oEndDate->format('H i');
		}
		$sTaskInfo = '';
		if ($oTree) {
			$oTaskInfo = \TreeAlgorithms::findById($oTree, ($aIntervalInfo['taskId'] ?? 0) );
			$sTaskInfo = $oTaskInfo->codename . ' ' . $oTaskInfo->name;
		} else if($oTask) {
			$sTaskInfo = $oTask->getCodename() . ' ' . $oTask->getName();
		}

		return $start . ' - ' . $sEnd . ' ' . $sTaskInfo;
	}
	/**
	 *
	 * @return  string
	*/
	public function getAuthUserAvatarImgSrc() : string
	{
		return $this->_oAppService->getUserAvatarImageSrc();
	}
	/**
	 * Возвращает загловок для сайдбара в зависимотси от роли пользователя
	 * @return  srting
	*/
	public function getSidebarHeading() : string
	{
		$oUser = $this->_oAppService->getAuthUser();
		if ($oUser->getRole() == 2) {
			return 'ADMIN PANEL';
		}
		return 'FRIEND CRON';
	}

	/**
	 * Возвращает html ссылки на страницу смены версии сайта (chrome 70+ или android browser 2.3.3,
     * в зависимости от отсутствия соответсвующей куки )
	 * @param $nZero (просто потому что это фильтр twig)
	 * @return string html ссылки ({{- -}} проверить заодно)
	*/
	public function showSiteVersionLink() : string
    {
        /** @var Request $oRequest     */
	    $oRequest = $this->_oRequest;
        $sVersion = $oRequest->cookies->get('sv', 'c70');
        $allowVersions = ['c70', 'a2'];
        if (!in_array($sVersion, $allowVersions)) {
            $sVersion = 'c70';
        }
		$sPath = $this->_oRouter->generate('theme_switch');

        if ($sVersion == 'c70') {
			return '<a href="' . $sPath . '" class="user-link-with-icon">
	<img width="32" height="32" src="/i/a2-32.png"> ' . $this->translator->trans('Version for Android Browser 2+') . ' 
</a>';
        }

		if ($sVersion == 'a2') {
			return '<a href="' . $sPath . '" class="">
	<i class="fab fa-chrome"></i> ' . $this->translator->trans('Version for Crome 70+') . ' 
</a>';
		}

	}

}