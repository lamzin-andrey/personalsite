<?php
namespace App\Twig;

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

	public function __construct(ContainerInterface $container,  TranslatorInterface $t)
	{
		$this->container = $container;
		$this->translator = $t;
		$this->_oRequest = $container->get('request_stack')->getCurrentRequest();
		$oServer = $this->_oRequest->server ?? null;

		if ($oServer) {
			$url = explode('?', $oServer->get('REQUEST_URI'));
			$this->_baseUrl = $url[0];
		}
	}

	public function getFilters() : array
	{
		return [
			new TwigFilter('get_loginform_input_css', array($this, 'getLoginformInputCss')),
			new TwigFilter('rouble', array($this, 'roubleFilter')),
			new TwigFilter('translite_url', array($this, 'transliteUrl')),
			new TwigFilter('draw_menu_item', array($this, 'drawMenuItem')),
			new TwigFilter('topbar_new_is_read', array($this, 'topbarNewIsRead')),
			new TwigFilter('topbar_message_is_read', array($this, 'topbarMessageIsRead')),
			new TwigFilter('get_auth_user_display_name', array($this, 'getAuthUserDisplayName')),
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
	 * @return string
	*/
	public function drawMenuItem(string $href, string $text, string $translationDomain = 'sidebar') : string
	{
		return '<a class="collapse-item'. $this->_activeMenuItem($href) . '" href="' . $href . '">' . $this->translator->trans($text, [], $translationDomain) . '</a>';
	}
	/**
	 * @description Вернет строку active если запрошенный url совпадает с запрошеным (для пунктов меню сайдбара)
	 * @return string
	*/
	private function _activeMenuItem(string $href) : string
	{
		$href = preg_replace("#/$#", '', $href);
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
}