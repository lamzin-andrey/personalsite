<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
