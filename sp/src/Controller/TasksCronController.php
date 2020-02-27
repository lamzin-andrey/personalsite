<?php

namespace App\Controller;

use App\Entity\CrnTasks;
use App\Form\TaskManagerType;
use App\Service\AppService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class TasksCronController extends AppBaseController
{
	/**
	 * @Route("/tasks/taskstop.json", name="taskstop")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService $oAppService
	 * @return Response
	 */
	public function taskstop(Request $oRequest, TranslatorInterface $t, AppService $oAppService) : Response
	{
		$this->_oAppService = $oAppService;
		$aResult = [];
		if (!$this->_accessControl()) {
			$aResult['msg'] = $t->trans('You have not access to this task');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
		if (!$this->isCsrfTokenValid('list', $oRequest->get('_token'))) {
			$aResult['msg'] = $t->trans('Invalid token');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
		$nId = $oRequest->get('id');
		if (!$nId) {
			$aResult['msg'] = $t->trans('Task id not found');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
		$oTask = $oAppService->repository('App:CrnTasks')->find($nId);
		if (!$oTask) {
			$aResult['msg'] = $t->trans('Task  not found');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
		$oInterval = null;
		$oAppService->stopTask($oTask, $oInterval, true);
		$aResult['stoppedTask'] = $oTask->getId();
		return $this->_json($aResult);
	}
	/**
	 * @Route("/tasks/taskrun.json", name="taskrun")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService $oAppService
	 * @return Response
	*/
	public function taskrun(Request $oRequest, TranslatorInterface $t, AppService $oAppService) : Response
	{
	    $this->_oAppService = $oAppService;
	    $aResult = [];
	    if (!$this->_accessControl()) {
	        $aResult['msg'] = $t->trans('You have not access to this task');
	        $aResult['status'] = 'error';
	        return $this->_json($aResult);
	    }
	    if (!$this->isCsrfTokenValid('list', $oRequest->get('_token'))) {
			$aResult['msg'] = $t->trans('Invalid token');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
	    $nId = $oRequest->get('id');
	    if (!$nId) {
			$aResult['msg'] = $t->trans('Task id not found');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
	    $oTask = $oAppService->repository('App:CrnTasks')->find($nId);
		if (!$oTask) {
			$aResult['msg'] = $t->trans('Task  not found');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
		$oResult = $oAppService->runTask($oTask, $this->getUserId(), true);
		$aResult = (array)$oResult;
		return $this->_json($aResult);
	}
	/**
	 * @Route("/tasks/move.json", name="tasks_move")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService $oAppService
	 * @return Response
	*/
	public function move(Request $oRequest, TranslatorInterface $t, AppService $oAppService) : Response
	{
		$this->_oAppService = $oAppService;
		$aResult = [];
		if (!$this->_accessControl()) {
			$aResult['msg'] = $t->trans('You have not access to this task');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
		$nId = intval($oRequest->get('id') );
		$sDirect = trim( strip_tags($oRequest->get('d')) );
		$aResult = [
			'srcId' => $nId,
		];
		if ($nId && ($sDirect == 'u' || $sDirect == 'd')) {
			//$nPos = dbvalue('SELECT delta FROM ' . $this->table . ' WHERE id = ' . $nId . ' LIMIT 1');
			$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnTasks')->createQueryBuilder('t');
			$e = $oQueryBuilder->expr();
			$oQueryBuilder->select('t.delta');
			$aDelta = $oQueryBuilder->where( $e->eq('t.id', $nId) )->getQuery()->getSingleResult();
			$nPos = intval($aDelta['delta']);

			$sOperation = $sDirect == 'd' ? '>=' : '<=';
			$sDirection = $sDirect == 'd' ? 'ASC' : 'DESC';
			//$aRows = query('SELECT id, url, heading, delta FROM ' . $this->table .
			// ' WHERE delta ' . $sOperation .  ' ' . $nPos . ' AND is_deleted != 1 ' .
			// ' ORDER BY '. $this->orderField . ' ' . $sDirection  . ' LIMIT 2', $nRows);
			$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnTasks')->createQueryBuilder('t');
			$oQueryBuilder->where( 't.delta ' . $sOperation .  ' ' . $nPos );
			$oQueryBuilder->select('t.id, t.name, t.delta');
			$oQueryBuilder->orderBy('t.delta', $sDirection);
			$oQueryBuilder->setMaxResults(2);

			$aRows = $oQueryBuilder->getQuery()->getResult();

			//var_dump($aRows);die;

			$nRows = count($aRows);
			if ($nRows == 2) {
				$aRows = array_column($aRows, null, 'id');
				$aMovingRow = ($aRows[$nId] ?? []);
				unset($aRows[$nId]);
				$aRow = current($aRows);//it next or prew row
				if ($aMovingRow['delta'] == $aRow['delta']) {
					if ($sDirect == 'd') {
						$aMovingRow['delta']++;
					} else {
						$aRow['delta']++;
					}
				}
				$this->_swapRecords($nId, $aMovingRow['delta'], $aRow['id'], $aRow['delta']);
				$aResult['newRec'] = $aRow;
				return $this->_json($aResult);
			} else {
				//TODO localize
				$aResult['msg'] = $t->trans('Record is ' . ($sDirect == 'd' ? 'last' : 'top'));
				$aResult['status'] = 'error';
				return $this->_json($aResult);
			}
		}
		$aResult['msg'] = $t->trans('Unknown error');
		$aResult['status'] = 'error';
		return $this->_json($aResult);
	}
	/**
	 * @Route("/tasks/reorder.json", name="tasks_reorder")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService $oAppService
	 * @param $
	 * @return
	*/
	public function reorder(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
	{
		$this->_oAppService = $oAppService;
		$aResult = [];
		if (!$this->_accessControl()) {
			$aResult['msg'] = $t->trans('You have not access to this task');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
		$a = $oRequest->get('a', []);
		$oRepository = $oAppService->repository('App:CrnTasks');
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->select('MIN(t.delta) AS m')
			->where($e->in('t.id', $a));
		$aMinInfo = $oQueryBuilder->getQuery()->getSingleResult();
		$nMin = ($aMinInfo['m'] ?? 0);
		foreach ($a as $nId) {
			$oQueryBuilder = $oRepository->createQueryBuilder('t');
			$oQueryBuilder->update()->set('t.delta', $nMin)
						  ->where( $e->eq('t.id', $nId) )
						  ->getQuery()->execute();
			$nMin++;
		}
		return $this->_json($aResult);
	}

	/**
	 * @Route("/tasks/task.json", name="task")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService $oAppService
	 * @return
	*/
	public function task(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
	{
		$this->_oAppService = $oAppService;
		$oTask = $oAppService->repository('App:CrnTasks')->find( intval($oRequest->get('id')) );
		$aData = [];
		if ($oTask) {
			$encoders = [new JsonEncoder()];
			$normalizers = [new ObjectNormalizer()];
			$oSerializer = new Serializer($normalizers, $encoders);
			$aData = json_decode($oSerializer->serialize([$oTask], 'json'), true)[0];
			$aData['tags'] = $this->_loadTagsByTaskId($oTask->getId());
			$aData['parentTaskData'] = $this->_loadParentTaskByTaskId($oTask->getParentId());
		}
		return $this->_json($aData);
	}
	/**
	 * @Route("/tasks/removetask.json", name="removetask")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService $oAppService
	*/
	public function removetask(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
	{
		$this->_oAppService = $oAppService;
		$nId = intval($oRequest->get('i'));
		$oAppService->deleteEntity('App:CrnTasks', $nId );
		return $this->_json(['id' => $nId]);
	}
    /**
     * @Route("/tasks.json", name="tasks_list")
    */
    public function index(Request $oRequest, AppService $oAppService)
    {
    	$this->_oAppService = $oAppService;
		$aResult = [
			'recordsTotal' => $this->_getTaskListCount(),
			'recordsFiltered' => $this->_getTaskListFiltredCount( $oRequest->get('search')['value'] ),
			'draw' => $oRequest->get('draw'),
			'data' => $this->_getTaskList( $oRequest->get('search')['value'], intval($oRequest->get('start')), intval( $oRequest->get('length', $this->getParameter('app.tasks_per_page') ) ) )
		];
        return $this->_json($aResult);
    }
	/**
	 * @Route("/parenttasks.json", name="parenttasks")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param $
	 * @return  
	*/
	public function parenttasks(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
	{
		$this->_oAppService = $oAppService;
		$aData = [
			'tags' => $this->_getTasks($oRequest->get('s')),
			'status' => 'ok'
		];
		return $this->_json($aData);
	}
    /**
     * @Route("/taglist.json", name="task_tags")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return Response
    */
    public function tags(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
    {
		$this->_oAppService = $oAppService;
		$aResult = [
			'recordsTotal' => 0,
			'recordsFiltered' => 0,
			'draw' => $oRequest->get('draw'),
			'data' => []
		];
		return $this->_json($aResult);
    }

    /**
	 * @Route("/savetask.json", name="savetask")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService  $oAppService
	 * @return Response
	 */
	public function savetask(Request $oRequest, TranslatorInterface $t, AppService $oAppService)
	{
		$this->_oAppService = $oAppService;
		$aResult = [
		];
		if (!$this->_accessControl()) {
			$aResult['msg'] = $t->trans('You have not access to this task');
			$aResult['status'] = 'error';
			return $this->_json($aResult);
		}
		if ($oRequest->getMethod() == 'POST') {
			$aFormData = $oRequest->get('crn_tasks_form');
			$oTask = new CrnTasks();
			$bSetDelta = true;
			if ($nId = intval($aFormData['id'])) {
				$oRepository = $this->_oAppService->repository('App:CrnTasks');
				$oTask = $oRepository->find($nId);
				$bSetDelta = false;
			}
			$oForm = $this->createForm(TaskManagerType::class, $oTask);
			$oForm->submit($aFormData);
			if ($oForm->isValid()) {

				$oTask->setAusersId($this->getUser()->getId());
				if ($aFormData['isPublic'] == 'false') {
					$oTask->setIsPublic(false);
				}
				$this->_setParentId($oTask, ($aFormData['parentIdData'] ?? ''));
				$sAction = ($aFormData['actionType'] ?? '');

				$oAppService->save($oTask);
				if ($bSetDelta) {
					$oTask->setDelta( $oTask->getId() );
					$oAppService->save($oTask);
				}
				$this->_saveTags(($aFormData['tags'] ?? ''), $oTask->getId());
				$aResult['id'] = $oTask->getId();
				if ($sAction == 'saveAndRun') {
					$oAppService->runTask($oTask, $this->getUser()->getId());
				}
			} else {
				$aErrors = $oAppService->getFormErrorsAsArray($oForm);
				$aResult['formErrors'] = $aErrors;
				$aResult['status'] = 'error';
			}
		}

		return $this->_json($aResult);
	}
	/**
	 * Сохраняет в crn_task_tags связи с тегами
	 * @param string $jsonData
	 * @param int $nTaskId
	*/
	private function _saveTags(string $jsonData, int $nTaskId) : void
	{	//Тут не смог отказать себе в удовольствии использовать ON DUPLICATE

		$aData = json_decode($jsonData);
		//Получить id новых тегов.
		foreach ($aData as $n => $oTagData) {
			if (!isset($oTagData->id)) {
				$aData[$n]->id = $this->_insertTag($oTagData->text);
			}
		}
		//Удалить из базы все, которые не входят в полученные в запросе id
		$oEm = $this->getDoctrine()->getManager();
		if (count($aData)) {
			$oEm->getConnection()->prepare('DELETE FROM crn_task_tags 
				WHERE task_id = ' . $nTaskId . '
				AND tag_id NOT IN(' . join(',', array_column($aData, 'id') ) . ')
			')->execute();
		} else {
			$oEm->getConnection()->prepare('DELETE FROM crn_task_tags 
				WHERE task_id = ' . $nTaskId)->execute();
		}

		//вcтaвить только те, которых нет.
		foreach ($aData as $n => $oTag) {
			$oEm->getConnection()->prepare('INSERT INTO crn_task_tags SET 
				task_id = ' . $nTaskId . ',
				tag_id = ' . $oTag->id . ' 
				ON DUPLICATE KEY UPDATE
				task_id = ' . $nTaskId)->execute();
		}
	}
	/**
	 * @param CrnTasks $oTask
	 * @param string $sParentTaskData
	*/
	private function _setParentId(CrnTasks $oTask, string $sParentTaskData) : void
	{
		$aData = json_decode($sParentTaskData);
		if (!is_array($aData) || !isset($aData[0]) || !isset($aData[0]->id)) {
			$oTask->setParentId(0);
			return;
		}
		$oTask->setParentId($aData[0]->id);
	}
	/**
	 * @param string $sText
	 * @return  int id вставленного тега
	*/
	private function _insertTag($sText) : int
	{
		$oEm = $this->getDoctrine()->getManager();
		$oEm->getConnection()->prepare('INSERT INTO crn_tags SET 
				name = \'' . $sText . '\' 
				ON DUPLICATE KEY UPDATE
				name = \'' . $sText . '\'')->execute();
		$oRepository = $this->_oAppService->repository('App:CrnTags');
		$oTag = $oRepository->findOneBy(['name' => $sText]);
		if ($oTag) {
			return $oTag->getId();
		}
		return 0;
	}
	/**
	 * Запрос для инпута выбора родительской задачи
	 * @param string $s Подстрока для поискаs
	 * @return array [id => name]
	*/
	private function _getTasks(string $s) : array
	{
		$oRepository = $this->_oAppService->repository('App:CrnTasks');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		$oCriteria->where( $e->orX( $e->contains('name', $s),  $e->contains('codename', $s)) );
		$aRaw = $oRepository->matching($oCriteria)->toArray();
		$a = [];
		foreach ($aRaw as $oTag) {
			$a[$oTag->getId()] = $oTag->getName() . ' ' . $oTag->getCodename();
		}
		return $a;
	}
	/**
	 * Запрос для списка задач
	 * @param string $s Подстрока для поиска
	 * @param int $nOffset
	 * @param int $nLength
	 * @return array
	*/
	private function _getTaskList(string $s, int $nOffset, int $nLength) : array
	{
		$oQueryBuilder = $this->_getQueryBuilderForTaskList($s);
		$oQueryBuilder->addOrderBy('t.isExecuted', 'DESC');
		$oQueryBuilder->addOrderBy('t.delta', 'DESC');
		$oQueryBuilder->addOrderBy('t.id', 'DESC');
		$oQueryBuilder->setFirstResult($nOffset);
		$oQueryBuilder->setMaxResults( $nLength );
		$aRaw = $oQueryBuilder->getQuery()->execute();
		$encoders = [new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$oSerializer = new Serializer($normalizers, $encoders);
		return json_decode($oSerializer->serialize($aRaw, 'json'));
	}
	/**
	 * Запрос для получения количества отфильтрованных записей
	 * @param string $s Подстрока для поискаs
	 * @return int
	 */
	private function _getTaskListFiltredCount(string $s) : int
	{
		$oQueryBuilder = $this->_getQueryBuilderForTaskList($s);
		$oQueryBuilder->select('COUNT(t.id) AS cnt');


		$n = intval($oQueryBuilder->getQuery()->getSingleScalarResult() );
		return $n;
	}
	/**
	 * Запрос для получения общего количества записей
	 * @param string $s Подстрока для поискаs
	 * @return array [id => name]
	*/
	private function _getTaskListCount() : int
	{
		$oRepository = $this->_oAppService->repository('App:CrnTasks');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		$aRaw = $oRepository->matching($oCriteria)->count();
		return $aRaw;
	}
	/**
	 * Создает QueryBuilder к которому применяет условия поиска.
	 * @see  _getTaskList, _getTaskListCount
	 * @param string $s Подстрока для поиска
	 * @return QueryBuilder
	*/
	private function _getQueryBuilderForTaskList(string $s) : QueryBuilder
	{
		/*
		 * SELECT tag_id FROM user_tags AS ut WHERE uid = UID;
		 *
		 * -- USER_TASK_ID_LIST получаем как сейчас, с учётом фильтра и надо туда добавитьб учёт пользователя
		 * SELECT task_id FROM task_tags WHERE task_id IN (USER_TASK_ID_LIST) AND  tag_id IN (TAGS_ID_LIST);
		 *
		 *
		 * SELECT * FROM tasks AS t
		 * WHERE task_id IN (ID_LIST)
		 *
		*/
		//Получаем без учёта тегов поиска
		/** @var QueryBuilder $oQueryBuilder */
		$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnTasks')->createQueryBuilder('t');
		/** @var Expr $e */
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->where( $e->eq('t.ausersId', $this->getUserId()) );
		$oQueryBuilder->andWhere( $e->orX( $e->like('t.name', '\'%' . $s . '%\''), $e->like('t.codename', '\'%' . $s . '%\'') ) );

		//тут смотрим, есть ли у пользователя теги поиска.
		$aUserTags = $this->_loadUserTagsId();
		if (!$aUserTags) {
			return $oQueryBuilder;
		}
		$oQueryBuilder->select('t.id');
		$aTaskIdData = array_column($oQueryBuilder->getQuery()->getResult(), 'id');
		//SELECT task_id FROM task_tags WHERE task_id IN (USER_TASK_ID_LIST) AND  tag_id IN (TAGS_ID_LIST);
		$aTaskIdWithTagData = $this->_loadTaskIdByTaskIdAndTagId($aTaskIdData, $aUserTags);
		if (!$aTaskIdWithTagData) {
			$aTaskIdWithTagData = [0];
		}
		/** @var QueryBuilder $oQueryBuilder */
		$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnTasks')->createQueryBuilder('t');
		$oQueryBuilder->where( $e->in('t.id', $aTaskIdWithTagData) );
		return $oQueryBuilder;
	}
	/**
	 * @param int $nTaskId
	 * @return array
	*/
	private function _loadTagsByTaskId(int $nTaskId) : array
	{
		$oRepository = $this->_oAppService->repository('App:CrnTaskTags');
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		/** @var QueryBuilder  $oQueryBuilder */
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->where( $e->eq('t.taskId', $nTaskId) );
		$oQueryBuilder->andWhere( $e->neq('tt.id', 0) );
		$oQueryBuilder->andWhere( $e->isNotNull('tt.id') );
		$oQueryBuilder->leftJoin('App:CrnTags', 'tt', 'WITH', $e->eq('t.tagId', 'tt.id'));
		$oQueryBuilder->select('tt.id, tt.name');

		$aRaw = $oQueryBuilder->getQuery()->execute();

		return $aRaw;
	}
	/**
	 *
	 * @param int $nTaskId
	 * @return array
	*/
	private function _loadParentTaskByTaskId(int $nTaskId) : array
	{
		$oRepository = $this->_oAppService->repository('App:CrnTasks');
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		/** @var QueryBuilder  $oQueryBuilder */
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->where( $e->eq('t.id', $nTaskId) );
		$oQueryBuilder->select('t.id, t.name');
		$aRaw = $oQueryBuilder->getQuery()->execute();
		return $aRaw;
	}
	/**
	 *  Меняет позиции двух записей
	 * @param int $nId1
	 * @param int $nPos1
	 * @param int $nId2
	 * @param int $nPos2
	 */
	private function _swapRecords(int $nId1, int $nPos1, int $nId2, int $nPos2)
	{
		//$sql = 'UPDATE ' . $this->table . ' SET delta = ' . $nPos2 . ' WHERE id = ' . $nId1;
		$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnTasks')->createQueryBuilder('t');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->update();
		$oQueryBuilder->set('t.delta', $nPos2);
		$oQueryBuilder->where( $e->eq('t.id', $nId1) );
		$oQueryBuilder->getQuery()->execute();
		//$sql = 'UPDATE ' . $this->table . ' SET delta = ' . $nPos1 . ' WHERE id = ' . $nId2;
		$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnTasks')->createQueryBuilder('t');
		$oQueryBuilder->update();
		$oQueryBuilder->set('t.delta', $nPos1);
		$oQueryBuilder->where( $e->eq('t.id', $nId2) );
		$oQueryBuilder->getQuery()->execute();
	}
	/**
	 *
	 * @return array of integer  
	*/
	private function _loadUserTagsId() : array
	{
		//SELECT tag_id FROM user_tags AS ut WHERE uid = UID;
		$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnUserTags')->createQueryBuilder('t');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->select('t.tagId');
		$oQueryBuilder->where( $e->eq('t.ausersId', $this->getUserId()) );
		$a = $oQueryBuilder->getQuery()->getResult();
		return array_column($a, 'tagId');
	}
	/**
	 *
	 * @param array $aTaskIdData
	 * @param array $aUserTagsIdData
	 * @return  array of integer
	*/
	private function _loadTaskIdByTaskIdAndTagId(array $aTaskIdData, array $aUserTagsIdData) : array
	{
		//SELECT task_id FROM task_tags WHERE task_id IN (USER_TASK_ID_LIST) AND  tag_id IN (TAGS_ID_LIST);
		$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnTaskTags')
			->createQueryBuilder('t');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->select('DISTINCT t.taskId');
		$oQueryBuilder->where( $e->in('t.taskId', $aTaskIdData) );
		$oQueryBuilder->andWhere( $e->in('t.tagId', $aUserTagsIdData) );
		$a = array_column( $oQueryBuilder->getQuery()->getResult(), 'taskId' );
		return $a;
	}
}
