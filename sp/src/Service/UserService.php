<?php


namespace App\Service;


use App\Entity\UserTempPasswords;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Ausers AS User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;


class UserService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $userPasswordEncoderInterface;

    /**
     * @var CsrfTokenManager
     */
    protected $csrfTokenManager;

    protected GuardAuthenticatorHandler $guardHandler;


    public function __construct(
        ContainerInterface $container,
        UserPasswordEncoderInterface $userPasswordEncoderInterface,
        CsrfTokenManagerInterface $csrfTokenManager,
        GuardAuthenticatorHandler $guardHandler
    )
    {
        $this->container = $container;
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->guardHandler = $guardHandler;
    }

    /**
     * @param $
     * @return
    */
    public function setPassword(User $user, string $password, bool $immediatelySave = false) : void
    {
        $hash = $this->userPasswordEncoderInterface->encodePassword($user, $password);
        $user->setPassword($hash);
        if ($immediatelySave) {
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($user);
            $em->flush();
        }

        return;
    }

    /**
     *
    */
    public function storePassword(User $user) : void
    {
        $userTempPassword = $this->container->get('doctrine')->getRepository(UserTempPasswords::class)->findOneBy([
            'userId' => $user->getId()
        ]);
        if (!$userTempPassword) {
            $userTempPassword = new UserTempPasswords();
            $userTempPassword->setUserId($user->getId());
        }
        $userTempPassword->setPassword($user->getPassword());
        $this->save($userTempPassword);
    }

    /**
     *
    */
    public function getFormLoginTokenCsrf() : string
    {
        return $this->csrfTokenManager->getToken('authenticate')->getValue();
    }

    /**
     *
    */
    public function restorePassword(User $user) : void
    {
        $userTempPassword = $this->container->get('doctrine')->getRepository(UserTempPasswords::class)->findOneBy([
            'userId' => $user->getId()
        ]);
        if ($userTempPassword) {
            $user->setPassword($userTempPassword->getPassword());
            $this->save($user);
            $em = $this->container->get('doctrine')->getManager();
            $em->remove($userTempPassword);
            $em->flush();
        }
        return;
    }

    /**
     * Save entities
     * @param $
     * @return
    */
    protected function save() : void
    {
        $args = func_get_args();
        $n = 0;
        $em = $this->container->get('doctrine')->getManager();
        foreach ($args as $arg) {
            $em->persist($arg);
            $n++;
        }

        if ($n > 0) {
            $em->flush();
        }

    }

    public function login(UserInterface $user, bool $rememberMe = true, string $firewallName = 'main'): TokenInterface
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        if (!$rememberMe) {
            $token = new PostAuthenticationGuardToken($user->getRoles(), $firewallName, $roles);
        } else {
            $secret = $this->container->getParameter('kernel.secret');
            $token = new RememberMeToken($user, $firewallName, $secret);
        }
        $this->guardHandler->authenticateWithToken($token, $request, $firewallName);

        return $token;
    }

    public function createRegisterByEmailUser(string $email, bool $agreeSubscribe, bool $acceptCookies, bool $doSave = false): User
    {
        $user = new User();
        $user->setEmail($email);
        $username = $this->createUniqUsername($email);
        $user->setUsername($username);
        $name = explode("@", $username)[0];
        $user->setName($name);
        $user->setDateCreate(new \DateTime());
        $user->setSurname($name);
        $user->setIsSubscribed($agreeSubscribe);
        $user->setIsAcceptAllCookies($acceptCookies);
        $user->setIsAcceptAllCookiesTime(new \DateTime());
        if ($doSave) {
            $this->save($user);
        }

        return $user;
    }

    private function createUniqUsername(string $email): string
    {
        $rep = $this->container->get('doctrine')->getRepository(User::class);
        $n = 0;
        do {
            $user = $rep->findOneBy([
                'username' => $email
            ]);
            $n++;
            $a = explode("@", $email);
            $email = $a[0] . $n ."@" . $a[1];
        } while ($user);

        return $email;
    }

    /**
     * Генерирует пароль для пользователя
     */
    public function generatePassword(int $length = 20): string
    {
        do {
            $s = $this->_generatePassword($length);
            $isValid = true;
            if (strtolower($s) == $s || strtoupper($s) == $s) {
                $isValid = false;
            }
            $noN = preg_replace("#[\d]#s", '', $s);
            if ($noN == $s) {
                $isValid = false;
            }
        } while (!$isValid);

        return $s;
    }

    /**
     * Генерирует пароль для пользователя
     */
    private function _generatePassword(int $length): string
    {
        $L = 0;
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $chars .= strtoupper($chars);
        $chars .= '1234567890';
        $rate = [];
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
        return $str;
    }

    public function isAdmin($user): bool
    {
        if (!$user) {
            return false;
        }
        $nRole = $user->getRole();
        return ($nRole == 2);
    }
}
