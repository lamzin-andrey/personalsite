<?php

namespace App\Controller;

use App\Service\PayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class YamoneyNoticeRecieverController extends AbstractController
{
    /**
	 * Обработка уведомлений от сервиса Yandex Money
     * @Route("/yamoney/notice/reciever", name="yamoney_notice_reciever")
    */
    public function index(PayService $oService)
    {
    	$oService->setHttpNoticeEntityClassName('App\Entity\YaHttpNotice');
    	$oService->setPayTransactionEntityClassName('App\Entity\PhdPayTransaction');
    	$oService->setUserEntityClassName('App\Entity\PhdUsers');
    	$oService->setYandexNotificationEntityClassName('App\Entity\YaNotificationType');
    	$oService->setOperationEntityClassName('App\Entity\PhdOperations');
        return $oService->processYandexNotice($this, 'setWorkAsPayed');
    }
	/**
	 * @param string $label
	 * @param array $aInfo  {user_id, sum, email, phone} - можно использовать например  для отправки письма
	*/
    public function setWorkAsPayed(string $label, array $aInfo)
	{
		//Помечаем товар как оплаченный
		$oRepository = $this->getDoctrine()->getRepository('App\Entity\PhdMessages');
		$oMessage = $oRepository->find($label);
		if ($oMessage) {
			$oMessage->setIsPayed(true);
			$oEm = $this->getDoctrine()->getManager();
			$oEm->persist($oMessage);
			$oEm->flush();
		}
	}
}
