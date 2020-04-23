<?php
namespace App\Service;

use App\Entity\CrnIntervals;
use App\Entity\CrnTasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManager;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use App\Entity\Main;
//use \Landlib\Text2Png;
use Doctrine\Common\Collections\Criteria;
//use Landlib\RusLexicon;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityRepository;

/**
 * @class CrudAjaxService предназначен для распространённых операций на страницах, реализующих CRUD
 *    и использующих AJAX.
 * Таких как изменение позиции записи.
 * Возможно, сюда будет также вынесена какая-то общая логика при создании, редактировании и удалении записей,
 *  иначе сервис будет переименован
*/

class CrudAjaxService
{

	/** @property FormInterface $_oForm Сюда можно передать форму для более простой работы с ними */
	private $_oForm;

	public function __construct(ContainerInterface $container, ?ViewDataService $oViewDataService = null, ?FileUploaderService $oFileUploaderService = null)
	{
		$this->oContainer = $container;
		$this->oTranslator = $container->get('translator');
		$this->oViewDataService = $oViewDataService;
		$this->oFileUploaderService = $oFileUploaderService;
	}

	/**
	 * Переставить записи в таблице
	 * @param ServiceEntityRepositoryInterface $oRepository
	 * @param array $aOrderRecordsInfo for example ['15', '19', '25', '18'] each value is entity.id
     * @param string  $sOrderFieldName = 'delta' имя поля, по которому упорядочиваются записи сущности
	 * @return
	*/
	public function rearrangeRecords(ServiceEntityRepositoryInterface $oRepository, array $aOrderRecordsInfo, string $sOrderFieldName = 'delta')
	{
        $oQueryBuilder = $oRepository->createQueryBuilder('t');
        $e = $oQueryBuilder->expr();
        $oQueryBuilder->select('MIN(t.' . $sOrderFieldName . ') AS m')
            ->where($e->in('t.id', $aOrderRecordsInfo));
        $aMinInfo = $oQueryBuilder->getQuery()->getSingleResult();
        $nMin = ($aMinInfo['m'] ?? 0);
        foreach ($aOrderRecordsInfo as $nId) {
            $oQueryBuilder = $oRepository->createQueryBuilder('t');
            $oQueryBuilder->update()->set('t.' . $sOrderFieldName, $nMin)
                ->where($e->eq('t.id', $nId))
                ->getQuery()->execute();
            $nMin++;
        }
	}
	/**
	 * @param ServiceEntityRepositoryInterface $oRepository
	 * @param string $sPositionFieldName = 'delta'
     * @return int максимальное значение поля $sPositionFieldName в репозитории сущностей $oRepository
	*/
	public function getNextPosition(ServiceEntityRepositoryInterface $oRepository, string $sPositionFieldName = 'delta') : int
	{
        $oQueryBuilder = $oRepository->createQueryBuilder('t');
        $oQueryBuilder->select('MAX(t.' . $sPositionFieldName . ') AS m');
        $aMaxInfo = $oQueryBuilder->getQuery()->getSingleResult();
        $nMax = ($aMaxInfo['m'] ?? 0);
        return ($nMax + 1);
	}
	/**
	 * Переместить запись на другую страницу (при постраничной выдаче)
	 * @param int $nId идентификатор сущности (записи)
	 * @param ServiceEntityRepositoryInterface $oRepository
	 * @param string $sDirect  = 'u' 'u' for previous page or 'd' for next page
	 * @param string $sDisplayedOrder = 'ASC' или 'DESC' это порядок сортировки используемый для отображаемых данных
	 * @param array $aFieldsForSelect = ['id', 'name', 'delta'], поля, которые надо выбрать после пермещения записей (чтобы отобразить их для записи, появившейся на странице вместо перемещённой)
	 * @param string $sPositionFieldName = 'delta'
	 * @param string $sIdFieldName = 'id'
	 * @return array В случае успеха ['srcId' => $nId, 'newRec' => ... ] с данными полей из $aFieldsForSelect
	 *                иначе ['msg' => 'error', 'msg' => ... ]
	*/
	public function moveRecordToOtherPage(int $nId, ServiceEntityRepositoryInterface $oRepository, string $sDirect = 'u', string $sDisplayedOrder = 'ASC', array $aFieldsForSelect = ['id', 'name', 'delta'], string $sPositionFieldName = 'delta', string $sIdFieldName = 'id')
	{
		$t = $this->oTranslator;
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->select('t.' . $sPositionFieldName);
		$aDelta = $oQueryBuilder->where( $e->eq('t.id', $nId) )->getQuery()->getSingleResult();
		$nPos = intval($aDelta[$sPositionFieldName]);

		$sOperation = $sDirect == 'd' ? '>=' : '<=';
		$sDirection = $sDirect == 'd' ? 'ASC' : 'DESC';
		if ($sDisplayedOrder == 'DESC') {
			$sDirection = $sDirection == 'ASC' ? 'DESC' : 'ASC';
			$sOperation = $sOperation == '>=' ? '<=' : '>=';
		}
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		$oQueryBuilder->where( 't. ' . $sPositionFieldName . ' ' . $sOperation .  ' ' . $nPos );
		foreach ($aFieldsForSelect as &$item) {
			$item = 't.' . $item;
		}
		$select = implode(', ', $aFieldsForSelect);
		$oQueryBuilder->select($select);
		$oQueryBuilder->orderBy( ('t.' . $sPositionFieldName), $sDirection);
		$oQueryBuilder->setMaxResults(2);

		$aRows = $oQueryBuilder->getQuery()->getResult();


		$nRows = count($aRows);
		if ($nRows == 2) {
			$aRows = array_column($aRows, null, $sIdFieldName);
			$aMovingRow = ($aRows[$nId] ?? []);
			unset($aRows[$nId]);
			$aRow = current($aRows);//it next or prew row
			if ($aMovingRow[$sPositionFieldName] == $aRow[$sPositionFieldName]) {
				if ($sDirect == 'd') {
					$aMovingRow[$sPositionFieldName]++;
				} else {
					$aRow[$sPositionFieldName]++;
				}
			}
			$this->_swapRecords($nId, $aMovingRow[$sPositionFieldName], $aRow[$sIdFieldName], $aRow[$sPositionFieldName], $oRepository, $sPositionFieldName, $sIdFieldName);
			$aResult['newRec'] = $aRow;
			$aResult['srcId'] = $nId;
			return $aResult;
		}
		$aResult['msg'] = $t->trans('Record is ' . ($sDirect == 'd' ? 'last' : 'top'));
		$aResult['status'] = 'error';
		return $aResult;
	}
	/**
	 * @see moveRecordToOtherPage
	 *  Меняет позиции двух записей
	 * @param int $nId1
	 * @param int $nPos1
	 * @param int $nId2
	 * @param int $nPos2
	 * @param ServiceEntityRepositoryInterface $oRepository
	 * @param string $sPositionFieldname = 'delta',
	 * @param string $sIdFieldname = 'id'
	 */
	private function _swapRecords(int $nId1, int $nPos1, int $nId2, int $nPos2, ServiceEntityRepositoryInterface $oRepository, string $sPositionFieldname = 'delta', string $sIdFieldname = 'id')
	{
		//$sql = 'UPDATE ' . $this->table . ' SET delta = ' . $nPos2 . ' WHERE id = ' . $nId1;
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->update();
		$oQueryBuilder->set( ('t.' . $sPositionFieldname), $nPos2);
		$oQueryBuilder->where( $e->eq( ('t.' . $sIdFieldname), $nId1) );
		$oQueryBuilder->getQuery()->execute();
		//$sql = 'UPDATE ' . $this->table . ' SET delta = ' . $nPos1 . ' WHERE id = ' . $nId2;
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		$oQueryBuilder->update();
		$oQueryBuilder->set( ('t.' . $sPositionFieldname), $nPos1);
		$oQueryBuilder->where( $e->eq( ('t.' . $sIdFieldname), $nId2) );
		$oQueryBuilder->getQuery()->execute();
	}
}
