<?php

namespace App\Controller;

use App\Entity\PhdMessages;
use App\Entity\PhdUsers;
use App\Form\PsdUploadFormType;
use App\Service\AppService;
use App\Service\FileUploaderService;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhdController extends AbstractController
{
	/**
	 * Проверяет, установлена ли кука app.phdusercookiename
	 * @Route("/phdsayhello", name="phdsayhello")
	 */
	public function sayhello(Request $oRequest)
	{
		$oEm = $this->getDoctrine()->getManager();
		$sCookieValue = md5($oRequest->server->get('REMOTE_ADDR')) . md5(microtime() . $oRequest->server->get('HTTP_USER_AGENT'));
		while (true) {
			$oPhdUser = $this->_findByHash($sCookieValue);
			//Если такого нет, выходим из цикла
			if (!$oPhdUser) {
				break;
			}
			$sCookieValue = md5($sCookieValue) . md5( microtime(true) );
		}
		$oPhdUser = new PhdUsers();
		$oPhdUser->setHash($sCookieValue);
		$oPhdUser->setAuthHash( sha1($sCookieValue) );
		$oEm->persist($oPhdUser);
		$oEm->flush();
		$aData = [];
		$oResponse = $this->_json($aData);
		$sCookieName = $this->getParameter('app.phdusercookiename');
		/** @var \Symfony\Component\HttpFoundation\Cookie $oCookie */
		$oCookie = Cookie::create($sCookieName, $sCookieValue, time() + 31536000);
		$oResponse->headers->setCookie($oCookie);
		return $oResponse;
	}
	/**
	 * Проверяет, установлена ли кука app.phdusercookiename
	 * @Route("/phdcheckin", name="phdcheckin")
	 */
	public function checkIn(Request $oRequest, FileUploaderService $oFileUploaderService, AppService $oAppService)
	{
		$oPhdUser = null;
		$sCookieName = $this->getParameter('app.phdusercookiename');
		$sCookieValue = $oRequest->cookies->get($sCookieName, '');
		if ($sCookieValue) {
			$oPhdUser = $this->_findByHash($sCookieValue);
		}

		$this->_subdir = 'd/' . date('Y/m');
		$this->_oMessage = new PhdMessages();

		$oForm = $this->createForm(get_class(new PsdUploadFormType()), $this->_oMessage, [
			'app_service' => $oAppService,
			'uploaddir' => $this->_subdir
		]);

		$aData = [
			'formToken' => $oForm->createView()->children['_token']->vars['value']
		];


		if ($oPhdUser) {
			$oSession = $oRequest->getSession();
			$oSession->set('phd_user', $oPhdUser);
			$aData['cookieFound'] = 1;
		}
		return $this->_json($aData);
	}

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

    private function _json($aData)
	{
		if (!isset($aData['status'])) {
			$aData['status'] = 'ok';
		}
    	$oResponse = new Response( json_encode($aData) );
    	$oResponse->headers->set("Content-Type", "application/json");
    	return $oResponse;
	}

	private function _findByHash(string $sCookieValue)
	{
		$oRepository = $this->getDoctrine()->getRepository('App:PhdUsers');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		$oCriteria->where( $e->eq('hash', $sCookieValue));
		$oCriteria->setMaxResults(1);
		$oPhdUser = $oRepository->matching($oCriteria)->get(0);
		return $oPhdUser;
	}

}
