<?php

namespace App\Controller;

# use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\RegisterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\Ausers AS User;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SecurityController extends AbstractController
{
	/**
	 * @Route("/login", name="login")
	 */
	public function loginAction(AuthenticationUtils $authenticationUtils, CsrfTokenManagerInterface $oCsrfTokenManager)
	{
		// сообщение об ошибке аутентификации
		$error = $authenticationUtils->getLastAuthenticationError();
		// имя пользователя, которое пытались ввести
		$lastUsername = $authenticationUtils->getLastUsername();

		$aData = $this->_getDefaultViewData();



		$csrfToken = $oCsrfTokenManager
			? $oCsrfTokenManager->getToken('authenticate')->getValue()
			: null;

		$aData['controller_name'] = 'SecurityController';
		$aData['last_username'] = $lastUsername;
		$aData['error']         = $error;
		$aData['isAuthform']    = 1;
		$aData['csrf_token']    = $csrfToken;
		$aData['sFormBgImageCss']    = 'bg-login-image';
		return $this->render('security/login.html.twig', $aData);
	}

	/**
	 * @Route("/register", name="register")
	*/
	public function register(Request $oRequest, UserPasswordEncoderInterface $oEncoder, TranslatorInterface $t)
	{
		$oUser = new User();
		$oForm = $this->createForm(get_class(new RegisterFormType()), $oUser);
		if ($oRequest->getMethod() == 'POST') {
			$oForm->handleRequest($oRequest);
			if ($oForm->isValid()) {
				$sPassword = $oForm->get('passwordRaw')->getData();
				$sPassword2 = $oForm->get('passwordRepeat')->getData();
				$sUsername = $oForm->get('username')->getData();
				$oRepository = $this->getDoctrine()->getRepository('App:Ausers');
				//TODO add email check
				$oExistsUser = $oRepository->findBy(['username' => $sUsername]);
				if ($oExistsUser) {
					$this->addFlash('notice', $t->trans('User with login already exists'));
					return $this->redirectToRoute('login');
				}

				if ($sPassword != $sPassword2) {
					$this->addFlash('notice', 'Passwords is different!');
					return $this->redirectToRoute('register');
				}
				$sPassword = $oEncoder->encodePassword($oUser, $sPassword);
				$oUser->setPassword($sPassword);
				$oEm = $this->getDoctrine()->getManager();
				$oEm->persist($oUser);
				$oEm->flush();
				return $this->redirectToRoute('login');
			} else {
				$this->addFlash('notice', $t->trans('some error...'));
				return $this->redirectToRoute('register');
			}
		}
		$aData = $this->_getDefaultViewData();
		$aData['form'] = $oForm->createView();
		$aData['sFormBgImageCss'] = 'bg-register-image';
		return $this->render('security/register.html.twig', $aData);
	}

	/**
	* @Route("/login_check", name="check_path")
	*/
	public function check()
	{
		return $this->redirectToRoute('login');
	}
	/**
	 *
	*/
	private function _getDefaultViewData() : array
	{
		return [
			'formHeading' => '',
			'isResetform' => 0,
			'isAuthform' => 0,
			'sFormBgImageCss' => '',
			'' => ''
		];
	}

	/**
	 * TODO
	 * @Route("/reset", name="reset")
	*/
	public function reset()
	{

	}
}