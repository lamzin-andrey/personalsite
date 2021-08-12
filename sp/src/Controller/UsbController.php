<?php

namespace App\Controller;

use App\Entity\DrvCatalogs;
use App\Entity\PhdMessages;
use App\Entity\PhdUsers;
use App\Form\PsdUploadFormType;
use App\Form\RegisterFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\DrvCatalogsRepository;
use App\Service\AppService;
use App\Service\PayService;
use App\Service\FileUploaderService;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
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
                                              CsrfTokenManagerInterface $csrfTokenManager)
	{
	    // $aData['errors'] = $oAppService->getFormErrorsAsArray($oForm);
        // $this->_json($aData)
		if ($oRequest->getMethod() == 'POST') {
			return $this->_json([
			    'message' => 'oops'
            ]);
		} else {
		    $user = $this->getUser();
            $csrfToken = $csrfTokenManager
                ? $csrfTokenManager->getToken('authenticate')->getValue()
                : null;
		    if (is_null($user)) {
                $formRegister = $this->createForm(get_class(new RegisterFormType()));
                $csrfRegToken = $oAppService->getFormTokenValue($formRegister);
                $formReset = $this->createForm(ResetPasswordFormType::class);
                $csrfResetToken = $oAppService->getFormTokenValue($formReset);
		        $data = [
		            'auth' => false,
                    'token_reg' => $csrfRegToken,
                    'token_res' => $csrfResetToken
                ];
            } else {
                $data = [
                    'auth' => true,
                    'token' => $csrfToken,
                    'uid' => $user->getId()
                ];
            }
			return $this->_json($data);
		}
	}

    /**
     * @Route("/driveaddcatalog.json", name="driveaddcatalog")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveAddCatalogAction(Request $request,
                                              TranslatorInterface $t,
                                              AppService $oAppService,
                                              Filesystem $filesystem,
                                              CsrfTokenManagerInterface $csrfTokenManager)
    {
        $csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        if ($csrfToken != $request->request->get('_token')) {
            $domain = null;
            return $this->_json([
                'status' => 'error',
                'error' => $t->trans('You have not access to this page', [], $domain)
            ]);
        }

        $domain = 'wusb_filesystem';
        // Create db record
        /**
         * @var DrvCatalogsRepository $catalogRepository
        */
        $catalogRepository = $this->container->get('doctrine')->getRepository(DrvCatalogs::class);
        $alreadyExists = false;
        $catalogId = $catalogRepository->addCatalogEntity($request->get('name'), $this->getUser()->getId(), intval($request->get('c')), $alreadyExists);

        if ($alreadyExists) {
            return $this->_json([
                'status' => 'error',
                'error' => $t->trans('Catalog with name already exists', [], $domain)
            ]);
        }

        if (!$catalogId) {
            return $this->_json([
                'status' => 'error',
                'error' => $t->trans('Unable create catalog (db)', [], $domain)
            ]);
        }

        // First create phisical catalog
        $relativePath = $this->getParameter('app.wusb_catalog_root');
        $userPath = $this->generateUserPath($this->getUser()->getId()); // TODO
        $path = $request->server->get('DOCUMENT_ROOT') . $relativePath . '/' . $userPath . '/' . $catalogId;
        $filesystem->mkdir($path);

        if (!$filesystem->exists($path) || !is_dir($path)) {
            return $this->_json([
                'status' => 'error',
                'error' => $t->trans('Unable create catalog', [], $domain)
            ]);
        }


        return $this->_json([
            'name' => $request->request->get('name', ''),
            'i' => $catalogId
        ]);

    }

    /**
     * @Route("/drivelist.json", name="drivegetcatalogfiles")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveGetCatalogFilesAction(Request $request,
                                          TranslatorInterface $t,
                                          AppService $oAppService,
                                          Filesystem $filesystem,
                                          CsrfTokenManagerInterface $csrfTokenManager)
    {
        /*$csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        if ($csrfToken != $request->request->get('_token')) {
            $domain = null;
            return $this->_json([
                'status' => 'error',
                'error' => $t->trans('You have not access to this page', [], $domain)
            ]);
        }*/

        $domain = 'wusb_filesystem';
        // Create db record
        /**
         * @var DrvCatalogsRepository $catalogRepository
         */
        $catalogRepository = $this->container->get('doctrine')->getRepository(DrvCatalogs::class);
        $parentId = $request->query->get('c');
        $filter = [
            'userId' => $this->getUser()->getId(),
            'parentId' => $parentId,
            'isHide' => false
        ];
        if (intval($request->query->get('m')) === 1) {
            unset($filter['isHide']);
        }
        $listData = $catalogRepository->findBy($filter);

        $list = [];
        $relativePath = $this->getParameter('app.wusb_catalog_root');
        $userPath = $this->generateUserPath($this->getUser()->getId());

        foreach ($listData as $drvCatalogs) {
            $path = $request->server->get('DOCUMENT_ROOT') . $relativePath . '/' . $userPath . '/' . $drvCatalogs->getId();
            /*if (!$filesystem->exists($path) || !is_dir($path)) {
                continue;
            }*/
            $item = [
                'name' => $drvCatalogs->getName(),
                'type' => 'c',
                'i' => $drvCatalogs->getId(),
                'h' => $drvCatalogs->getIsHide()
            ];
            $list[] = $item;
        }

        return $this->_json([
            'ls' => $list,
            'p' => $parentId
        ]);


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

	/**
	 * @param $
	 * @return
	*/
	protected function generateUserPath(int $userId) : string
	{
	    $n = floor($userId / 100) * 100 + 1;
	    $s = ($n) . '-' . ($n + 100 - 1) . '/' . $userId;

	    return $s;
	}
		
}
