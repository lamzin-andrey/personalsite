<?php


namespace App\Service;


use App\Entity\UserTempPasswords;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Ausers AS User;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;


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


    public function __construct(ContainerInterface $container,
                                UserPasswordEncoderInterface $userPasswordEncoderInterface,
                                CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->container = $container;
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
        $this->csrfTokenManager = $csrfTokenManager;
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
            $userTempPassword->setPassword($user->getPassword());
            $userTempPassword->setUserId($user->getId());
            $this->save($userTempPassword);
        }
        return;
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
}