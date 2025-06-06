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

/**
 * Class AuthenticationHandler
 * @package App\Handler
 */
class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
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
     * AuthenticationHandler constructor.
     * @param RouterInterface $router
     * @param Session $session
     */
    public function __construct(RouterInterface $router,/* Session $session*/ ContainerInterface $oContainer, AppService $appService, UserService $userService)
    {
        $this->router = $router;
		$this->_oContainer = $oContainer;
        $this->session = $oContainer->get('request_stack')->getCurrentRequest()->getSession();
        $this->appService = $appService;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return JsonResponse|RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $this->userService->restorePassword($token->getUser());

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['success' => true]);
        } else {
            $url = $this->router->generate('home');
            return new RedirectResponse($url);
        }

    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
			//$request->get('session')->set(Security::AUTHENTICATION_ERROR, $exception);
			$t = $this->_oContainer->get('translator');
			$sMessage = $this->appService->l(null, $exception->getMessage(), null);
            return new JsonResponse(['success' => false, 'message' => $sMessage]);
        } else {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
            return new RedirectResponse($this->router->generate('login'));
        }
    }


}
