<?php
namespace App\Service;

use \Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\Criteria;
/**
 Потом будет бандл
*/
class PayService
{
	public function __construct(ContainerInterface $container)
	{
		$this->_oContainer = $container;
		$this->oTranslator = $container->get('translator');
		$this->_oRequest = $container->get('request_stack')->getCurrentRequest();
	}
	/**
	 * Добавить запись в таблицу транзакций
	 * @return int идентификатор записи из таблицы связанной с сущностью 0 - если не удалось создать запись
	*/
	public function createTransaction(int $nUserId) : int
	{
		$sClass = $this->_sPayTransactionClass;
		$oPayTransaction = new $sClass();
		$oPayTransaction->setUserId($nUserId);
		//NOTE для rk было безразлично, возможно qiwi заставит пересмотреть
		//Пока всегда пишем номер я-кошелька
		$oPayTransaction->setCache($this->_oContainer->getParameter('app.yacache'));
		$oPayTransaction->setSum( strval(floatval($this->_oRequest->get('sum', 0))) );
		$sMethod = '';
		$sRawMethod = $this->_oRequest->get('method', '');
		switch ($sRawMethod) {
			case 'MC':
				$sMethod = 'ms';
				break;
			case 'AC':
				$sMethod = 'bs';
				break;
			case 'PC':
				$sMethod = 'ps';
		}
		if (!$sMethod) {
			return 0;
		}
		$oPayTransaction->setMethod($sMethod);
		//TODO проверить, что там в created записывается ли?
		$oEm = $this->_oContainer->get('doctrine')->getManager();
		$oEm->persist($oPayTransaction);
		$oEm->flush();
		return ($oPayTransaction->getId() ?? 0 );
	}

	public function setPayTransactionEntityClassName(string $s)
	{
		$this->_sPayTransactionClass = $s;
	}
}
