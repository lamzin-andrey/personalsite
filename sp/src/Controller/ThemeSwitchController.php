<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeSwitchController extends AppBaseController
{
    /**
	 * Здесь пока просто проверяем, установлена ли кука sv, если нет, ставим a2
	 *  Если да и равна a2 ставим c70
	 *  По умолчанию ставим c70
     * @Route("/theme/switch", name="theme_switch")
    */
    public function index(Request $oRequest) : Response
    {
		$sThemeCookieValue = $oRequest->cookies->get('sv', 'c70');
		if ($sThemeCookieValue == 'c70') {
			$sThemeCookieValue = 'a2';
		} else {
			$sThemeCookieValue = 'c70';
		}
		$oCookie = Cookie::create('sv', $sThemeCookieValue, time() + 365 * 24 * 3600,
			'/', $oRequest->server->get('HTTP_HOST'), false);
		$oResponse = $this->redirectDefault($oRequest);
		$oResponse->headers->setCookie($oCookie);
		return $oResponse;
    }
}
