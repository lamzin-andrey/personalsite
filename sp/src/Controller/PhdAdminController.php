<?php

namespace App\Controller;

use App\Entity\PhdUsers;
use App\Service\AppService;
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

	/** @property \DateTime $_sMessageDatetime  Заполняется временем подачи заявки */
	private $_sMessageDatetime = '';


	/** @property bool $_bOtherOperator принимает true если текущую заявку обрабатывает другой оператор */
	private $_bOtherOperator = false;

	/** @property \App\Entity\PhdMessages $_oMessage принимает true если текущую заявку обрабатывает другой оператор */
	private $_oMessage;


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
	public function phpadminRequest(int $requestId, TranslatorInterface $t)
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
			'payed' => $this->_oMessage->getIsPayed(),
			'pageHeading' => $t->trans('Order datetime') . ': ' . $this->_sMessageDatetime->format('Y-m-d H:i'),
		];
		$this->_setPayInfo($aData, $t);
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
		$this->_sMessageDatetime = $oMessage->getCreatedAt();
		switch ($oMessage->getState()) {
			case 0:
				if ($oMessage->getOperatorId() == 0) {
					return $t->trans('Wait processing');
				}
				if ($oMessage->getOperaitorId() != $this->getUser()->getId()) {
					$this->_sOtherOperatorEmail = $this->getUser()->getEmail();
					$this->_bOtherOperator = true;
					return $t->trans('Other processing');
				}
				return $t->trans('Your processing');
			case 1:
				return $t->trans('Upload process');
			case 2:
				return $t->trans('Convert process');
			case 3:
				return $t->trans('Preview_showed');
			case 7:
				return $t->trans('Wait payment');
			case 8:
				return $t->trans('Link sended');
			case 9:
				return $t->trans('Canceled');
		}
	}
	/**
	 * Установить переменые связанные со способом оплаты
	 * @param array &$aData
	*/
	private function _setPayInfo(array &$aData, TranslatorInterface $t) : void
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
		//TODO время прибытия нотайса - добавить обязательно добавить поле в pay_transaction.
		$aData['sPaydatetime'] = 'TODO';
		$oRepository = $this->getDoctrine()->getRepository('App:PhdPayTransaction');
		$oTransaction = $oRepository->find($operation->getPayTransactionId() );
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
		}
	}
}
