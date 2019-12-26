<?php

namespace App\Controller;

# use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\Ausers AS User;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
	/**
	 * @Route("/login", name="login")
	 */
	public function loginAction(AuthenticationUtils $authenticationUtils)
	{
		// сообщение об ошибке аутентификации
		$error = $authenticationUtils->getLastAuthenticationError();
		// имя пользователя, которое пытались ввести
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', [
			'controller_name' => 'SecurityController',
			'last_username' => $lastUsername,
			'error'         => $error,
		]);
	}

	/**
	 * @Route("/register", name="register")
	*/
	public function register(Request $oRequest, UserPasswordEncoderInterface $oEncoder, TranslatorInterface $t)
	{
		if ($oRequest->getMethod() == 'POST') {
			$sPassword = $oRequest->get('password');
			$sPassword2 = $oRequest->get('password2');
			$sEmail = $oRequest->get('email');
			$sUsername = $oRequest->get('login');

			$oRepository = $this->getDoctrine()->getRepository('App:Ausers');
			$oExistsUser = $oRepository->findBy(['username' => $sUsername]);
			if ($oExistsUser) {
				$this->addFlash('notice', $t->trans('User with login already exists'));
				return $this->redirectToRoute('login');
			}

			if ($sPassword != $sPassword2) {
				$this->addFlash('notice', 'Passwords is different!');
				return $this->redirectToRoute('register');
			}
			$oUser = new User();
			$sPassword = $oEncoder->encodePassword($oUser, $sPassword);

			$oUser->setPassword($sPassword);
			$oUser->setEmail($sEmail);
			$oUser->setUsername($sUsername);

			$oEm = $this->getDoctrine()->getManager();
			$oEm->persist($oUser);
			$oEm->flush();
			return $this->redirectToRoute('login');
		}
		return $this->render('security/register.html.twig', [
			'controller_name' => 'SecurityController',
		]);
	}

	/**
	* @Route("/login_check", name="check_path")
	*/
	public function check()
	{
		return $this->redirectToRoute('login');
	}
}
