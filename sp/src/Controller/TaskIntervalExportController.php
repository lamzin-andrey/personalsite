<?php

namespace App\Controller;

use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TaskIntervalExportController extends AppBaseController
{
    /**
     * @Route("/exporttask", name="task_interval_export")
     */
    public function index(Request $oRequest, AppService $oAppService, TranslatorInterface $t)
    {
    	if (!$this->_accessControl()) {
    		return $this->redirectToRoute('login');
		}
    	$nTaskId = intval($oRequest->get('id', 0) );
		$aData = [
			'pageHeading' => $t->trans('Export Task Intervals'),
			'sDate' => '',
			'aIntervals' => []
		];
    	if ($nTaskId) {
			$oTask = $oAppService->repository('App:CrnTasks')->findOneBy([
				'id' => $nTaskId,
				'ausersId' => $this->getUserId()
			]);
			if (!$oTask) {
				$this->addFlash('notice', $t->trans('You have not access to this task'));
				return $this->render('task_interval_export/articles.html.twig', $aData);
			}
			$aData['oTask'] = $oTask;
		}
		$sDate = $oRequest->get('pc-calindar-date', date('Y-m-d'));
    	$sDate = trim($sDate) ?? date('Y-m-d');
    	//TODO validate date!
		if (!preg_match("#^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$#", $sDate)) {
			$this->addFlash('notice', $t->trans('Invalid date'));
			return $this->render('task_interval_export/articles.html.twig', $aData);
		}


		$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnTasks')->createQueryBuilder('t');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->where( $e->eq('t.ausersId', $this->getUserId()) );
		$oQueryBuilder->select('t.id, t.parentId, t.name, t.codename, t.description');
		$aUserTasks = $oQueryBuilder->getQuery()->getResult();


		\TreeAlgorithms::$parentIdFieldName = 'parentId';
		$aTrees = \TreeAlgorithms::buildTreeFromFlatList($aUserTasks);
		$oRoot = new \StdClass();
		$oRoot->id = 0;
		$oRoot->parentId = -1;
		$oRoot->name = 'Root';
		$oRoot->codename = 'root';
		$oRoot->children = $aTrees;
		$oBranch = $oTree = $oRoot;
		if ($nTaskId) {
			$oBranch = \TreeAlgorithms::findById($oTree, $nTaskId);
		}

		$this->_aChildTasks = [];
		$oCallback = new \StdClass();
		$oCallback->context = $this;
		$oCallback->f = 'addChildTask';
		$oCallback->isStatic = false;
		\TreeAlgorithms::walkAndExecuteAction($oBranch, $oCallback);
		$aTaskIdList = array_column($this->_aChildTasks, 'id');
		if (!$aTaskIdList) {
			$aTaskIdList = [$nTaskId];
		}
    	/*
    	 * SELECT start_datetime. end_datetime FROM crn_intervals WHERE task_id in(N)
    	 * AND start_datetime >= 'sDate 00:00:00' AND end_datetime <= 'sDate 23:59:59'
    	*/
    	$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnIntervals')->createQueryBuilder('t');
    	$e = $oQueryBuilder->expr();
    	$oQueryBuilder->where( $e->in('t.taskId', $aTaskIdList) );
    	$oQueryBuilder->select('t.startDatetime, t.endDatetime, t.taskId');
    	$oQueryBuilder->andWhere( 't.startDatetime >= \'' . $sDate . ' 00:00:00\'' );
    	$oQueryBuilder->andWhere( 't.endDatetime <= \'' . $sDate . ' 23:59:59\'' );
    	$aData['aIntervals'] = $oQueryBuilder->getQuery()->getResult();
		$aData['sDate'] = $sDate;
		$aData['oTree'] = $oBranch;

        return $this->render('task_interval_export/index.html.twig', $aData);
    }
    /**
	 * Добавляет в _aChildTasks очередной объект с данными задачи
     * @param StdClass $oNode элемент дерева
    */
    public function addChildTask($oNode)
    {
    	if ($oNode) {
			$this->_aChildTasks[] = $oNode;
		}
    }
}
