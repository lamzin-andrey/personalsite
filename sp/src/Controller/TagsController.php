<?php

namespace App\Controller;

use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TagsController extends AbstractController
{
	/** @property AppService $_oAppService */
	private $_oAppService = null;

    /**
     * @Route("/tags.json", name="tags")
    */
    public function index(AppService $oAppService, TranslatorInterface $t)
    {
    	$this->_oAppService = $oAppService;
    	$aData = [
    		'tags' => $this->_getTags(),
			'status' => 'ok'
		];
        return $this->_json($aData);
    }
    /**
     * @return array [id => name]
    */
    private function _getTags() : array
    {
		$oRepository = $this->_oAppService->repository('App:CrnTags');
		$aRaw = $oRepository->findAll();
		$a = [];
		foreach ($aRaw as $oTag) {
			$a[$oTag->getId()] = $oTag->getName();
		}
		return $a;
    }
    /**
     *
     * @param array $aData
     * @return Response
    */
    private function _json(array $aData) : Response
    {
    	return $this->_oAppService->json($aData);
    }
}
