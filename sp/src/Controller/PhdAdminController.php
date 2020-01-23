<?php

namespace App\Controller;

use App\Entity\PhdUsers;
use App\Form\PhdAdminUploaderFormType;
use App\Service\AppService;
use App\Service\FileUploaderService;
use App\Service\PayService;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PhdAdminController extends AbstractController
{
	/** @property int $_nUncompleteId  Заполняется номером недообработанной заявки если он не совпадает с переданным requestId  */
	private $_nUncompleteId = 0;

	/** @property string $_sOtherOperatorEmail  Заполняется email оператора, взявшего себе заявку ва том случае если не совпадает с текущим оператором */
	private $_sOtherOperatorEmail = '';

	/** @property string $_sPhdUserEmail  Email на который должны быть отправлены данные о конвертации */
	private $_sPhdUserEmail = '';

	/** @property \DateTime $_sMessageDatetime  Заполняется временем подачи заявки */
	private $_sMessageDatetime = '';


	/** @property bool $_bOtherOperator принимает true если текущую заявку обрабатывает другой оператор */
	private $_bOtherOperator = false;

	/** @property \App\Entity\PhdMessages $_oMessage принимает true если текущую заявку обрабатывает другой оператор */
	private $_oMessage;

	/**
	 * @Route("/phdadminpreviewupload.json", name="phdadminpreviewupload")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService $oAppService
	 * @return
	*/
	public function phdadminpreviewupload(Request $oRequest, TranslatorInterface $t, AppService $oAppService, FileUploaderService $oFileUploaderService)
	{
		//std validation
		if (!$this->_hasPermissions()) {
			return $this->_json([]);
		}
		$aData = [];
		$nRequestId = $this->_getCurrentRequestIdForOperator();
		if (!$nRequestId) {
			$aData['status'] = 'error';
			$aData['msg'] = $t->trans('Unable get current order id');
			return $this->_json($aData);
		}
		$bRequestIdError = $this->_setRequestIdError($oRequest, $oAppService, $t, $aData, $nRequestId);//TODO add arg
		if ($bRequestIdError) {
			return $this->_json($aData);
		}

		if ($oRequest->getMethod() == 'POST') {
			$sFormName = 'preview_up_form';
			$aData =[];
			$this->_subdir = 'd/' . date('Y/m');
			$oForm = $this->createForm(get_class(new PhdAdminUploaderFormType()), null, [
				'app_service' => $oAppService,
				'uploaddir' => $this->_subdir
			]);
			//$oForm->handleRequest($oRequest);
			$oForm->submit([
				'_token' => $oRequest->get($sFormName)['_token'],
				'previewfileFileImmediately' => ($oRequest->files->get($sFormName)['previewfileFileImmediately'] ?? null),
				'resultfileFileImmediately' => ($oRequest->files->get($sFormName)['resultfileFileImmediately'] ?? null),
				'previewnoticefileFileImmediately' => ($oRequest->files->get($sFormName)['previewnoticefileFileImmediately']  ?? null),
				'htmlexampleFileImmediately' => ($oRequest->files->get($sFormName)['htmlexampleFileImmediately'] ?? null),
				'previewcssFileImmediately' => ($oRequest->files->get($sFormName)['previewcssFileImmediately'] ?? null)
			]);
			if ($oForm->isSubmitted() && $oForm->isValid()) {
				//save file
				$oFile = $oForm['previewfileFileImmediately']->getData();
				//TODO тут будет определение, какой файл загрузили
				$sMethod = 'setPreviewLink';
				$sModel = 'previewLink';
				if ($oFile) {
					$sFileName = $oFileUploaderService->upload($oFile);
					$s = '/' . $this->_subdir . '/' . $sFileName;
					$this->_oMessage->$sMethod($s);
					$this->_oMessage->setCreatedAt($oAppService->now());
					$aData['path'] = $s;
					$aData['m'] = $sModel;
					$this->_saveMessage();
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
	 * @Route("/phdadminsetremoteresource.json", name="phdadminsetremoteresource")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	*/
	public function phdadminsetremoteresource(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
	{
		if (!$this->_hasPermissions()) {
			return $this->_json([]);
		}
		$aData = [];
		$bRequestIdError = $this->_setRequestIdError($oRequest, $oAppService, $t, $aData);
		if ($bRequestIdError) {
			return $this->_json($aData);
		}
		$sRemoteLink = $oRequest->get('h');
		$sModelName = $oRequest->get('m');
		$fileData = file_get_contents($sRemoteLink);

		$sHtmlFolder = '/i/' . date('Y/m') . '/';
		$sFolder = $oRequest->server->get('DOCUMENT_ROOT') . $sHtmlFolder;
		$sSavedPath = $sFolder . $this->getUser()->getId() . '.img';
		//TODO create folder
		file_put_contents($sSavedPath, $fileData);
		$aInfo = getimagesize($sSavedPath);
		if ($aInfo['mime'] != 'image/jpeg' && $aInfo['mime'] != 'image/png') {
			$aData['status'] = 'error';
			$aData['msg'] = $t->trans('Unable load image file from remote server');
			return $this->_json($aData);
		}


		$sMethodName = '';
		$savedFileSuffix = '';
		switch ($sModelName) {
			case 'previewLink':
				$sMethodName = 'setPreviewLink';
				break;
			case 'noticePreviewLink':
				$sMethodName = 'setNoticePreviewLink';
				$savedFileSuffix = 'notice';
				break;
			case 'cssPreviewLink':
				$sMethodName = 'setCssPreviewLink';
				$savedFileSuffix = 'css';
				break;
		}
		if (!$sMethodName) {
			$aData['status'] = 'error';
			$aData['msg'] = $t->trans('Unable dtect db field name for save');
			return $this->_json($aData);
		}

		$sBasefilename = 'pu' . $this->_oMessage->getPhdUser()->getId() . 'n' .  $this->_oMessage->getId() . 'o' . $this->getUser()->getId() . $savedFileSuffix;
		$sFilename = '';
		if ($aInfo['mime'] == 'image/jpeg') {
			$sFilename = $sBasefilename . '.jpg';
		}
		if ($aInfo['mime'] == 'image/png') {
			$sFilename = $sBasefilename . '.png';
		}
		copy($sSavedPath, $sFolder . $sFilename);
		$sFileLink = $sHtmlFolder . $sFilename;

		$this->_oMessage->$sMethodName($sFileLink);

		$this->_saveMessage();
		$aData['m'] = $sModelName;
		$aData['path'] = $sFileLink;
		return $this->_json($aData);
	}
	/**
	 * @Route("phdadminsavenotices.json", name="phdadminsavenotices")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	*/
	public function savenotices(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
	{
		if (!$this->_hasPermissions()) {
			return $this->_json([]);
		}
		$aData = [];
		$bRequestIdError = $this->_setRequestIdError($oRequest, $oAppService, $t, $aData);
		if ($bRequestIdError) {
			return $this->_json($aData);
		}
		$sHtmlNotices = $oRequest->get('t');
		$bIsPlainText = $oRequest->get('isplain');
		$bIsPlainText = $bIsPlainText == 'false' ? false : true;
		if ($bIsPlainText) {
			$a = explode("\n", $sHtmlNotices);
			$sHtmlNotices = '<p>' . join('</p><p>', $a) . '</p>';
		}
		$this->_oMessage->setServiceNotes($sHtmlNotices);
		$this->_saveMessage();
		$aData['gotIsPlain'] = $bIsPlainText;
		return $this->_json($aData);
	}

	/**
	 * @Route("/phdadminchangestate.json", name="phdadminchangestate")
	 * @param Request $oRequest
	*/
	public function changestate(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
	{
		if (!$this->_hasPermissions()) {
			return $this->_json([]);
		}
		$aData = [];
		$bRequestIdError = $this->_setRequestIdError($oRequest, $oAppService, $t, $aData);
		if ($bRequestIdError) {
			return $this->_json($aData);
		}
		$nState = intval($oRequest->get('s') );
		$this->_oMessage->setState($nState);
		$this->_saveMessage();
		switch ($nState) {
			case 1:
				$aData['statusMessage'] = $t->trans('Upload process') . ', ' .  $t->trans('Your processing');
				break;
			case 2:
				$aData['statusMessage'] = $t->trans('Convert process') . ', ' .  $t->trans('Your processing');
				break;
			case 3:
				$aData['statusMessage'] = $t->trans('Preview showed') . ', ' .  $t->trans('Your processing');
				break;
		}
		return $this->_json($aData);
	}

	/**
	 * @Route("/phdadmintakeorder.json", name="phdadmintakeorder")
	 * @param $
	 * @return
	*/
	public function takeorder(Request $oRequest, TranslatorInterface $t)
	{
		if (!$this->_hasPermissions()) {
			return $this->_json([]);
		}
		$nRequestId = intval($oRequest->get('id'));
		$aData = [];
		if ($this->_hasNotCompletedOperation($nRequestId) ) {
			$aData['status'] = 'error';
			$aData['msg'] = $t->trans('Unable take new order  while you have uncomplete order');
			return $this->_json($aData);
		}
		$oPhdMessage = $this->_getMessageRepository()->find($nRequestId);
		if (!$oPhdMessage) {
			$aData['status'] = 'error';
			$aData['msg'] = $t->trans('Message not found');
			return $this->_json($aData);
		}

		if ($oPhdMessage->getOperatorId() != $this->getUser()->getId() && $oPhdMessage->getOperatorId() != 0) {
			$aData['status'] = 'error';
			$aData['msg'] = $t->trans('Unable take alien order!');
			return $this->_json($aData);
		}

		$oPhdMessage->setOperatorId($this->getUser()->getId() );
		$oEm = $this->getDoctrine()->getManager();
		$oEm->persist($oPhdMessage);
		$oEm->flush();
		$aData['statusMessage'] = $t->trans('Your processing');
		return $this->_json($aData);
	}
	/**
	 * @Route("/phdagetmessages.json", name="phdagetmessages")
	*/
	public function phpadminGetList(Request $oRequest, AppService $oAppService)
	{
		if (!$this->_hasPermissions()) {
			return $this->_json([]);
		}
		$oRepository = $this->_getMessageRepository();
		$oCriteria = Criteria::create();
		$ex = Criteria::expr();
		/*
		 * SELECT * FROM phd_messages WHERE
		 * 		is_closed != 1
		 *     AND (operatior_id = 0 OR operatior_id = Im)
		 * ORDER BY id
		*/
		$nId = $this->getUser()->getId();
		$oCriteria->where( $ex->andX( $ex->neq('isClosed', 1), $ex->orX( $ex->eq('operatorId', 0), $ex->eq('operatorId', $nId) )  ) )
			->orderBy(['id' => 'ASC']);
		$aList = $oRepository->matching($oCriteria)->toArray();
		$aResult = [];
		foreach ($aList as $oItem) {
			$aItem = [
				'id' => $oItem->getId(),
				'psd_link' => $oItem->getPsdLink(),
				'phone' => $this->_getPhoneNumber($oItem->getId(), $oAppService),
				'email' => $this->_getEmail($oItem->getPhdUser())
			];
			$aResult[$oItem->getId()] = $aItem;
		}

		return $this->_json(['list' => $aResult]);
	}

	/**
	 * @Route("/phdadmin/request/{requestId}", name="phdadmin_request")
	 */
	public function phpadminRequest(int $requestId, TranslatorInterface $t, AppService $oAppService)
	{
		if (!$this->_hasPermissions()) {
			return $this->redirectToRoute('home');
		}
		//Проверить, нет ли другого платежа, связанного с данным оператором и показать ссылку на него
		if ($this->_hasNotCompletedOperation($requestId)) {
			return $this->render('phd_admin/hasuncomplete.html.twig', [
				'nUncompleteId' => $this->_nUncompleteId,
			]);
		}

		$sMessageState = $this->_getMessageState($requestId, $t);
		if ($this->_bOtherOperator) {
			$this->addFlash('notice', $t->trans('Message processed operator') . ' ' . $this->_sOtherOperatorEmail );
			return $this->redirectToRoute('phd_admin');
		}
		$aData = [
			'messageState' => $sMessageState,
			'o' => $this->_oMessage,
			'payed' => $this->_oMessage->getIsPayed(),
			'email' => $this->_sPhdUserEmail,
			'pageHeading' => $t->trans('Order datetime') . ': ' . $this->_sMessageDatetime->format('Y-m-d H:i'),
		];
		$this->_setPsdUploadFormToken($aData, $oAppService);
		$this->_setPayInfo($aData, $t, $oAppService);
		return $this->render('phd_admin/message.html.twig', $aData);
	}
    /**
     * @Route("/phdadmin", name="phd_admin")
    */
    public function index()
    {
    	if (!$this->_hasPermissions()) {
    		return $this->redirectToRoute('home');
		}
        return $this->render('phd_admin/index.html.twig', [
            'controller_name' => 'PhdAdminController',
        ]);
    }
	/**
	 * Готовит JSON response
	*/
	private function _json($aData)
	{
		if (!isset($aData['status'])) {
			$aData['status'] = 'ok';
		}
		$oResponse = new Response( json_encode($aData) );
		$oResponse->headers->set("Content-Type", "application/json");
		return $oResponse;
	}

	private function _getMessageRepository()
	{
		return $this->getDoctrine()->getRepository('App:PhdMessages');
	}
	/**
	 * @return bool true когда пользователь имеет роль админ или оператор phd сообщений
	*/
	private function _hasPermissions() : bool
	{
		$oUser = $this->getUser();
		if (!$oUser) {
			return false;
		}
		$nRole = $oUser->getRole();
		return ( $nRole == 3 || $nRole == 2 );
	}
	/**
	 * Получить данные
	 * @param int $nPhdMessageId
	 * @return string телефонный номер заявки, если вводился
	*/
	private function _getPhoneNumber(int $nPhdMessageId, AppService $oAppService) : string
	{
		$oRepository = $this->getDoctrine()->getRepository('App:PhdOperations');
		$operation = $oRepository->findOneBy(['mainId' => $nPhdMessageId]);
		if ($operation) {
			$oRepository = $this->getDoctrine()->getRepository('App:PhdPayTransaction');
			$oTransaction = $oRepository->find($operation->getPayTransactionId());
			$s =  (!is_null($oTransaction->getPhone()) ? $oTransaction->getPhone() : '');
			if ($s) {
				return $oAppService->formatPhone($s);
			}
		}
		return '';
	}
	/**
	 * Получить данные
	 * @param \App\Entity\PhdUsers | null  $oPhdUser
	 * @return string телефонный номер заявки, если вводился
	*/
	private function _getEmail($oPhdUser) : string
	{
		if (!$oPhdUser) {
			return '';
		}
		return (!is_null($oPhdUser->getEmail()) ? $oPhdUser->getEmail() : '');
	}
	/**
	 * Проверить, нет ли другого платежа, связанного с данным оператором и показать ссылку на него
	 * @param int $nRequestId
	 * @return bool
	*/
	private function _hasNotCompletedOperation($requestId) : bool
	{
		$nOperatorId = $this->getUser()->getId();
		$oPhdMessage = $this->_getMessageRepository()->findOneBy([
			'operatorId' => $nOperatorId,
			'isClosed'  => 0
		], ['id' => 'ASC']);
		if ($oPhdMessage && $requestId != $oPhdMessage->getId()) {
			$this->_nUncompleteId = $oPhdMessage->getId();
			return true;
		}
		return false;
	}
	/**
	 *
	 * @param int $nRequestId
	 * @param TranslatorInterface $t
	 * @return string
	*/
	private function _getMessageState(int $nRequestId, TranslatorInterface $t) : string
	{
		$this->_oMessage = $oMessage = $this->_getMessageRepository()->find($nRequestId);
		if (!$oMessage) {
			return $t->trans('Order not found');
		}

		$oPhdUser = $oMessage->getPhdUser();
		if ($oPhdUser) {
			$this->_sPhdUserEmail = $oPhdUser->getEmail();
		}

		$this->_sMessageDatetime = $oMessage->getCreatedAt();
		$sProcessor = '';

		if ($oMessage->getOperatorId() == 0) {
			$sProcessor = ', ' . $t->trans('Wait processing');
		}
		if ($oMessage->getOperatorId() != $this->getUser()->getId() && $oMessage->getOperatorId() != 0) {
			$this->_sOtherOperatorEmail = $this->getUser()->getEmail();
			$this->_bOtherOperator = true;
			$sProcessor = ', ' .  $t->trans('Other processing');
		} else if ($oMessage->getOperatorId() == $this->getUser()->getId()) {
			$sProcessor = ', ' .  $t->trans('Your processing');
		}

		switch ($oMessage->getState()) {
			case 0:
				return $t->trans('Wait processing');
			case 1:
				return $t->trans('Upload process') . $sProcessor;
			case 2:
				return $t->trans('Convert process') . $sProcessor;
			case 3:
				return $t->trans('Preview showed') . $sProcessor;
			case 7:
				return $t->trans('Wait payment') . $sProcessor;
			case 8:
				return $t->trans('Link sended') . $sProcessor;
			case 9:
				return $t->trans('Canceled') . $sProcessor;
		}
	}
	/**
	 * Установить переменые связанные со способом оплаты
	 * @param array &$aData
	*/
	private function _setPayInfo(array &$aData, TranslatorInterface $t, AppService $oAppService) : void
	{
		if (!$this->_oMessage) {
			return;
		}
		$oRepository = $this->getDoctrine()->getRepository('App:PhdOperations');
		$operation = $oRepository->findOneBy(['mainId' => $this->_oMessage->getId()]);
		if (!$operation) {
			return;
		}
		$aData['sPayinfo'] = '';
		$aData['sPaysum'] = $operation->getSum();

		$aData['sPaydatetime'] = '';
		$oRepository = $this->getDoctrine()->getRepository('App:PhdPayTransaction');
		$oTransaction = $oRepository->find($operation->getPayTransactionId() );
		$aData['sPaydatetime'] = $t->trans('Payed at') . ' ' . $oTransaction->getNotifyDatetime()->format('Y-m-d H:i:s');
		if ($oTransaction) {
			switch($oTransaction->getMethod()) {
				case 'ms':
					$aData['sPayinfo'] = $t->trans('Yandex mobile commerce');
					break;
				case 'ps':
					$aData['sPayinfo'] = $t->trans('Yandex cache');
					break;
				case 'bs':
					$aData['sPayinfo'] = $t->trans('Yandex card');
					break;
			}
			$this->_sPhdPhone = trim($oTransaction->getPhone());
			if ($this->_sPhdPhone) {
				$aData['phone'] = $oAppService->formatPhone($this->_sPhdPhone);
			}
		}
	}
	/**
	 *
	 * @param Request $oRequest
	 * @param AppService $oAppService
	 * @param TranslatorInterface $t
	 * @param array &$aData
	 * @return  bool true если пытается отредактировать чужую заявку
	*/
	private function _setRequestIdError(Request $oRequest, AppService $oAppService, TranslatorInterface $t, array &$aData, int $nRequestId = 0) : bool
	{
		$nId = $nRequestId;
		if (!$nId )	{
			$nId = intval( $oRequest->get('id') );
		}
		$this->_oMessage = $oMessage = $this->_getMessageRepository()->find($nId);
		if (!$oMessage) {
			$aData['status'] = 'error';
			$aData['msg'] = $t->trans('Message not found');
			return true;
		}
		$nOperatorId = $oMessage->getOperatorId();
		if ($nOperatorId != 0 && $nOperatorId != $this->getUser()->getId()) {
			$aData['status'] = 'error';
			$operator = $this->getDoctrine()->getRepositiry('App:Ausers')->find($nOperatorId);
			$sEmailTail = '';
			if ($operator) {
				$sEmail = $operator->getEmail();
				if ($oAppService->isValidEmail($sEmail)) {
					$sEmailTail = ', (' . $sEmail . ')';
				}
			}
			$aData['msg'] = $t->trans('Alien order') . $sEmailTail;
			return true;
		}
		return false;
	}
	/**
	 * Получить идентификатор текущей заявки оператора
	 * @return int
	*/
	private function _getCurrentRequestIdForOperator() : int
	{
		$nOperatorId = $this->getUser()->getId();
		$oRepository = $this->_getMessageRepository();
		/*$oCriteria = Criteria::create();
		$ex = Criteria::expr();
		*
		 * SELECT * FROM phd_messages WHERE
		 * 		is_closed != 1
		 *     AND operatior_id = Im
		 * ORDER BY id
		*

		$oCriteria->where( $ex->andX( $ex->neq('isClosed', 1), $ex->orX( $ex->eq('operatorId', 0), $ex->eq('operatorId', $nId) )  ) )
			->orderBy(['id' => 'ASC']);
		$aList = $oRepository->matching($oCriteria)->toArray();*/
		$oMessage = $oRepository->findOneBy(['operatorId' => $nOperatorId, 'isClosed' => 0], ['id' => 'ASC']);
		if ($oMessage) {
			return $oMessage->getId();
		}
		return 0;
	}
	/**
	 * Сохранить $this->_oMessage в БД
	 * @return  void
	*/
	private function _saveMessage() : void
	{
		if ($this->_oMessage) {
			$oEm = $this->getDoctrine()->getManager();
			$oEm->persist($this->_oMessage);
			$oEm->flush();
		}
	}
	/**
	 * Установка токена формы
	 * @param array &$aData
	 */
	private function _setPsdUploadFormToken(array &$aData, AppService $oAppService) : void
	{
		$this->_subdir = 'd/' . date('Y/m');
		$oForm = $this->createForm(get_class(new PhdAdminUploaderFormType()), null, [
			'app_service' => $oAppService,
			'uploaddir' => $this->_subdir
		]);
		$aData['formToken'] = $oForm->createView()->children['_token']->vars['value'];
	}
}
