<?php

namespace App\Controller;

use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\KxmQuestFormType;

class KxmController extends AppBaseController
{
    /**
     * @property KxmQuest $_oKxmQuest вопрос викторины
    */
    private $_oKxmQuest = null;
    /**
     * @Route("/kxm", name="kxm")
     */
    public function index(AppService $oAppService)
    {
        $oForm = $this->_createForm();
        return $this->render('kxm/index.html.twig', [
            'controller_name' => 'KxmController',
            'formtoken' => $oAppService->getFormTokenValue($oForm)
        ]);
    }

    /**
     *
     * @param $
     * @return
    */
    private function _createForm() : FormInterface
    {
        $this->_oForm = $this->createForm(KxmQuestFormType::class, $this->_oKxmQuest);
        return $this->_oForm;
    }
}
