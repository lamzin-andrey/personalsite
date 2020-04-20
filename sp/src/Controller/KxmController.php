<?php

namespace App\Controller;

use App\Service\AppService;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\KxmQuestFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class KxmController extends AppBaseController
{
    /**
     * @property KxmQuest $_oKxmQuest вопрос викторины
    */
    private $_oKxmQuest = null;

    /**
     * @Route("/kxm", name="kxm")
     */
    public function index(AppService $oAppService, TranslatorInterface $t)
    {
        $oForm = $this->_createForm();
        return $this->render('kxm/index.html.twig', [
            'controller_name' => 'KxmController',
            'formtoken' => $oAppService->getFormTokenValue($oForm),
            'sTokenPrefix' => $oForm->getName(),
            'pageHeading' => $t->trans('Quests managment', [], 'victorina')
        ]);
    }

    /**
     * @Route("kxmquestsave.json", name="kxmquestsavejson")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param AppService $oAppService
     * @return Response
    */
    public function savequestjson(Request $oRequest, TranslatorInterface $t, AppService $oAppService) : Response
    {
        $this->_oAppService = $oAppService;
        $aResult = [];
        if (!$this->_accessControl()) {
            $aResult['msg'] = $t->trans('You have not access to this question');
            $aResult['status'] = 'error';
            return $this->_json($aResult);
        }
        if ($oRequest->getMethod() == 'POST') {
            $oExistsQuest = $oAppService->repository('App:KxmQuest')->find($oRequest->get(KxmQuestFormType::$prefix)['id']);
            if ($oExistsQuest) {
                $this->_oKxmQuest = $oExistsQuest;
            }
            $oForm = $this->_createForm();
            $oForm->handleRequest($oRequest);
            if ($oForm->isValid()) {
                if (!$this->_oKxmQuest) {
                    $this->_oKxmQuest = $oForm->getData();
                }
                $oAppService->save($this->_oKxmQuest);
                return $this->_json([
                    'status' => 'ok',
                    'id' => $this->_oKxmQuest->getId()
                ]);
            } else {
                return $this->_json([
                    'status' => 'error',
                    'errors' => $oAppService->getFormErrorsAsArray($oForm),
                    'token' => $oAppService->getFormTokenValue($oForm)
                ]);
            }
        }
        return $this->_json(['status' => 'error', 'msg' => 'method is not POST']);
    }

    /**
     * @Route("kxmlist.json", name="kxmlistjson")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param AppService $oAppService
     * @return Response
    */
    public function kxmlistjson(Request $oRequest, TranslatorInterface $t, AppService $oAppService) : Response
    {
        $this->_oAppService = $oAppService;
        $aResult = [];
        if (!$this->_accessControl()) {
            $aResult['msg'] = $t->trans('You have not access to this task');
            $aResult['status'] = 'error';
            return $this->_json($aResult);
        }

        $aData = $this->_loadData($oAppService, $oRequest);
        $nSz = count($aData);
        $aResult = [
            'recordsTotal' => $this->_getTotalRecords($oAppService),
            'recordsFiltered' => $this->_getTotalRecords($oAppService),
            'data' => $aData,
            'draw' => $oRequest->get('draw'),
            'status' => 'ok'
        ];
        return $this->_json($aResult);
    }

    /**
     * @Route("/kxm/quest.json", name="getquest")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param AppService $oAppService
     * @return Response
    */
    public function getquest(Request $oRequest, TranslatorInterface $t, AppService $oAppService) : Response
    {
        $this->_oAppService = $oAppService;
        $aResult = [];
        if (!$this->_accessControl()) {
            $aResult['msg'] = $t->trans('You have not access to this task');
            $aResult['status'] = 'error';
            return $this->_json($aResult);
        }
        $oQuest = $this->getDoctrine()->getRepository('App:KxmQuest')
            ->find($oRequest->get('id'));
        if ($oQuest) {
            return $this->_json([
                'status' => 'ok',
                'quest' => [
                    'id' => $oQuest->getId(),
                    'body' => $oQuest->getBody(),
                    'var1' => $oQuest->getVar1(),
                    'var2' => $oQuest->getVar2(),
                    'var3' => $oQuest->getVar3(),
                    'var4' => $oQuest->getVar4(),
                    'var_right' => $oQuest->getVarRight(),
                    'price' => $oQuest->getPrice(),
                ]
            ]);
        }
        return $this->_json([
            'status' => 'error',
            'msg' => 'Quest not found'
        ]);
    }
    /**
     * Создать объект формы
     * @return
    */
    private function _createForm() : FormInterface
    {
        $this->_oForm = $this->createForm(KxmQuestFormType::class, $this->_oKxmQuest);
        return $this->_oForm;
    }
    /**
	 * 
	 * @param AppService $oAppService
	 * @param Request $oRequest
	 * @return array
	*/
    private function _loadData(AppService $oAppService, Request $oRequest) : array
    {
        $oQueryBuilder = $this->getDoctrine()
            ->getRepository('App:KxmQuest')
            ->createQueryBuilder('k');
        $aData = $oQueryBuilder
            ->setMaxResults($oRequest->get('length', 0))
            ->setFirstResult($oRequest->get('start', 0))
            ->getQuery()
            ->getScalarResult();
        $aResult = [];
        foreach ($aData as $oItem) {
            $aItem = [];
            foreach ($oItem as $sName => $sValue) {
                $aItem[ preg_replace("#^k_#", '', $sName) ] = $sValue;
            }
            $aResult[] = $aItem;
        }
        return $aResult;
	}
	/**
	 * @return int Общее количество записей
	*/
	private function _getTotalRecords() : int
	{
	    $oQueryBuilder = $this->getDoctrine()->getRepository('App:KxmQuest')->createQueryBuilder('k');
	    /** @var  QueryBuilder $oQueryBuilder */
	    $aData = $oQueryBuilder->select( 'COUNT(k.id) AS cnt' )
            ->getQuery()
            ->getSingleResult();
	    $nTotal = ($aData['cnt'] ?? 0);
	    return $nTotal;
	}
}
