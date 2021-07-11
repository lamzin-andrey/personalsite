<?php

namespace App\Controller;

use App\Entity\PhdMessages;
use App\Entity\PhdUsers;
use App\Form\PsdUploadFormType;
use App\Service\AppService;
use App\Service\PayService;
use App\Service\FileUploaderService;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;

class UsbController extends AbstractController
{
	/** @property string $_subdir подкаталог в который загружаются файлы при аплоаде */
	private  $_subdir;



	/**
	 * @Route("/dast.json", name="driveauthstate")
	 * @param Request $oRequest
	 * @param TranslatorInterface $t
	 * @param $
	 * @return
	 */
	public function driveCheckAuthStateAction(Request $oRequest,
                                              TranslatorInterface $t,
                                              AppService $oAppService,
                                              CsrfTokenManager $csrfTokenManager)
	{
	    // $aData['errors'] = $oAppService->getFormErrorsAsArray($oForm);
        // $this->_json($aData)
		if ($oRequest->getMethod() == 'POST') {
			return $this->_json([
			    'message' => 'oops'
            ]);
		} else {
		    $user = $this->getUser();
		    if (is_null($user)) {
                $csrfToken = $csrfTokenManager
                    ? $oCsrfTokenManager->getToken('authenticate')->getValue()
                    : null;
		        $data = [
		            'auth' => false,
                    'token' => $csrfToken
                ];
            } else {

            }
			return $this->_json(['nopost' => 1]);
		}
	}

    private function _json($aData)
	{
		if (!isset($aData['status'])) {
			$aData['status'] = 'ok';
		}
    	$oResponse = new Response( json_encode($aData) );
    	$oResponse->headers->set("Content-Type", "application/json");
    	return $oResponse;
	}

	/**
	 * Установка токена формы
	 * @param array &$aData
	*/
	private function _setPsdUploadFormToken(array &$aData, AppService $oAppService) : void
	{
		$this->_subdir = 'd/' . date('Y/m');
		$oForm = $this->createForm(get_class(new PsdUploadFormType()), null, [
			'app_service' => $oAppService,
			'uploaddir' => $this->_subdir
		]);
		$aData['formToken'] = $oForm->createView()->children['_token']->vars['value'];
	}

	/**
	 * Установить куку авторизации клиента 
	 * @param string  $sCookieValue
	 * @param Cookie
	*/
	private function _createPhdClientCookie(string $sCookieValue)
	{
		$sCookieName = $this->getParameter('app.phdusercookiename');
		return Cookie::create($sCookieName, $sCookieValue, time() + 31536000);
	}
		
}
