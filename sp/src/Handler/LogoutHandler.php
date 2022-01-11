<?php
namespace App\Handler;

use App\Service\AppService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

/**
 * Class AuthenticationHandler
 * @package App\Handler
 */
class LogoutHandler implements LogoutSuccessHandlerInterface
{
	/**
	 * @var RouterInterface
	 */
	private $router;
	/**
	 * @var Session
     */
    private $session;

    /**
     * @var AppService
    */
    private $appService;

    /**
     * @var UserService
     */
    private $userService;



    /**
     * @param $
     * @return
     */
    public function onLogoutSuccess(Request $request)
    {
        $referer = $request->query->get('rd', '/public/sp');

        return new RedirectResponse($referer);
    }
}