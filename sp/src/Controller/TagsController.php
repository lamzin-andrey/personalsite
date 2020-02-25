<?php

namespace App\Controller;

use App\Service\AppService;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class TagsController extends AppBaseController
{

	/**
	 * @Route("/tasks/setusertags.json", name="setusertags")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param AppService $oAppService
	 * @return Response
	*/
	public function setusertags(Request $oRequest, TranslatorInterface $t, AppService $oAppService) : Response
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
		$this->_saveTags( $oRequest->get('tags', '') );
		return $this->_json([]);
	}
    /**
     * @Route("/tags.json", name="tags")
    */
    public function index(Request $oRequest, AppService $oAppService, TranslatorInterface $t)
    {
    	$this->_oAppService = $oAppService;
    	$aData = [
    		'tags' => $this->_getTags($oRequest->get('s')),
			'status' => 'ok'
		];
        return $this->_json($aData);
    }
    /**
	 * @param string $s Подстрока для поискаs
     * @return array [id => name]
    */
    private function _getTags(string $s) : array
    {
		$oRepository = $this->_oAppService->repository('App:CrnTags');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		$oCriteria->where( $e->contains('name', $s) );
		$aRaw = $oRepository->matching($oCriteria)->toArray();
		$a = [];
		foreach ($aRaw as $oTag) {
			$a[$oTag->getId()] = $oTag->getName();
		}
		return $a;
    }
	/**
	 * Сохраняет в crn_task_tags связи с тегами
	 * @param string $jsonData
	 */
	private function _saveTags(string $jsonData) : void
	{	//Тут не смог отказать себе в удовольствии использовать ON DUPLICATE

		$nUserId = $this->getUserId();
		$aData = json_decode($jsonData);

		//Удалить из базы все, которые не входят в полученные в запросе id
		$oEm = $this->getDoctrine()->getManager();
		if (count($aData)) {
			$oEm->getConnection()->prepare('DELETE FROM crn_user_tags 
				WHERE ausers_id = ' . $nUserId . '
				AND tag_id NOT IN(' . join(',', array_column($aData, 'id') ) . ')
			')->execute();
		} else {
			$oEm->getConnection()->prepare('DELETE FROM crn_user_tags 
				WHERE ausers_id = ' . $nUserId)->execute();
		}
		//вcтaвить только те, которых нет.
		foreach ($aData as $n => $oTag) {
			$oEm->getConnection()->prepare('INSERT INTO crn_user_tags SET 
				ausers_id = ' . $nUserId . ',
				tag_id = ' . $oTag->id . ' 
				ON DUPLICATE KEY UPDATE
				ausers_id = ' . $nUserId)->execute();
		}
	}

}
