<?php

namespace App\Controller;

use App\Entity\Ausers;
use App\Entity\UserMedia;
use App\Form\PhdAdminUploaderFormType;
use App\Form\ProfileType;
use App\Service\AppService;
use App\Service\FileUploaderService;
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
			'avatarImgSrc' => $oAppService->getUserAvatarImageSrc(),
			'pageHeading' => $t->trans('Profile settings')
		];
        return $this->render('profile/articles.html.twig', $aData);
    }
    /**
     * @Route("/saveprofiledata", name="saveprofiledata")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
    */
    public function saveprofiledata(Request $oRequest, TranslatorInterface $t, AppService $oAppService, UserPasswordEncoderInterface $oEncoder, FileUploaderService $oFileUploaderService)
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
					$sNew = trim($oForm['newPassword']->getData());
					if ( $sNew && $oEncoder->isPasswordValid($oUser, $sRawActualPassword) ) {
						$sRepeat = $oForm['repeatPassword']->getData();
						if ($sNew == $sRepeat) {
							$sNewHash = $oEncoder->encodePassword($oUser, $sNew );
							$oUser->setPassword($sNewHash);
							$this->addFlash('success', $t->trans('Password updated'));
						} else {
							$this->addFlash('notice', $t->trans('Passwords is different'));
						} 
					} else {
						if (!$sNew) {
							$this->addFlash('notice', $t->trans('Empty password'));
						} else {
							$this->addFlash('notice', $t->trans('Invalid password'));
						}
					}
				}
				//Обновляем логотип
				$oFile = $oForm['useravatar']->getData();
				if ($oFile) {
					$sFileName = $oFileUploaderService->upload($oFile);
					$s = '/' . $this->_subdir . '/' . $sFileName;
					//Получить хэш текущего логотипа пользователя
					//Сравнить его с хэшем загруженного
					//если они различны, добавить в UserMedia и обновить logoid
					if ($this->_isAvatarChanged($s)) {
						$oUserMedia = new UserMedia();
						$oUserMedia->setDateCreate($oAppService->now());
						$oUserMedia->setPath($s);
						$oUserMedia->setName(($oRequest->files->get('profile')['useravatar'] ? $oRequest->files->get('profile')['useravatar']->getClientOriginalName() : '') );
						$oAppService->save($oUserMedia);
						/** @var Ausers $oUser */
						$oUser->setLogotypeId($oUserMedia->getId());
					}
				}
				//Сохранение
				$oAppService->save($oUser);
				$this->addFlash('success', $t->trans('Data saved'));
			} else {
				$aData = [
					'form' => $oForm->createView(),
					'avatarImgSrc' => $oAppService->getUserAvatarImageSrc(),
					'pageHeading' => $t->trans('Profile settings')
				];
				return $this->render('profile/articles.html.twig', $aData);
			}
		}
		return $this->redirectToRoute('profile');
    }
    /**
     * Получить хэш текущего логотипа пользователя
	 * Сравнить его с хэшем загруженного
	 * если они различны, вернёт true
     * @param string $s
     * @return bool true если ватарка изменилась
    */
    private function _isAvatarChanged(string $s) : bool
    {
    	$oAppService = $this->_oAppService;
    	$oRequest = $oAppService->request();
		$sPath = $oRequest->server->get('DOCUMENT_ROOT') . $s;
		//Нового аватара не существует
		if (!file_exists($sPath)) {
			return false;
		}
		$oUser = $this->getUser();
		//Новый аватар существует, а старого не было
		$nOldAvatarId = $oUser->getLogotypeId();
		if (!$nOldAvatarId) {
			return true;
		}
		$oldAvatar = $oAppService->repository('App:UserMedia')->find($nOldAvatarId);
		//Новый аватар существует, а старый не найден
		if (!$oldAvatar) {
			return true;
		}
		$sOldAvatarPath = $oRequest->server->get('DOCUMENT_ROOT') . $oldAvatar->getPath();
		//Старый аватар не существует, а новый существует
		if (!file_exists($sOldAvatarPath)) {
			return true;
		}
		$sNewHash = md5_file($sPath);
		$sOldHash = md5_file($sOldAvatarPath);
		//Новый аватар аналогичен старому
		if ($sNewHash == $sOldHash) {
			return false;
		}
		//Новый аватар отличается от старого
		return true;
    }
}
