<?php

namespace App\Controller;

# use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Ausers;
use App\Form\ChangeUserFormType;
use App\Form\RegisterFormType;
use App\Handler\AuthenticationHandler;
use App\Service\AppService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\Ausers AS User;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Doctrine\Common\Collections\Criteria;
use App\Form\ResetPasswordFormType;

use Landlib\SimpleMail;


class SecurityController extends AppBaseController
{

    const MAX_ALLOW_WUSB_USERS = 20;

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
    public function register(
        Request $oRequest,
        UserPasswordEncoderInterface $oEncoder,
        TranslatorInterface $t,
        AppService $oAppService,
        UserService $userService
    )
    {
        $this->_oAppService = $oAppService;
        $oUser = new User();
        $this->_oForm = $oForm = $this->createForm(get_class(new RegisterFormType()), $oUser);
        $this->translator = $t;
        $agree = $oRequest->request->get('register_form')['agree'] ?? null;
        $acceptAllCookies = $oRequest->request->get('register_form')['agreeCC'] ?? null;
        $acceptAdv = $oRequest->request->get('register_form')['agreeAdv'] ?? null;
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
                $this->request = $oRequest;
                if ($oExistsUser) {
                    //$this->addFlash('notice', $t->trans('User with login or email already exists'));
                    $this->addFormError('User with login or email already exists', 'username', $oAppService);
                    //return $this->redirectToRoute('login');
                } else if ($sPassword != $sPassword2) {
                    $this->addFormError('Passwords is different', 'passwordRaw', $oAppService);
                } else if ('true' !== $agree) {
                    $this->addFormError('Consent to agree to terms of use', 'agree', $oAppService);
                } else if ('true' !== $acceptAllCookies) {
                    $this->addFormError('You must accept all cookies for use this site', 'agreeCC', $oAppService);
                } else if ('true' !== $acceptAdv) {
                    $this->addFormError('You must accept advertising on this site', 'agreeAdv', $oAppService);
                } else if (!$this->isRussianEmail($sEmail, $allowEmails)) {
                    $this->addFormError('email_must_be_russian' , 'email', $oAppService, null, [
                        '%list%' => implode(",\n",  $allowEmails)
                    ]);
                } else if ($this->isMaxUsersRegistred()) {
                    $this->addFormError('max_quantity_users_registred' , 'email', $oAppService, null, []);
                }
                else {
                    //Success
                    $oUser->setIsAcceptAllCookies(true);
                    $oUser->setIsAcceptAllCookiesTime(new \DateTime());
                    $oUser->setAdvAgree(1);
                    $this->finalizeCreateNewUser($userService, $oUser, $sPassword);

                    if (!$oRequest->isXmlHttpRequest()) {
                        return $this->redirectToRoute('login');
                    } else {
                        return $this->_json(['success' => true]);
                    }
                }

            }
        }
        $aData = $this->_getDefaultViewData();
        $aData['form'] = $oForm->createView();
        $aData['sFormBgImageCss'] = 'bg-register-image';
        $aData['bEnableChooseSiteVersionButton'] = true;


        if (!$oRequest->isXmlHttpRequest()) {
            return $this->render('security/register.html.twig', $aData);
        } else {
            $errors = $oAppService->getFormErrorsAsArray($oForm);
            return $this->_json(['success' => false, 'errors' => $errors]);
        }

    }

    /*
	 * @desc Проверяем, относится ли email к одному из российских почтовых сервисов?
	*/
    private function isRussianEmail($email, &$allow = null)
    {
        $domain = explode('@', strtolower($email))[1];
        $allowList = [
            'mail.ru',
            'list.ru',
            'internet.ru',
            'inbox.ru',
            'bk.ru',
            'yandex.ru',
            'ya.ru',
            'narod.ru',
            'autorambler.ru',
            'myrambler.ru',
            'rambler.ru',
            'rambler.ua',
            'ro.ru',
        ];
        $allow = $allowList;

        $allowList[] = 'qwe.ru';

        if (in_array($domain, $allowList)) {
            return true;
        }

        return false;
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
    public function reset(Request $oRequest,
                          TranslatorInterface $t,
                          UserPasswordEncoderInterface $oEncoder,
                          AppService $oAppService)
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
        $ajaxData = [];
        $csrfResetToken = $oAppService->getFormTokenValue($oForm);

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

                    $subject = $this->getRecoveryMailSubject($oUser, $oAppService);

                    // Ваш забытый пароль
                    $sHtml = '<p> ' .
                        $oAppService->l($oUser, 'Your login', 'loginforms')
                        . ' '. $oUser->getUsername() . '</p>';
                    $sHtml .= '<p> ' .
                        $oAppService->l($oUser, 'Your forgotten password', 'loginforms')
                        . ' '. $sPasswordRaw . '</p>';
                    $oMessage = new SimpleMail();
                    $oMessage->setSubject($subject);
                    $oMessage->setFrom($siteAdminEmail);
                    $oMessage->setTo($sEmail);
                    $oMessage->setBody($sHtml, 'text/html', 'UTF-8');
                    $bSuccess = $oMessage->send();
                    $ajaxData['success'] = false;
                    $ajaxData['token'] = $csrfResetToken;
                    if ($bSuccess) {
                        $aData['isEmailWasFound'] = 1;
                        $a = explode('@', $sEmail);

                        $ajaxData['emailHostLink'] = $aData['emailHostLink'] = 'https://' . $a[1];
                        $ajaxData['success'] = true;
                        $ajaxData['message'] = $oAppService->l($oUser, 'The new password has been successfully emailed to you', 'loginforms');
                    } else {
                        $message = $oAppService->l(null, 'Unable send email', 'loginforms');
                        $this->addFlash('notice', $message);
                        $ajaxData['message'] = $message;
                    }

                } else {
                    $message = $oAppService->l(null, 'User with email %email% not found', 'loginforms', ['%email%' => $sEmail]);
                    $this->addFlash('notice', $message);
                    $ajaxData['success'] = false;
                    $ajaxData['message'] = $message;
                    $ajaxData['token'] = $csrfResetToken;
                }
            } else {
                $ajaxData['success'] = false;
                $ajaxData['message'] = implode(';', $oAppService->getFormErrorsAsArray($oForm));
                $ajaxData['token'] = $csrfResetToken;
            }
        }

        if ($oRequest->isXmlHttpRequest()) {
            return $this->_json($ajaxData);
        }

        return $this->render('security/reset.html.twig', $aData);
    }
    /**
     * @param string $sError
     * @param string $sField
     * @param FormInterface $oForm
     **/
    public function addFormError(string $sError, string $sField, AppService $appService, ?FormInterface $oForm = null, array $params = [])
    {
        $message = $appService->l(null, $sError, null, $params);
        $oError = new \Symfony\Component\Form\FormError($message);
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

    /**
     * Устанавливает роль пользователя при необходимости
     * @return
    */
    protected function setRole(Request $request, User $user) : void
    {
        $appType = $request->request->get('apptype');
        switch ($appType) {
            case 'drv':
                $user->setRole(User::ROLE_WEB_DRIVE_USER);
                break;
        }
    }

    /**
     * Сохраняет guest_id пользователя при необходимости
     * @return
     */
    protected function setGuestId(Request $request, User $user) : void
    {
        $guestId = $this->request->cookies->get('guest_id');
        if ($guestId) {
            $user->setGuestId($guestId);
        }
    }

    /**
     * @param $
     * @return
    */
    protected function getRecoveryMailSubject(Ausers $user, AppService $appService) : string
    {
        $subject =  'Password recovery in the Time Control Service';
        if ($user->getRole() == Ausers::ROLE_WEB_DRIVE_USER) {
            $subject =  'Password recovery in the WebUSB service';
        }
        $subject = $appService->l($user, $subject, 'loginforms');

        return $subject;
    }

    /**
     * @Route("/changeuser", name="changeuser")
     */
    public function changeUserAction(Request $request, AppService $appService, UserService $userService) : Response
    {
        if (!$this->hasPermissions()) {
            return $this->redirectToRoute('home');
        }
        $aData = $this->_getDefaultViewData();
        $form = $this->createForm(ChangeUserFormType::class);
        $aData['form'] = $form->createView();
        $aData['last_username'] = '';
        $this->_oAppService = $appService;

        if ($request->getMethod() == 'POST') {
            $newUserId = $request->request->get('change_user_form')['user_id'];
            $newUser = $this->container->get('doctrine')->getRepository(Ausers::class)->find($newUserId);
            /***
             * @var Ausers $newUser
            */
            if ($newUser) {
                // 1 Добавить в aData хеш (для проверки просто userId)
                $recoveryHash = $appService->getHash($request);
                $newUser->setRecoveryHash($recoveryHash);
                $appService->save($newUser);
                $aData['hash'] = $recoveryHash;
                // 2 Добавить в aData форму авторизации с предзаполненными полями
                $aData['last_username'] = $newUser->getUsername();
            }
        }

        return $this->render('security/change_user.html.twig', $aData);
    }

    /**
     * @Route("/swid", name="getSwitchUserId")
     */
    public function getSwitchedUserData(Request $request, AppService $appService, UserService $userService) : Response
    {
        $newUserHash = $request->query->get('id');
        $newUser = $this->container->get('doctrine')->getRepository(Ausers::class)->findOneBy([
            'recoveryHash' => $newUserHash
        ]);
        /***
         * @var Ausers $newUser
         */
        if ($newUser) {
            // 1 Установить пользователю временный пароль
            // Сохраняем старый пароль пользователя
            $userService->storePassword($newUser);
            $newPassword = $appService->getHash($request);
            $userService->setPassword($newUser, $newPassword, true);
            // 2 Добавить в aData хеш (для проверки просто userId)
            $aData['password'] = $newPassword;
            $aData['csrf_token'] = $userService->getFormLoginTokenCsrf();
            return $appService->json($aData);
        }

        return $appService->json([]);
    }

    /**
     * @return bool true когда пользователь имеет роль админ
     */
    private function hasPermissions() : bool
    {
        $oUser = $this->getUser();
        if (!$oUser) {
            return false;
        }
        $nRole = $oUser->getRole();
        return (2 == $nRole);
    }

    /**
     * Сюда будем переходить по ссылке в письме
     * @Route("/loginmailink", name="loginforce")
     */
    public function forceLoginAction(
        AuthenticationHandler $authenticator,
        UserService $userService,
        Request $request,
        AppService $appService
    )
    {
        $hash = $request->query->get('hash');
        $user = $this->container->get('doctrine')->getRepository(Ausers::class)->findOneBy([
            'recoveryHash' => $hash
        ]);
        if (!$user) {
            return new JsonResponse([
                'error' => true,
                'message' => $appService->l(null, 'User not found', 'loginforms')
            ]);
        }
        $token = $userService->login($user);
        return $authenticator->onAuthenticationSuccess($request, $token);
    }

    /**
     * Если есть уже юзер с таким email сохраняем хеш
     * @Route("/checkmail", name="checkmail")
     */
    public function loginByMailAction(
        UserService $userService,
        AppService $appService,
        Request $request,
        AuthenticationHandler $authenticator
    )
    {
        $this->request = $request;
        $email = $request->request->get('emailRE');

        if (!$this->isRussianEmail($email, $allowEmails)) {
            $message = $appService->l(null, 'email_must_be_russian', null, [
                '%list%' => implode(",\n",  $allowEmails)
            ]);
            return new JsonResponse(['sended' => false, 'msg' => $message]);
        }

        $agreeCondition = $request->request->get('agreeRE');
        $agreeSubscribe = $request->request->get('isSubscribedRE');
        $acceptCookies = $request->request->get('agreeCC');
        $acceptAdv = $request->request->get('agreeAdvRE');
        $agreeCondition = ($agreeCondition === 'true');
        $agreeSubscribe = ($agreeSubscribe === 'true');
        $acceptCookies  = ($acceptCookies === 'true');
        $acceptAdv      = ($acceptAdv === 'true');

        if (!$agreeCondition) {
            $message = $appService->l(null, 'Consent to agree to terms of use', null, []);
            return new JsonResponse(['sended' => false, 'msg' => $message]);
        }

        if (!$acceptCookies) {
            $message = $appService->l(null, 'You must accept all cookies for use this site', null, []);
            return new JsonResponse(['sended' => false, 'msg' => $message]);
        }
        if (!$acceptAdv) {
            $message = $appService->l(null, 'You must accept advertising on this site', null, []);
            return new JsonResponse(['sended' => false, 'msg' => $message]);
        }


        $this->_oAppService = $appService;
        if ($this->isMaxUsersRegistred()) {
            $message = $appService->l(null, 'max_quantity_users_registred', null, []);
            return new JsonResponse(['sended' => false, 'msg' => $message]);
        }

        $repository = $appService->repository(Ausers::class);
        /**
         * @var Ausers $user
        */
        $user = $repository->findOneBy([
            'email' => $email
        ]);


        if ($user) {
            $hash = $appService->getHash($request, uniqid(rand(10000, 99999), true), 'sha1');
            $hash .= $appService->getHash($request, uniqid(rand(10000, 99999), true), 'sha1');
            $user->setRecoveryHash($hash);
            $date = new \DateTime();
            $date->add(new \DateInterval('P3D'));
            $user->setRecoveryHashCreated($date);
            $appService->save($user);
            $html = $this->getEmailLoginHtml($hash, $appService);
            $success = $appService->sendEmailFromSite($user->getEmail(), 'Quick login in web USB', $html, $user, 'loginforms');
            if ($success) {
                if (!$user->getIsSubscribed()) {
                    $user->setIsSubscribed($agreeSubscribe);
                    $appService->save($user);
                }
                return new JsonResponse(['sended' => true]);
            }
            return new JsonResponse(['sended' => false]);
        } else {
            $user = $userService->createRegisterByEmailUser($email, $agreeSubscribe, $acceptCookies, false);
            $password = $userService->generatePassword();
            $this->finalizeCreateNewUser($userService, $user, $password);
            $token = $userService->login($user);
            return $authenticator->onAuthenticationSuccess($request, $token);
        }
    }

    private function finalizeCreateNewUser(UserService $userService, Ausers $user, string $password)
    {
        $userService->setPassword($user, $password);
        $this->setRole($this->request, $user);
        $this->setGuestId($this->request, $user);
        $oEm = $this->getDoctrine()->getManager();
        $oEm->persist($user);
        $oEm->flush();
    }

    private function getEmailLoginHtml(string $hash, AppService $appService): string
    {
        $request = $appService->request();
        $protocol = 2 == $request->request->get("scheme") ? 'https' : 'http';
        $scheme = $protocol . '://';

        $os = ' ';
        $tDomain = 'loginforms';
        $user = $this->getUser();
        $site = $appService->getParameter('app.siteName');
        $link = $scheme . $site .'/d/drive/?mailhash=' . $hash;
        $html = '<p> ' . $appService->l($user, 'Link for login in service', $tDomain)
            . $os . '<a href="' . $scheme . $site .'" target="_blank">'. $site
            . '</a>:'
            . '</p>';
        $html .= '<p>'
              . '<a href="' . $link . '" target="_blank">'. $link . '</a>'
              . '</p>';
        $html .= '<p>' . $appService->l($user, 'Link will actual in next three days', $tDomain) . '</p>';
        $html .= '<p>' . $appService->l($user, 'Best regards, site', $tDomain) . $os . $site . '</p>';



        return $html;
    }

    private function isMaxUsersRegistred(): bool
    {
        $sql = "SELECT COUNT(id) AS c FROM ausers
            WHERE
                role = :role
                AND email NOT LIKE(:testdomain);";

        $count = $this->_oAppService->dbvalue($sql,
            [
                "role" => User::ROLE_WEB_DRIVE_USER,
                "testdomain" => "%@qwe.ru"
            ]
        );
        return $count >= self::MAX_ALLOW_WUSB_USERS;
     }
}
