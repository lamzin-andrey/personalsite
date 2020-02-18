<?php

namespace App\Controller;

use App\Entity\CrnTasks;
use App\Form\TaskManagerType;
use App\Service\AppService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class TasksCronController extends AbstractController
{
	/** @property AppService $_oAppService */
	private $_oAppService;
    /**
     * @Route("/tasks.json", name="tasks_list")
    */
    public function index(Request $oRequest, AppService $oAppService)
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
				$oAppService->save($oTask);
				$this->_saveTags(($aFormData['tags'] ?? ''), $oTask->getId());
				$aResult['id'] = $oTask->getId();
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
}
