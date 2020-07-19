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


		if (!$response) {
			//Fix for android browser 2 - Симфони почему то всегда возвращает ему Content-type: text/xml

			//Например http://mh.loc/sp/public/login GET (если захочется воспроизвеяти баг)
			//Пример заголовков android browser 2.3.6 (если захочется воспроизвеяти баг):

			//Host: andryuxa.ru
			//User-Agent: Mozilla/5.0 (Linux; U; Android 2.3.6; ru-ru; GT-S5363 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1
			//Accept: application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5
			//Accept-Language: ru-RU, en-US
			//Accept-Encoding: gzip
			//Accept-Charset: utf-8, iso-8859-1, utf-16, *;q=0.7
			//Pragma: no-cache
			//Cache-Control: no-cache
			//Upgrade-Insecure-Requests: 1
			//Connection: keep-alive
			//Cookie: PHPSESSID=c5fb48c5d803777cc47c8a1a9af5c56b; REMEMBERME=QXBwXEVudGl0eVxBdXNlcnM6YkdrPToxNjE5MzM4Mzk3OmYzNTU4MjVlYTI5ZGIzYWQ1ZTgxNjdhZThmZGUzYTlmYzk0MmJkOTZiODM3Y2M3ZmI1MWIzYThiYTM3NGE5NTY%3D; s=2990ef8af82effdfef2d298f84680ce537dea86a; sv=a2


			$sUa = $oRequest->server->get('HTTP_USER_AGENT');
			if ($sUa != 'Mozilla/5.0 (Linux; U; Android 2.3.6; ru-ru; GT-S5363 Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1') {
				return parent::render($view, $parameters, $response);
			}
			$response = new Response();
			header('Content-Type: text/html');
			$response->headers->set('Content-Type', 'text/html; charset=UTF-8');
		}
		return parent::render($view, $parameters, $response);
	}
}
