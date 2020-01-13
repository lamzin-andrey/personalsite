<?php

namespace App\Controller;

use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhdController extends AbstractController
{
	/** @property int _examplesPerPage Количество работ на одной "странице" */
	private $_examplesPerPage = 3;

    /**
	 * Примеры работ ajax. Возвращает три последних работы, разрешённых к показу.
     * @Route("/phdexamples", name="phdexamples")
     */
    public function getExamplesAction(Request $oRequest)
    {
		$nPage = intval($oRequest->get('page'));
		$nOffset = ($nPage - 1) * $this->_examplesPerPage;
		$aExamplesList = [];
    	$oRepository = $this->getDoctrine()->getRepository('App:PhdMessages');
    	$oCriteria = Criteria::create();
    	$e = Criteria::expr();

    	$oCriteria->where(
    		$e->eq('isPublish', 1),
			$e->eq('isPayed', 1),
		)
			->setMaxResults($this->_examplesPerPage)
			->setFirstResult($nOffset)
			->orderBy(['id' => Criteria::DESC]);
		$aExamplesData = $oRepository->matching($oCriteria)->toArray();
		foreach ($aExamplesData as $oItem) {
			$aItem = [
				'result_link' => $oItem->getResultLink(),
				'psd_link' => $oItem->getPsdLink(),
				'preview_link' => $oItem->getPreviewLink()
			];
			$aExamplesList[] = $aItem;
		}

		$oCriteria = Criteria::create();
		$oCriteria->where(
			$e->eq('isPublish', 1),
			$e->eq('isPayed', 1),
		);
		$nTotal = $oRepository->matching($oCriteria)->count();

        return $this->_json([
        	'list' => $aExamplesList,
			'total' => $nTotal
		]);
    }

    private function _json($oData)
	{
    	$oResponse = new Response( json_encode($oData) );
    	$oResponse->headers->set("Content-Type", "application/json");
    	return $oResponse;
	}
}
