<?php

namespace App\Controller;

# use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Form\RegisterFormType;
use App\Service\AppService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\Ausers AS User;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Doctrine\Common\Collections\Criteria;
use App\Form\ResetPasswordFormType;

use Landlib\SimpleMail;


class SecurityController extends AppBaseController
{
	/**
	 * @Route("/login", name="login")
	 */
	public function loginAction(TranslatorInterface $t, AuthenticationUtils $authenticationUtils, CsrfTokenManagerInterface $oCsrfTokenManager, AppService $oAppService)
	{
		$this->_oAppService = $oAppService;
		if ($this->getUser()) {
			return $this->redirectToRoute('home');
		}
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
		$aData['bEnableChooseSiteVersionButton'] = true;
		$aData['pageHeading'] = $t->trans('Reset-Password');
		$aData['pageHeading'] = $t->trans('Login', [], 'loginforms');
		return $this->render('security/login.html.twig', $aData);
	}

	/**
	 * @Route("/register", name="register")
	*/
	public function register(Request $oRequest, UserPasswordEncoderInterface $oEncoder, TranslatorInterface $t, AppService $oAppService)
	{
		$this->_oAppService = $oAppService;
		$oUser = new User();
		$this->_oForm = $oForm = $this->createForm(get_class(new RegisterFormType()), $oUser);
		$this->translator = $t;
		if ($oRequest->getMethod() == 'POST') {
			$oForm->handleRequest($oRequest);
			if ($oForm->isValid()) {
				$sPassword = $oForm->get('passwordRaw')->getData();
				$sPassword2 = $oForm->get('passwordRepeat')->getData();
				$sEmail = $oForm->get('email')->getData();
				$sUsername = $oForm->get('username')->getData();
				$oRepository = $this->getDoctrine()->getRepository('App:Ausers');
				$oCriteria = Criteria::create();
				$oExpr = Criteria::expr();
				$oCriteria->where(
					$oExpr->orX(
						$oExpr->eq('username', $sUsername),
						$oExpr->eq('email', $sEmail)
					)
				);
				//$oExistsUser = $oRepository->findBy(['username' => $sUsername]);
				$oExistsUser = $oRepository->matching($oCriteria)->get(0);
				if ($oExistsUser) {
					//$this->addFlash('notice', $t->trans('User with login or email already exists'));
					$this->addFormError('User with login or email already exists', 'username');
					//return $this->redirectToRoute('login');
				} else if ($sPassword != $sPassword2) {
					$this->addFormError('Passwords is different', 'passwordRaw');
				} else {
					//Success
					$sPassword = $oEncoder->encodePassword($oUser, $sPassword);
					$oUser->setPassword($sPassword);
					$oEm = $this->getDoctrine()->getManager();
					$oEm->persist($oUser);
					$oEm->flush();
					return $this->redirectToRoute('login');
				}

			}
		}
		$aData = $this->_getDefaultViewData();
		$aData['form'] = $oForm->createView();
		$aData['sFormBgImageCss'] = 'bg-register-image';
		$aData['bEnableChooseSiteVersionButton'] = true;
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
	 * @Route("/reset", name="reset")
	*/
	public function reset(Request $oRequest, TranslatorInterface $t, UserPasswordEncoderInterface $oEncoder, AppService $oAppService)
	{
		$this->_oAppService = $oAppService;
		$this->_oForm = $oForm = $this->createForm(get_class(new ResetPasswordFormType()));
		$this->translator = $t;
		$aData = $this->_getDefaultViewData();
		$aData['sFormBgImageCss'] = 'bg-password-image';
		$aData['isEmailWasFound'] = 0;
		$aData['emailHostLink'] = '#';
		$aData['isResetform'] = 0;
		$aData['bEnableChooseSiteVersionButton'] = true;
		$aData['form'] = $oForm->createView();
		if ($oRequest->getMethod() == 'POST') {
			$oForm->handleRequest($oRequest);
			if ($oForm->isValid()) {
				$sEmail = $oForm->get('email')->getData();
				$oUserRepository = $this->getDoctrine()->getRepository('App:Ausers');
				$oUser = $oUserRepository->findOneBy([
					'email' => $sEmail
				]);
				if ($oUser) {
					$sPasswordRaw = $this->_generatePassword();
					$sEncodePassword = $oEncoder->encodePassword($oUser, $sPasswordRaw);
					$oUser->setPassword($sEncodePassword);
					$oEm = $this->getDoctrine()->getManager();
					$oEm->persist($oUser);
					$oEm->flush();

					$siteAdminEmail = $this->getParameter('app.siteAdminEmail');
					$subject = 'Восстановление пароля в сервисе контроля времени';
					$sHtml = '<p>Ваш забытый пароль ' . $sPasswordRaw . '</p>';
					$oMessage = new SimpleMail();
					$oMessage->setSubject($subject);
					$oMessage->setFrom($siteAdminEmail);
					$oMessage->setTo($sEmail);
					$oMessage->setBody($sHtml, 'text/html', 'UTF-8');
					$bSuccess = $oMessage->send();
					if ($bSuccess) {
						$aData['isEmailWasFound'] = 1;
						$a = explode('@', $sEmail);
						$aData['emailHostLink'] = 'https://' . $a[1];
					} else {
						$this->addFlash('notice', $t->trans('Unable send email'));
					}

				} else {
					$this->addFlash('notice', $t->trans('User with email %email% not found', null, ['%email%' => $sEmail]));
				}
			}
		}
		return $this->render('security/reset.html.twig', $aData);
	}
	/**
	 * @param string $sError
	 * @param string $sField
	 * @param FormInterface $oForm
	 **/
	public function addFormError(string $sError, string $sField, ?FormInterface $oForm = null)
	{
		$oError = new \Symfony\Component\Form\FormError($this->translator->trans($sError));
		$this->_oForm->get($sField)->addError($oError);
	}
	/**
	 * Генерирует пароль для пользователя
	 * @param int $length длина пароля
	 * @return string
	 */
	private function _generatePassword(int $length = 8) : string
	{

		$chars = 'abcdefghijklmnopqrstuvwxyz';
		$chars .= strtoupper($chars);
		$chars .= '1234567890';

		$sPattern =  "/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])/s";

		while (true) {
			$L = 0;
			$rate = [];//{}
			$str = '';
			$limit = strlen($chars) - 1;
			while ($L < $length) {
				$ch = $chars[ rand(0, $limit) ];
				if (!isset($rate[$ch]) || $rate[$ch] < 1) {
					$str .= $ch;
					$rate[$ch] = isset($rate[$ch]) ? $rate[$ch] + 1 : 1;
					$L++;
				}
			}
			if (preg_match($sPattern, $str)) {
				break;
			}
		}

		return $str;
	}
}
