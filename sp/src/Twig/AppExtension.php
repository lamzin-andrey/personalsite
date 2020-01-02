<?php
namespace App\Twig;

use \Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\TwigFilter;

class AppExtension extends \Twig\Extension\AbstractExtension
{

	public function __construct(ContainerInterface $container,  TranslatorInterface $t)
	{
		$this->container = $container;
		$this->translator = $t;
	}

	public function getFilters() : array
	{
		return [
			new TwigFilter('get_loginform_input_css', array($this, 'getLoginformInputCss')),
			new TwigFilter('rouble', array($this, 'roubleFilter')),
			new TwigFilter('translite_url', array($this, 'transliteUrl')),
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
}
