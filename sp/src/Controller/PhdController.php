<?php

namespace App\Controller;

use App\Entity\PhdMessages;
use App\Entity\PhdUsers;
use App\Form\PsdUploadFormType;
use App\Service\AppService;
use App\Service\PayService;
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
	/** @const Полная сумма за конвертацию */
	const SUM_FULL = 100;

	/** @const Полная сумма за конвертацию, по которой определяется, какой вариант выбрал пользователь (со скидкой или без) */
	const SUM_FULL_NOMINAL = 100;

	/** @const Сумма за конвертацию со скидкой */
	const SUM_DISCOUNT = 50;

	/** @property string $_subdir подкаталог в который загружаются файлы при аплоаде */
	private  $_subdir;

	/** @property App\Entit\PhdMessage $_oMessage */
	private	$_oMessage;

	/** @property int _examplesPerPage Количество работ на одной "странице" */
	private $_examplesPerPage = 3;

	/**
	 * @Route("/phducheck", name="phducheck")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @return Response
	*/
	public function phducheck(Request $oRequest, TranslatorInterface $t)
	{
		$oPhdUser = $this->_getAuthPhdUser($oRequest);
		if ($oPhdUser) {
			header('location: ' . $this->getParameter('app.appPageUrl') );
			exit;
		}
		$aData = [];
		$aData ['pageHeading'] = 'Authentication, step #2';
		$aData['sError'] = $t->trans('Possible your cookie is disabled, or user not found');
		$aData['isPhducheck'] = 1;
		return $this->render('phd/eauth.html.twig', $aData);
	}
	/**
	 * Авторизация по ссылке в email
	 * @Route("/phdusreau", name="phdusreau")
	*/
	public function phduseremailauth(Request $oRequest, TranslatorInterface $t)
	{
		$sAuthHash = $oRequest->get('ah', '');
		$oPhdUser = $this->getDoctrine()->getRepository('App:PhdUsers')->findOneBy(['authHash' => $sAuthHash]);
		$aData = [];
		$aData ['pageHeading'] = 'Authentication, step #1';
		if ($oPhdUser) {
			$oCookie = $this->_createPhdClientCookie($oPhdUser->getHash());
			$oResponse = $this->render('phd/eauth.html.twig', $aData);
			$oResponse->headers->setCookie($oCookie);
			return $oResponse;
		}
		$aData['sError'] = $t->trans('User not found');
		return $this->render('phd/eauth.html.twig', $aData);
	}

	/**
	 * Добавить запись в pay_transaction и вернуть идентификатор записи
	 * @Route("/phdstarttransaction.json", name="phdstarttransaction")
	*/
	public function phdstarttransaction(Request $oRequest, PayService $oPayService, TranslatorInterface $t)
	{
		$oEm = $this->getDoctrine()->getManager();
		$oPhdMessage = $this->_getPhdMessage($oRequest);
		if (!$oPhdMessage) {
			return $this->_json([
				'status' => 'error',
				'msg' => $t->trans('Unauth user')
			]);
		}
		$oPayService->setPayTransactionEntityClassName('App\Entity\PhdPayTransaction');
		$oPayService->setOperationEntityClassName('App\Entity\PhdOperations');
		$oTransactionData = $oPayService->createTransaction($oPhdMessage->getUid(), $oPhdMessage->getId());
		$nTransactionId = $oTransactionData->nPayTransactionId;
		$sPayUrl = $oTransactionData->sPayUrl;
		$oPhdMessage->setState(7);
		$oEm->persist($oPhdMessage);
		$oEm->flush();
		$aData = [
			'id' => $nTransactionId,
			'ops' => $oTransactionData->nBillId,
			'url' => $sPayUrl
		];

		if ($oTransactionData->sError) {
			$aData['status'] = 'error';
			$aData['msg'] = $oTransactionData->sError;
		}
		return $this->_json($aData);
	}
	/**
	 * Установить факт, что работу можно показывать в примерах
	 * @Route("/phddiscount.json", name="phddiscount")
	*/
	public function phddiscount(Request $oRequest, AppService $oAppService, TranslatorInterface $t)
	{
		$oEm = $this->getDoctrine()->getManager();
		$oPhdMessage = $this->_getPhdMessage($oRequest);
		if (!$oPhdMessage) {
			return $this->_json([
				'status' => 'error',
				'msg' => $t->trans('Unauth user')
			]);
		}
		$bVal = $oRequest->get('sum') == static::SUM_FULL_NOMINAL ? false : true;
		$nSum = $oRequest->get('sum') == static::SUM_FULL_NOMINAL ? static::SUM_FULL : static::SUM_DISCOUNT;
		$oPhdMessage->setIsPublish($bVal);
		$oEm->persist($oPhdMessage);
		$oEm->flush();
		$aData = [
			'sum' => $nSum,
			'yc' => $this->getParameter('app.yacache')
		];
		return $this->_json($aData);
	}
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
		/** @var \Symfony\Component\HttpFoundation\Cookie $oCookie */
		$oCookie = $this->_createPhdClientCookie($sCookieValue);
		$oResponse->headers->setCookie($oCookie);
		return $oResponse;
	}

	/**
	 * Установить работу как отмененную (пользователь решил загрузить другой файл)
	 * @Route("/phdnewpsd.json", name="phdnewpsd")
	*/
	public function phdnewpsd(Request $oRequest, AppService $oAppService, TranslatorInterface $t)
	{
		$oEm = $this->getDoctrine()->getManager();
		$oPhdMessage = $this->_getPhdMessage($oRequest);
		if (!$oPhdMessage) {
			return $this->_json([
				'status' => 'error',
				'msg' => $t->trans('Unauth user')
			]);
		}
		$oPhdMessage->setState(9);
		$oEm->persist($oPhdMessage);
		$oEm->flush();
		$aData = [];
		$this->_setPsdUploadFormToken($aData, $oAppService);
		return $this->_json($aData);
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
				'msg' => $t->trans('Unauth user 1')
			]);
		}
		$oPhdMessage = $this->_getPhdMessage($oRequest, $oPhdUser);
		if (!$oPhdMessage) {
			return $this->_json([
				'status' => 'error',
				'msg' => $t->trans('Unauth user')
			]);
		}
		$aData = [
			'st' => $oPhdMessage->getState()
		];
		$this->_setShowPreviewVariables($aData, $oPhdMessage);
		$this->_setResultLinkVariables($aData, $oPhdMessage);
		return $this->_json($aData);
	}
	/**
	 * Проверяет, установлена ли кука app.phdusercookiename
	 * @Route("/phdcheckin.json", name="phdcheckin")
	*/
	public function checkIn(Request $oRequest, AppService $oAppService)
	{
		$oPhdUser = $this->_getAuthPhdUser($oRequest);
		$oPhdMessage = $this->_getPhdMessage($oRequest, $oPhdUser);
		$aData = [];
		$this->_setPsdUploadFormToken($aData, $oAppService);

		if ($oPhdUser) {
			$oSession = $oRequest->getSession();
			$oSession->set('phd_user', $oPhdUser);
			$aData['cookieFound'] = 1;
			if ($oPhdMessage) {
				$aData['st'] = $oPhdMessage->getState();
				$this->_setShowPreviewVariables($aData, $oPhdMessage);
				$this->_setResultLinkVariables($aData, $oPhdMessage);
			}
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
					$this->_oMessage->setCreatedAt($oAppService->now());
					$oPhdUser = $this->_getAuthPhdUser($oRequest);
					//$this->_oMessage->setUid($oPhdUser->getId());
					$this->_oMessage->setPhdUser($oPhdUser);
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
	/**
	 * Установка токена формы
	 * @param array &$aData
	*/
	private function _setPsdUploadFormToken(array &$aData, AppService $oAppService) : void
	{
		$this->_subdir = 'd/' . date('Y/m');
		$oForm = $this->createForm(get_class(new PsdUploadFormType()), null, [
			'app_service' => $oAppService,
			'uploaddir' => $this->_subdir
		]);
		$aData['formToken'] = $oForm->createView()->children['_token']->vars['value'];
	}
	/**
	 * Установить переменные необходимые для показа экрана превью работы
	 * @param array &$aData
	 * @param PhdMessages $oPhdMessage
	*/
	private function _setShowPreviewVariables(array &$aData, PhdMessages $oPhdMessage)
	{
		if ($oPhdMessage->getState() == 3 || $oPhdMessage->getState() == 7) {
			$aData['previewLink'] = $oPhdMessage->getPreviewLink();
			$aData['notes'] = $oPhdMessage->getServiceNotes();
			$aData['noticePreviewLink'] = $oPhdMessage->getNoticePreviewLink();
			$aData['htmlExampleLink'] = $oPhdMessage->getHtmlExampleLink();
			$aData['cssPreviewLink'] = $oPhdMessage->getCssPreviewLink();
		}
	}
	/**
	 * Установить переменные необходимые для показа экрана с сылкой на результат
	 * @param array &$aData
	 * @param PhdMessages $oPhdMessage
	*/
	private function _setResultLinkVariables(array &$aData, PhdMessages $oPhdMessage)
	{
		if ($oPhdMessage->getState() == 8) {
			$aData['resultLink'] = $this->getParameter('app.siteUrlBegin') . $oPhdMessage->getResultLink();
		}
	}
	/**
	 * Установить куку авторизации клиента 
	 * @param string  $sCookieValue
	 * @param Cookie
	*/
	private function _createPhdClientCookie(string $sCookieValue)
	{
		$sCookieName = $this->getParameter('app.phdusercookiename');
		return Cookie::create($sCookieName, $sCookieValue, time() + 31536000);
	}
		
}
