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
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class PhdController extends AbstractController
{
	/** @property string $_subdir подкаталог в который загружаются файлы при аплоаде */
	private  $_subdir;

	/** @property App\Entit\PhdMessage $_oMessage */
	private	$_oMessage;

	/** @property int _examplesPerPage Количество работ на одной "странице" */
	private $_examplesPerPage = 3;

	/**
	 * Проверяет, установлена ли кука app.phdusercookiename
	 * @Route("/phdsayhello.json", name="phdsayhello")
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
	 * @Route("/phdsaveemail.json", name="phdsaveemail")
	*/
	public function phdsaveemail(Request $oRequest, AppService $oAppService, TranslatorInterface $t)
	{
		$oPhdUser = $this->_getAuthPhdUser($oRequest);
		if (!$oPhdUser) {
			return $this->_json([
				'status' => 'error',
				'msg' => $t->trans('Unauth user')
			]);
		}
		$aData = [];
		$sEmail = $oRequest->get('e');
		$nIsChoosed = intval($oRequest->get('choosed'));
		if ($oAppService->isValidEmail($sEmail)) {
			$oPhdUser->setEmail($sEmail);
			$oEm = $this->getDoctrine()->getManager();
			if ($nIsChoosed) {
				$oPhdMessage = $this->_getPhdMessage($oRequest, $oPhdUser);
				$oPhdMessage->setIsEmailUser(true);
				$oEm->persist($oPhdMessage);
			}
			$oEm->persist($oPhdUser);
			$oEm->flush();
			$aData['id'] = $oPhdUser->getId();
		} else {
			$aData['status'] = 'error';
			$aData['msg'] = $t->trans('Invalid email');
		}
		return $this->_json($aData);
	}
	/**
	 * @Route("/phdgetstate.json", name="phdgetstate")
	*/
	public function phdstate(Request $oRequest, AppService $oAppService, TranslatorInterface $t)
	{
		$oPhdUser = $this->_getAuthPhdUser($oRequest);
		if (!$oPhdUser) {
			return $this->_json([
				'status' => 'error',
				'msg' => $t->trans('Unauth user')
			]);
		}
		$oPhdMessage = $this->_getPhdMessage($oRequest, $oPhdUser);
		if (!$oPhdMessage) {
			return $this->_json([
				'status' => 'error',
				'msg' => $t->trans('Unauth user')
			]);
		}
		return $this->_json([
			'st' => $oPhdMessage->getState()
		]);
	}
	/**
	 * Проверяет, установлена ли кука app.phdusercookiename
	 * @Route("/phdcheckin.json", name="phdcheckin")
	 */
	public function checkIn(Request $oRequest, FileUploaderService $oFileUploaderService, AppService $oAppService)
	{
		$oPhdUser = $this->_getAuthPhdUser($oRequest);

		$this->_subdir = 'd/' . date('Y/m');
		$this->_oMessage = new PhdMessages();

		$oForm = $this->createForm(get_class(new PsdUploadFormType()), null, [
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
	/**
	 * Загрузка файлов
	 * @Route("/phdpsdupload.json", name="phdpsdupload")
	*/
	public function psdUpload(Request $oRequest, FileUploaderService $oFileUploaderService, AppService $oAppService)
	{
		if ($oRequest->getMethod() == 'POST') {
			$aData =[];
			$this->_subdir = 'd/' . date('Y/m');
			$oForm = $this->createForm(get_class(new PsdUploadFormType()), null, [
				'app_service' => $oAppService,
				'uploaddir' => $this->_subdir
			]);
			//$oForm->handleRequest($oRequest);
			$oForm->submit([
				'_token' => $oRequest->get('psd_up_form')['_token'],
				'psdfileFileImmediately' => $oRequest->files->get('psd_up_form')['psdfileFileImmediately']
			]);
			if ($oForm->isSubmitted() && $oForm->isValid()) {
				//save file
				$oFile = $oForm['psdfileFileImmediately']->getData();
				if ($oFile) {
					$sFileName = $oFileUploaderService->upload($oFile);
					$s = '/' . $this->_subdir . '/' . $sFileName;
					$this->_oMessage = new PhdMessages();
					$this->_oMessage->setPsdLink($s);
					$oSession = $oRequest->getSession();
					$oPhdUser = $oSession->get('phd_user');
					$this->_oMessage->setUid($oPhdUser->getId());
					$aData['path'] = $s;
					$oEm = $this->getDoctrine()->getManager();
					$oEm->persist($this->_oMessage);
					$oEm->flush();
				} else {
					$aData['error'] = 'No file!';
				}
				// ...
			} else {
				$aData['errors'] = $oAppService->getFormErrorsAsArray($oForm);
				if (count($aData['errors'])) {
					$aData['status'] = 'error';
				}
				$aData['error_text'] = 'No submit or invalid!';
			}
			return $this->_json($aData);
		} else {
			return $this->_json(['nopost' => 1]);
		}
	}

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
	/**
	 * @return PhdUsers or null
	*/
	private function _getAuthPhdUser(Request $oRequest)
	{
		$oPhdUser = null;
		$sCookieName = $this->getParameter('app.phdusercookiename');
		$sCookieValue = $oRequest->cookies->get($sCookieName, '');
		if ($sCookieValue) {
			$oPhdUser = $this->_findByHash($sCookieValue);
		}
		return $oPhdUser;
	}

	/**
	 * Вернёт последнюю загруженную пользователем работу
	 * @param PhdUsers $oPhdUser = null
	 * @return PhdMessages or null
	*/
	private function _getPhdMessage($oRequest, $oPhdUser = null)
	{
		if (!$oPhdUser) {
			$oPhdUser = $this->_getAuthPhdUser($oRequest);
		}
		if (!$oPhdUser) {
			return null;
		}
		$oRepository = $this->getDoctrine()->getRepository('App:PhdMessages');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		$oCriteria->where($e->eq('uid', $oPhdUser->getId()))
			->orderBy(['id' => Criteria::DESC])
			->setMaxResults(1);
		$oPhdMessage = $oRepository->matching($oCriteria)->get(0);
		return $oPhdMessage;
	}
}
