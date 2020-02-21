<?php

namespace App\Controller;

use App\Entity\CrnTasks;
use App\Form\TaskManagerType;
use App\Service\AppService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class TasksCronController extends AbstractController
{
	/** @property AppService $_oAppService */
	private $_oAppService;


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
			'data' => $this->_getTaskList( $oRequest->get('search')['value'] )
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
			if ($nId = intval($aFormData['id'])) {
				$oRepository = $this->_oAppService->repository('App:CrnTasks');
				$oTask = $oRepository->find($nId);
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
				$oTask->setDelta( $oTask->getId() );
				$oAppService->save($oTask);
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

    private function _json(array $aData)
	{
    	return $this->_oAppService->json($aData);
	}
	/**
	 *
	 * @return bool true если пользователь авторизован
	*/
	private function _accessControl() : bool
	{
		if (!$this->getUser()) {
			return false;
		}
		return true;
	}
	/**
	 * Сохраняет в crn_task_tags связи с тегами
	 * @param string $jsonData
	 * @param int $nTaskId
	*/
	private function _saveTags(string $jsonData, int $nTaskId) : void
	{

		//Тут не смог отказать себе в удовольствии использовать ON DUPLICATE

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
	 * @param string $s Подстрока для поискаs
	 * @return array [id => name]
	*/
	private function _getTaskList(string $s) : array
	{
		//TODO per page
		$oRepository = $this->_oAppService->repository('App:CrnTasks');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		$oCriteria->where( $e->orX( $e->contains('name', $s),  $e->contains('codename', $s)) );
		$oCriteria->orderBy(['delta' => 'ASC']);
		$aRaw = $oRepository->matching($oCriteria)->toArray();
		$a = [];
		//TODO try serialize array
		$encoders = [new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$oSerializer = new Serializer($normalizers, $encoders);
		foreach ($aRaw as $oTag) {
			$a[] = json_decode($oSerializer->serialize($oTag, 'json'));
		}
		return $a;
	}

	/**
	 * Запрос для получения количества отфильтррованных записей
	 * @param string $s Подстрока для поискаs
	 * @return array [id => name]
	 */
	private function _getTaskListFiltredCount(string $s) : int
	{
		$oRepository = $this->_oAppService->repository('App:CrnTasks');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		$oCriteria->where( $e->orX( $e->contains('name', $s),  $e->contains('codename', $s)) );
		$aRaw = $oRepository->matching($oCriteria)->count();
		return $aRaw;
	}

	/**
	 * Запрос для получения количества отфильтррованных записей
	 * @param string $s Подстрока для поискаs
	 * @return array [id => name]
	 */
	private function _getTaskListCount() : int
	{
		$oRepository = $this->_oAppService->repository('App:CrnTasks');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		//$oCriteria->where( $e->orX( $e->contains('name', $s),  $e->contains('codename', $s)) );
		$aRaw = $oRepository->matching($oCriteria)->count();
		return $aRaw;
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

}
