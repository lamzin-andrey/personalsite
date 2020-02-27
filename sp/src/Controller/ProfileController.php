<?php

namespace App\Controller;

use App\Form\PhdAdminUploaderFormType;
use App\Form\ProfileType;
use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfileController  extends AppBaseController
{
    /**
     * @Route("/profile", name="profile")
    */
    public function index(AppService $oAppService,TranslatorInterface $t)
    {
    	$this->_oAppService = $oAppService;
		$this->_subdir = 'd/' . date('Y/m');
		$oUser = $this->getUser();
		$oForm = $this->createForm(get_class(new ProfileType()), $oUser, [
			'app_service' => $oAppService,
			'uploaddir' => $this->_subdir
		]);
		$aData = [
			'form' => $oForm->createView(),
			'pageHeading' => $t->trans('Profile settings')
		];
        return $this->render('profile/index.html.twig', $aData);
    }
    /**
     * @Route("/saveprofiledata", name="saveprofiledata")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
    */
    public function saveprofiledata(Request $oRequest, TranslatorInterface $t, AppService $oAppService, UserPasswordEncoderInterface $oEncoder)
    {
		$this->_oAppService = $oAppService;
		$this->_subdir = 'd/' . date('Y/m');
		$oUser = $this->getUser();
		$oForm = $this->createForm(get_class(new ProfileType()), $oUser, [
			'app_service' => $oAppService,
			'uploaddir' => $this->_subdir
		]);
		if ($oRequest->getMethod() == 'POST') {
			$oForm->handleRequest($oRequest);
			if ($oForm->isSubmitted() && $oForm->isValid()) {
				//Обновляем пароль
				$sRawActualPassword = trim($oForm['currentPassword']->getData());
				if ($sRawActualPassword ) {
					if ( $oEncoder->isPasswordValid($oUser, $sRawActualPassword) ) {

					} else {
						$this->addFlash('Notice', $t->trans('Invalid password'));
					}
				}
				//Сохранение
				$oAppService->save($oUser);
				$this->addFlash('success', $t->trans('Data saved'));
			}
		}
		return $this->redirectToRoute('profile');
    }
}
