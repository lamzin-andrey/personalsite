<?php

namespace App\Controller;

use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class TasksCronController extends AbstractController
{
	/** @property AppService $_oAppService */
	private $_oAppService;
    /**
     * @Route("/tasks.json", name="tasks_list")
    */
    public function index(Request $oRequest, AppService $oAppService)
    {
    	$this->_oAppService = $oAppService;
		$aResult = [
			'recordsTotal' => 0,
			'recordsFiltered' => 0,
			'draw' => $oRequest->get('draw'),
			'data' => []
		];
        return $this->_json($aResult);
    }

    /**
     * @Route("/taglist.json", name="task_tags")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return Response
    */
    public function tags(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
    {
		$this->_oAppService = $oAppService;
		$aResult = [
			'recordsTotal' => 0,
			'recordsFiltered' => 0,
			'draw' => $oRequest->get('draw'),
			'data' => []
		];
		return $this->_json($aResult);
    }

    private function _json(array $aData)
	{
    	return $this->_oAppService->json($aData);
	}
}
