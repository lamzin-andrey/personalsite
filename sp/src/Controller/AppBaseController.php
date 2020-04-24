<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AppService;
class AppBaseController extends AbstractController
{
	/** @property \App\Service\AppService $_oAppService */
	protected $_oAppService;


	protected function _json(array $aData)
	{
		return $this->_oAppService->json($aData);
	}
	/**
	 *
	 * @return bool true если пользователь авторизован
	 */
	protected function _accessControl() : bool
	{
		if (!$this->getUser()) {
			return false;
		}
		return true;
	}

	protected function getUserId() : int
	{
		return $this->getUser()->getId();
	}
	/**
	 * Если есть реферер, делает редирект на реферер, иначе делает редирект на домашнюю страницу
	 * @param Request $oRequest
	 * @return Response
	*/
	protected function redirectDefault(Request $oRequest) : Response
	{
		$sReferer = $oRequest->server->get('HTTP_REFERER', '');
		if ($sReferer) {
			return $this->redirect($sReferer);
		}
		return $this->redirectToRoute('home');
	}
	/**
	 * Добавляет в строку с путём к шаблону префикс каталога, в котором лежит тема оформления, например  'a2'
	 *  если установлена соответсвующая кука
	*/
	protected function render(string $view, array $parameters = [], Response $response = null) : Response
	{
		$oRequest = $this->_oAppService->request();
		$sThemeVersion = $oRequest->cookies->get('sv');
		if ($sThemeVersion == 'a2') {
			$view = 'themes/a2/' . $view;
		}
		return parent::render($view, $parameters, $response);
	}
}
