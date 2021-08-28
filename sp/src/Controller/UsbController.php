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
use Doctrine\ORM\Query\TreeWalkerAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Translation\Translator;
use \TreeAlgorithms;
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
                    'token' => $csrfToken,
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
            'isDeleted' => false,
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
        $realParentId = $parentId;
        $parentCatalog = $catalogRepository->find($parentId);
        if ($parentCatalog) {
            $realParentId = $parentCatalog->getParentId();
        }

        TreeAlgorithms::$parentIdFieldName = 'parentId';
        $flatList = $catalogRepository->getFlatIdListByUserId($this->getUser()->getId());
        $cluster = TreeAlgorithms::buildTreeFromFlatList($flatList, true);
        $breadCrumbs = [];
        foreach ($cluster as $tree) {
            $nodes = TreeAlgorithms::getNodesByNodeId($tree, $parentId);
            if (count($nodes)) {
                foreach ($nodes as $node) {
                    $breadCrumbs[] = $node->name;
                }
                break;
            }
        }
        $breadCrumbs = '/' . implode('/', $breadCrumbs);

        return $this->_json([
            'ls' => $list,
            'p' => $realParentId,
            'bc' => $breadCrumbs
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

	/**
     * @Route("/driveremid.json", name="drivegetcatalogsidlist")
     * Get all subcatalog id list
	 * @param $
	 * @return
	*/
	public function getRemovableIdListAction(Request $request, TranslatorInterface $t)
	{
	    $domain = '';
        $id = intval($request->get('i'));
        /**
         * @var DrvCatalogsRepository $catalogsRepository
        */
	    $catalogsRepository = $this->container->get('doctrine')->getRepository(DrvCatalogs::class);
	    // check acccess rights
        $catalog = $catalogsRepository->find($id);
        if (!$this->getUser() || is_null($catalog) || $catalog->getUserId() != $this->getUser()->getId()) {
            return $this->_json([
                'status' => 'error',
                'error' => $t->trans('You have not access to this page', [], $domain)
            ]);
        }

        TreeAlgorithms::$parentIdFieldName = 'parentId';
        $list = [$id];
	    $flatList = $catalogsRepository->getFlatIdListByUserId( intval($this->getUser()->getId() ) );
        $tree = TreeAlgorithms::buildTreeFromFlatList($flatList, false);

        $node = null;
        for ($i = 0; $i < count($tree); $i++) {
            $node = TreeAlgorithms::findById($tree[$i], $id);
            if (!is_null($node)) {
                break;
            }
        }
        if (!is_null($node)) {
            $list = TreeAlgorithms::getBranchIdList($node);
        }


	    return $this->_json([
	        'status' => 'ok',
            'list' => $list
        ]);
	}
    /**
     * @Route("/drivermrf.json", name="driveremovecatalog", methods={"POST"})
     * Set all catalogs as is Deleted
     * @param $
     * @return
     */
    public function setCalaogsAsIsDeleted(Request $request, TranslatorInterface $t)
    {
        $domain = '';
        if (!$this->getUser()) {
            return $this->_json([
                'status' => 'error',
                'error' => $t->trans('You have not access to this page', [], $domain)
            ]);
        }

        $rawIdList = explode(',', $request->request->get('list'));
        $idList = [];
        foreach ($rawIdList as $id) {
            $n = intval($id);
            if ($n > 0) {
                $idList[] = $n;
            }
        }

        if (count($idList)) {
            /**
             * @var DrvCatalogsRepository $catalogsRepository
             */
            $catalogsRepository = $this->container->get('doctrine')->getRepository(DrvCatalogs::class);
            $catalogsRepository->removeByIdList($idList, intval($this->getUser()->getId()));
        }

        return $this->json(['status' => 'ok']);
    }
    /**
     * @Route("/drivern.json", name="driverenamecatalog", methods={"PATCH", "POST"})
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveRenameCatalogAction(Request $request,
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

        $catalogId = $catalogRepository->renameCatalogEntity(intval($request->get('i')), $request->get('s'), $this->getUser()->getId(), intval($request->get('c')), $alreadyExists);

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
        $userPath = $this->generateUserPath($this->getUser()->getId());
        $path = $request->server->get('DOCUMENT_ROOT') . $relativePath . '/' . $userPath . '/' . $catalogId;
        $filesystem->mkdir($path);

        if (!$filesystem->exists($path) || !is_dir($path)) {
            return $this->_json([
                'status' => 'error',
                'error' => $t->trans('Unable create catalog', [], $domain)
            ]);
        }


        return $this->_json([
            'name' => $request->request->get('s', '')
        ]);
    }
}
