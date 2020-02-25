<?php

namespace App\Controller;

use App\Form\TaskManagerType;
use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TranslatorInterface $t, AppService $oAppService)
    {
    	$this->_oAppService = $oAppService;
    	if (!$this->getUser()) {
    		return $this->redirectToRoute('login');
		}
		$oForm = $this->createForm(TaskManagerType::class);
    	$aData = [
    		'pageHeading' => $t->trans('Add task', [], 'tasks'),
			'formtoken' => $oForm->createView()->children['_token']->vars['value'],
			'searchtags' => $this->_getSearchTagsData()
		];
        return $this->render('default/index.html.twig', $aData);
    }
    /**
     * Получить тэги пользователя в формате JSON
     * @return  string
    */
    private function _getSearchTagsData() : string
    {
    	/** @var \App\Service\AppService $oAppService */
		$oAppService = $this->_oAppService;
		$oQueryBuilder = $this->getDoctrine()->getRepository('App:CrnUserTags')->createQueryBuilder('ut');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->where( $e->eq('ut.ausersId', $this->getUser()->getId()) );
		$oQueryBuilder->select('ct.id, ct.name');
		$oQueryBuilder->leftJoin('App:CrnTags', 'ct', 'WITH', 'ct.id = ut.tagId');
		$a = $oQueryBuilder->getQuery()->execute();
		return json_encode($a);
    }
}
