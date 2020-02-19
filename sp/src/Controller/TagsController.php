<?php

namespace App\Controller;

use App\Service\AppService;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $oRequest, AppService $oAppService, TranslatorInterface $t)
    {
    	$this->_oAppService = $oAppService;
    	$aData = [
    		'tags' => $this->_getTags($oRequest->get('s')),
			'status' => 'ok'
		];
        return $this->_json($aData);
    }
    /**
	 * @param string $s Подстрока для поискаs
     * @return array [id => name]
    */
    private function _getTags(string $s) : array
    {
		$oRepository = $this->_oAppService->repository('App:CrnTags');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		$oCriteria->where( $e->contains('name', $s) );
		$aRaw = $oRepository->matching($oCriteria)->toArray();
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
