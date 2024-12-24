<?php

namespace App\Controller;

use App\Entity\Ausers;
use App\Entity\BanUsers;
use App\Entity\DrvBookmark;
use App\Entity\DrvCatalogs;
use App\Entity\DrvFile;
use App\Entity\DrvFilePermissions;
use App\Entity\PhdMessages;
use App\Entity\PhdUsers;
use App\Form\PsdUploadFormType;
use App\Form\RegisterFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\DrvCatalogsRepository;
use App\Repository\DrvFileRepository;
use App\Repository\UserRepository;
use App\Service\AppService;
use App\Service\BitReader;
use App\Service\PayService;
use App\Service\FileUploaderService;
use App\Service\UserService;
use App\Stat\Service\StatService;
use App\WebUSB\Service\FilePermissionService;
use App\WebUSB\Service\WusbUploadService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\TreeWalkerAdapter;
use Landlib\SimpleMail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Translation\Translator;
use \TreeAlgorithms;
use Symfony\Contracts\Translation\TranslatorInterface;
use Transliterator;
use StdClass;

class UsbController extends AppBaseController
{

    private const VERSION = '57';

    /** @property string $backendRoot subdirectory with root symfony project */
    private  $backendRoot = '/sp/public';

    /**
     * @var Request
    */
    protected $request;

    public function __construct(ContainerInterface $container)
    {
            $this->request = $container->get('request_stack')->getCurrentRequest();
    }

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
                                              StatService $statService,
                                              CsrfTokenManagerInterface $csrfTokenManager)
	{
        $adv = 0; //1 adv self, 2 VK adv, 0 off
		if ($oRequest->getMethod() == 'POST') {
			return $this->_json([
			    'message' => 'oops'
            ]);
		} else {
		    /**
             * @var AUsers $user
		    */
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
                    'token_res' => $csrfResetToken,
                    'adv' => $adv,
                ];
            } else {
                /**
                 * @var DrvFileRepository $filesRepository
                 */
                $filesRepository = $this->getDoctrine()->getRepository(DrvFile::class);

                if (0 == $user->getAdvAgree()) {
                    $adv = 0;
                }
                $data = [
                    'auth' => true,
                    'token' => $csrfToken,
                    't' => AppService::getHumanFilesize($this->getTotalSize($user) - $filesRepository->getCurrentSize($user), 0, 3, true),
                    'uid' => $user->getId(),
                    'u' => $user->getUsername(),
                    'adv' => $adv,
                    'f' => 0 // TODO days to date close project
                ];
            }

            $statService->write($user);

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
                'error' => $this->l($t, 'You have not access to this page', $domain)
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
                'error' => $this->l($t, 'Catalog with name already exists')
            ]);
        }

        if (!$catalogId) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'Unable create catalog (db)')
            ]);
        }

        // First create phisical catalog
        $path = $this->createCatalog($request, $filesystem, $catalogId);

        if (!$filesystem->exists($path) || !is_dir($path)) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'Unable create catalog')
            ]);
        }


        return $this->_json([
            'name' => $request->request->get('name', ''),
            'i' => $catalogId,
            'type' => 'c'
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
                                          Filesystem $filesystem,
                                          CsrfTokenManagerInterface $csrfTokenManager)
    {
        /*
         * TODO may be, token need? ))))
         * $csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        if ($csrfToken != $request->request->get('_token')) {
            $domain = null;
            return $this->_json([
                'status' => 'error',
                'error' => $t->trans('You have not access to this page', [], $domain)
            ]);
        }*/
        if (!$this->getUser()) {
            $data = [
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page',  null)
            ];

            return $this->_json($data);
        }

        $domain = 'wusb_filesystem';
        $parentId = $request->query->get('c');
        $mode = intval($request->query->get('m'));

        return $this->_json($this->getFileList($parentId, $mode, $request, $filesystem, $this->getUser()));
    }

    /**
     * @Route("/drivegetlink.json", name="drivegetdownloadkink")
     * @param Request $request
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveGetFileDowloadLinkAction(
       Request $request,
       TranslatorInterface $t,
       Filesystem $filesystem,
       CsrfTokenManagerInterface $csrfTokenManager,
       AppService $appService,
       FilePermissionService $filePermissionService
    )
    {
        $data = [];
        /**
         * @var ?DrvFile $fileEntity
        */
        $fileEntity = null;
        if (!$this->hasAccessToFile($request, $csrfTokenManager, $data, $t, $fileEntity, $filePermissionService)) {
            return $this->_json($data);
        }

        if ($fileEntity->getWdPublic() == 1) {
            $fileEntity->setDwnCnt($fileEntity->getDwnCnt() + 1);
            $appService->save($fileEntity);
            return $this->_json([
                'link' => $fileEntity->getWdLink()
            ]);
        }

        $domain = 'wusb_filesystem';
        $filePathObject = $this->getFilePathObject($fileEntity, $this->getUser(), $request, $filesystem);
        $path = $filePathObject->path;

        if (filesize($path) < 40 * pow(1024, 2)) {
            $fileEntity->setDwnCnt($fileEntity->getDwnCnt() + 1);
            $appService->save($fileEntity);
            return $this->_json([
                'link' => $this->backendRoot . '/drivedwnlsmall?i=' . $request->query->get('i')
            ]);
        }

        if (!empty($filePathObject->error)) {
            return $this->_json([
                'status' => 'ok',
                'error' => $this->l($t, $filePathObject->error)
            ]);
        }
        $symlink = $filePathObject->symlink;

        if (!$filesystem->exists($symlink)) {
            symlink($path, $symlink);
        }
        if (!$filesystem->exists($symlink)) {
            return $this->_json([
                'status' => 'ok',
                'error' => $this->l($t, 'Unable create copy')
            ]);
        }

        $fileEntity->setDwnCnt($fileEntity->getDwnCnt() + 1);
        $appService->save($fileEntity);
        return $this->_json([
            'link' => str_replace($request->server->get('DOCUMENT_ROOT'), '', $symlink)
        ]);
    }


    /**
     *
     * Small file - file less than 40 Mb
     * @Route("/drivedwnlsmall", name="drivedownloadsmallfile")
     * @param Request $request
     * @param TranslatorInterface $t
    */
    public function downloadSmallFileAction(
        Request $request,
        TranslatorInterface $t,
        Filesystem $filesystem,
        CsrfTokenManagerInterface $csrfTokenManager,
        FilePermissionService $filePermissionService
    )
    {

        $data = [];
        /**
         * @var ?DrvFile $fileEntity
        */
        $fileEntity = null;
        if (!$this->hasAccessToFile($request, $csrfTokenManager, $data, $t, $fileEntity, $filePermissionService)) {
            return $this->_json($data);
        }

        $domain = 'wusb_filesystem';
        $filePathObject = $this->getFilePathObject($fileEntity, $this->getUser(), $request, $filesystem);
        $path = $filePathObject->path;
        if (!empty($filePathObject->error)) {
            return $this->_json([
                'status' => 'ok',
                'error' => $this->l($t, $filePathObject->error)
            ]);
        }

        if (!$filesystem->exists($path)) {
            $s = $t->trans('File not found', [], $domain);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="Not_Found.txt"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($s));
            echo $s;
            exit;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($fileEntity->getName()).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    protected function _json($aData)
	{
	    if (is_array($aData)) {
            if (!isset($aData['status'])) {
                $aData['status'] = 'ok';
            }
        } else {
            if (!isset($aData->status)) {
                $aData->status = 'ok';
            }
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
                'error' => $this->l($t, 'You have not access to this page', null)
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
    public function setCalaogsAsIsDeleted(Request $request, TranslatorInterface $t, Filesystem $filesystem)
    {
        if (!$this->getUser()) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', null)
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
            /**
             * @var DrvFileRepository $filesRepository
             */
            $filesRepository = $this->container->get('doctrine')->getRepository(DrvFile::class);
            $filesRepository->removeByCatalogIdList($idList, intval($this->getUser()->getId()), $this->getLegalTimeIntervalSeconds());
            $list = $filesRepository->findBy(['catalogId' => $idList]);
            $this->removePhisFiles($list, $this->getUser(), $request, $filesystem, $t);
        }

        return $this->json(['status' => 'ok']);
    }

    /**
     * @Route("/driverm.json", name="driveremove", methods={"POST"})
     * Set file as is Deleted and remove phisical file
     * @param $
     * @return
     */
    public function driveRemoveFile(Request $request, TranslatorInterface $t, Filesystem $filesystem)
    {
        if (!$this->getUser()) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', '')
            ]);
        }

        $fileId = intval($request->request->get('i') );

        if ($fileId > 0) {
            /**
             * @var DrvFileRepository $fileRepository
             */
            $fileRepository = $this->container->get('doctrine')->getRepository(DrvFile::class);
            // remove phisical + symlink
            $fileEntity = $fileRepository->find($fileId);
            if ($fileEntity->getUserId() != $this->getUser()->getId()) {
                return $this->_json([
                    'status' => 'error',
                    'error' => $this->l($t, 'You have not access to this page', null)
                ]);
            }
            if ($this->itIsLegalRm($fileEntity)) {
                if ($fileEntity->getWdPublic() == 1) {
                    $fileEntity->setIsDeleted(true);
                    $fileEntity->setWdPublic(6);
                }
                $pathObject = $this->getFilePathObject($fileEntity, $this->getUser(), $request, $filesystem);
                $this->removeSymlink($filesystem, $pathObject->symlink);
                if (!empty($pathObject->path) && file_exists($pathObject->path)) {
                    $filesystem->remove($pathObject->path);
                }
                $fileRepository->setIsDeletedById($fileId, intval($this->getUser()->getId()));
            } else {
                $fileRepository->removeById($fileId, intval($this->getUser()->getId()), 6);
            }
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
        if ($csrfToken != $request->request->get('_token') || !$this->getUser()) {
            $domain = null;
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', null)
            ]);
        }
        $domain = 'wusb_filesystem';
        if ('c' == $request->get('t') ) {
            /**
             * @var DrvCatalogsRepository $catalogRepository
             */
            $catalogRepository = $this->container->get('doctrine')->getRepository(DrvCatalogs::class);
            $alreadyExists = false;

            $catalogId = $catalogRepository->renameCatalogEntity(intval($request->get('i')), $request->get('s'), $this->getUser()->getId(), intval($request->get('c')), $alreadyExists);

            if ($alreadyExists) {
                return $this->_json([
                    'status' => 'error',
                    'error' => $this->l($t, 'Catalog with name already exists')
                ]);
            }

            if (!$catalogId) {
                return $this->_json([
                    'status' => 'error',
                    'error' => $this->l($t, 'Unable rename catalog (db)')
                ]);
            }
        } else {
            /**
             * @var DrvFileRepository $fileRepository
             */
            $fileRepository = $this->container->get('doctrine')->getRepository(DrvFile::class);
            $alreadyExists = false;

            $fileId = $fileRepository->renameFileEntity(intval($request->get('i')), $request->get('s'), $this->getUser()->getId(), intval($request->get('c')), $alreadyExists);

            if ($alreadyExists) {
                return $this->_json([
                    'status' => 'error',
                    'error' => $this->l($t, 'File with name already exists')
                ]);
            }

            if (!$fileId) {
                return $this->_json([
                    'status' => 'error',
                    'error' => $this->l($t, 'Unable rename file (db)')
                ]);
            }
        }

        return $this->_json([
            'name' => $request->request->get('s', '')
        ]);
    }

    /**
     * @Route("/drvupload.json", name="driveupload", methods={"POST"})
     * @param Request $request
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveUploadAction(Request $request,
                                     TranslatorInterface $t,
                                     AppService $oAppService,
                                     Filesystem $filesystem,
                                     CsrfTokenManagerInterface $csrfTokenManager,
                                     WusbUploadService $wusbUploadService
    )
    {
        $csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        if ($csrfToken != $request->request->get('_csrf_token_uf')) {
            $domain = null;
            return $this->mixResponse($request, [
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page 1', $domain)
            ]);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->mixResponse($request, [
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page 2', null)
            ]);
        }

        $domain = 'wusb_filesystem';
        /**
         * @var UploadedFile $file
        */
        $file = $request->files->get('iFile');
        $iModifyTime = round(intval($request->request->get('mt', 0) ) / 1000 );
        $modifyTime = new \DateTime();
        $modifyTime->setTimestamp($iModifyTime);

        // Get all user files sizes
        /**
         * @var DrvFileRepository $filesRepository
         */
        $filesRepository = $this->getDoctrine()->getRepository(DrvFile::class);
        $size = $filesRepository->getCurrentSize($user) + $file->getSize();
        //$allowSize = intval($this->getParameter('app.wusb_max_space') );
        $allowSize = $this->getTotalSize($user);

        if ($allowSize <= $size) {
            return $this->mixResponse($request, [
                'status' => 'error',
                'error' => $this->l($t, 'Your busy all {allowSize}',  'wusb_filesystem', [
                    '{allowSize}' => AppService::getHumanFilesize($this->getTotalSize($user) - $filesRepository->getCurrentSize($user), 0, 3, false)
                ])
            ]);
        }

        $originalName = $file->getClientOriginalName();
        $relativePath = $this->getParameter('app.wusb_catalog_root');
        $userPath = $this->generateUserPath($this->getUser()->getId());
        $drvCatalogId = intval($request->request->get('c'));
        $drvCatalog = null;

        $isTryUploadInRoot = false;
        if ($drvCatalogId == 0) {
            $lang = 'en';
            if ('langRu' == $request->request->get('lang')) {
                $lang = 'ru';
            }
            $drvCatalogId = $wusbUploadService->getUploadCatalogId($drvCatalogId, $lang, $user->getId());
            $this->createCatalog($request, $filesystem, $drvCatalogId);
            $isTryUploadInRoot = true;
        }
        if ($drvCatalogId > 0) {
            /**
             * @var DrvCatalogs $drvCatalog
            */
            $drvCatalog = $this->getDoctrine()->getRepository(DrvCatalogs::class)->find($drvCatalogId);
            if (is_null($drvCatalog) || !$this->getUser() || $drvCatalog->getUserId() != $this->getUser()->getId() ) {
                return $this->mixResponse($request, [
                    'status' => 'error',
                    'error' => $this->l($t, 'You have not access to this page 2', $domain)
                ]);
            }
        }

        $existsFile = $this->getDoctrine()->getRepository(DrvFile::class)->findOneBy([
            'name' => $originalName,
            'catalogEntity' => $drvCatalog,
            'isDeleted' => false
        ]);

        if (!is_null($existsFile)) {
            return $this->mixResponse($request, [
                'status' => 'error',
                'error' => $this->l($t, 'File with name {name} already exists', $domain, ['{name}' => $originalName])
            ]);
        }

        $targetPath = $path = $request->server->get('DOCUMENT_ROOT') . $relativePath . '/' . $userPath . '/' . $drvCatalog->getId();

        if (!$filesystem->exists($targetPath)) {
            return $this->mixResponse($request, [
                'status' => 'error',
                'error' => $this->l($t, 'Target catalog not found on disk. Try again later.')
            ]);
        }

        if (intval($request->request->get('isiframe')) === 1) {
            $originalName = mb_convert_encoding($originalName, 'utf-8', 'Windows-1251');
        }

        $pathInfo = pathinfo($originalName);
        $ext = $pathInfo['extension'] ?? '';
        $ext = strtolower($ext);
        if ( (
                'php' == $ext
                || 'sh' == $ext
                || 'js' == $ext
                || 'pl' == $ext
                || 'py' == $ext
                || 'bat' == $ext
                || 'exe' == $ext
            ) && 2 !== $this->getUser()->getRole() ) {
            return $this->mixResponse($request, [
                'status' => 'error',
                'error' => $this->l($t, 'This file type deny for upload')
            ]);
        }

        $fileEntity = new DrvFile();
        $fileEntity->setName($originalName);
        $this->setType($fileEntity);
        $fileEntity->setIsDeleted(false);
        $fileEntity->setIsHidden(false);
        $fileEntity->setUserId($this->getUser()->getId());
        $fileEntity->setCatalogEntity($drvCatalog);
        $size = $file->getSize();
        $fileEntity->setSize($size);
        $fileEntity->setUpdatedTime($modifyTime);
        $fileEntity->setCreatedTime(new \DateTime());
        $fileEntity->setHash($oAppService->getHash(
            $request,
            $originalName . $this->getUser()->getId(),
            'sha1'
        ));

        $em = $this->getDoctrine()->getManager();
        $em->persist($fileEntity);
        $drvCatalog->setSize($drvCatalog->getSize() + $size);
        $em->flush();

        $targetName = $fileEntity->getId() . '.' . $ext;
        $file->move($targetPath, $targetName);

        $fileData = [
            'name' => $originalName,
            'type' => $fileEntity->getType()[0] ?? 'u',
            'i'    => $fileEntity->getId(),
            'h'    => false,
            's' => AppService::getHumanFilesize($size),
            'ct' => AppService::sqzDatetime($fileEntity->getCreatedTime()),
            'ut' => AppService::sqzDatetime($fileEntity->getUpdatedTime())
        ];

        if ($isTryUploadInRoot) {
            $drvCatalog->setSize($drvCatalog->getSize() + $size);
            $oAppService->save($drvCatalog);
            $fileData = [
                'name' => $drvCatalog->getName(),
                'type' => 'c',
                'i'    => $drvCatalog->getId(),
                'h'    => false,
                's' => AppService::getHumanFilesize($size),
                'ct' => AppService::sqzDatetime($drvCatalog->getCreatedTime()),
                'ut' => AppService::sqzDatetime($drvCatalog->getUpdatedTime())
            ];
        }
        return $this->mixResponse($request, [
            'status' => 'ok',
            'path' => $relativePath . '/' . $userPath . '/' . $drvCatalog->getId() . '/' . $targetName,
            'file' => $fileData,
            'isRt' => $isTryUploadInRoot
        ]);
    }

    /**
     * Если в request есть isiframe = 1 вернет html, иначе JSON
     * @param $
     * @return
    */
    protected function mixResponse(Request $request, array $data)
    {
        if (intval($request->request->get('isiframe')) !== 1) {
            return $this->_json($data);
        }

        $response = new Response();
        $response->headers->set('Content-type', 'text/html');
        return $this->render('webusb/a2uploadsuccess.html.twig', [
            'data' => json_encode($data)
        ], $response);
        
    }

    /**
     * TODO в сервис, у тебя уже есть AppExtension
     * Транслитерация
     **/
    public function transliteUrl(string $string) : string
    {
        $string = str_replace('ё','e',$string);
        $string = str_replace('й','y',$string);
        $string = str_replace('ю','yu',$string);
        $string = str_replace('ь','',$string);
        $string = str_replace('ч','ch',$string);
        $string = str_replace('щ','sh',$string);
        $string = str_replace('ц','c',$string);
        $string = str_replace('у','u',$string);
        $string = str_replace('к','k',$string);
        $string = str_replace('е','e',$string);
        $string = str_replace('н','n',$string);
        $string = str_replace('г','g',$string);
        $string = str_replace('ш','sh',$string);
        $string = str_replace('з','z',$string);
        $string = str_replace('х','h',$string);
        $string = str_replace('ъ','',$string);
        $string = str_replace('ф','f',$string);
        $string = str_replace('ы','i',$string);
        $string = str_replace('в','v',$string);
        $string = str_replace('а','a',$string);
        $string = str_replace('п','p',$string);
        $string = str_replace('р','r',$string);
        $string = str_replace('о','o',$string);
        $string = str_replace('л','l',$string);
        $string = str_replace('д','d',$string);
        $string = str_replace('ж','j',$string);
        $string = str_replace('э','е',$string);
        $string = str_replace('я','ya',$string);
        $string = str_replace('с','s',$string);
        $string = str_replace('м','m',$string);
        $string = str_replace('и','i',$string);
        $string = str_replace('т','t',$string);
        $string = str_replace('б','b',$string);
        $string = str_replace('Ё','E',$string);
        $string = str_replace('Й','Y',$string);
        $string = str_replace('Ю','YU',$string);
        $string = str_replace('Ч','CH',$string);
        $string = str_replace('Ь','',$string);
        $string = str_replace('Щ','SH',$string);
        $string = str_replace('Ц','C',$string);
        $string = str_replace('У','U',$string);
        $string = str_replace('К','K',$string);
        $string = str_replace('Е','E',$string);
        $string = str_replace('Н','N',$string);
        $string = str_replace('Г','G',$string);
        $string = str_replace('Ш','SH',$string);
        $string = str_replace('З','Z',$string);
        $string = str_replace('Х','H',$string);
        $string = str_replace('Ъ','',$string);
        $string = str_replace('Ф','F',$string);
        $string = str_replace('Ы','I',$string);
        $string = str_replace('В','V',$string);
        $string = str_replace('А','A',$string);
        $string = str_replace('П','P',$string);
        $string = str_replace('Р','R',$string);
        $string = str_replace('О','O',$string);
        $string = str_replace('Л','L',$string);
        $string = str_replace('Д','D',$string);
        $string = str_replace('Ж','J',$string);
        $string = str_replace('Э','E',$string);
        $string = str_replace('Я','YA',$string);
        $string = str_replace('С','S',$string);
        $string = str_replace('М','M',$string);
        $string = str_replace('И','I',$string);
        $string = str_replace('Т','T',$string);
        $string = str_replace('Б','B',$string);
        $string = str_replace(' ','_',$string);
        $string = str_replace('"','',$string);
        $string = str_replace('.','.',$string);
        $string = str_replace("'",'',$string);
        $string = str_replace(",",'',$string);
        $string = str_replace('\\', '', $string);
        $string = str_replace('?', '', $string);

        $result = strtolower($string);
        $allow = 'abcdefgkijklmnopqrstuvwxyz-./0123456789';
        $sz = strlen($result);
        $allowResult = '';
        for ($i = 0; $i < $sz; $i++) {
            $ch = $result[$i];
            if (strpos($allow, $ch) !== false) {
                $allowResult .= $ch;
            }
        }

        return $allowResult;
    }

    /**
     * @param $
     * @return
    */
    protected function getExtWithDot(string $s) : string
    {
        $pathInfo = pathinfo($s);

        return strtolower(isset($pathInfo['extension']) ? ('.' . $pathInfo['extension']) : '');
    }

    /**
     * @param $
     * @return
    */
    protected function setType(DrvFile $fileEntity) : void
    {
        $a = explode('.', $fileEntity->getName());
        $ext = $a[count($a) - 1] ?? '';
        $isZip = false;
        if ('zip' === $ext) {
            $ext = $a[count($a) - 2] ?? '';
            $isZip = true;
        }
        $type = $this->getTypeByExtension($ext);
        if ('unknown' === $type && $isZip) {
            $type = 'zip';
        }

        $fileEntity->setType($type[0]);
    }

    /**
     * @param $
     * @return
    */
    protected function getTypeByExtension(string $extension) : string
    {
        $types = [
            //zip
            'zip' => 'zip',
            'gz' => 'zip',
            '7z' => 'zip',
            // audio
            'mp3' => 'audio',
            'ogg' => 'audio',
            'wav' => 'audio',
            'mp4' => 'audio',

            // text
            'txt' => 'text',
            'php' => 'text',
            'js' => 'text',

            // image
            'jpg' => 'image',
            'png' => 'image',
            'bmp' => 'image',
            'jpeg' => 'image',
            'jiff' => 'image',

            // pdf
            'pdf' => 'pdf',

            // apk
            'apk' => 'apk',
        ];
        return $types[$extension] ?? 'unknown';
    }


    /**
     * @param $
     * @return \StdClass {symlink:string, path: string, error: string, ext: string}
    */
    protected function getFilePathObject(DrvFile $fileEntity, ?Ausers $user, Request $request, Filesystem $filesystem) : \StdClass
    {

        if (!$user) {
            $userId = $fileEntity->getUserId();
            $repository = $this->get('doctrine')->getRepository(AUsers::class);
            $user = $repository->find($userId);
        }

        $result = new \StdClass();
        $result->symlink = '';
        $result->path = '';
        $result->error = '';
        $result->ext = '';

        // start method
        $relativePath = $this->getParameter('app.wusb_catalog_root');
        $userPath = $this->generateUserPath($user->getId());
        $catalogIdSubpath = '';
        $catalogEntity = $fileEntity->getCatalogEntity();
        if (!is_null($catalogEntity)) {
            $catalogIdSubpath = $catalogEntity->getId() . '/';
        }
        $result->ext = $ext = $this->getExtWithDot($fileEntity->getName());
        $result->path = $request->server->get('DOCUMENT_ROOT') . $relativePath . '/' . $userPath . '/' . $catalogIdSubpath . $fileEntity->getId() .  $ext;
        $relativePathForSymlink = str_replace('drive/d', 'drive/t', $relativePath);
        $symlink = $request->server->get('DOCUMENT_ROOT') . $relativePathForSymlink . '/' . $userPath . '/' . $catalogIdSubpath;
        $symlink = preg_replace("#/$#", '', $symlink);
        $filesystem->mkdir($symlink);

        if (!$filesystem->exists($symlink) || !is_dir($symlink)) {
            $result->error = 'Unable create temp catalog';

            return $result;
        }

        // $symlink .= '/' . $fileEntity->getId() . $ext;
        $transliterator = Transliterator::create('Any-Latin');
        $transliteratorToASCII = Transliterator::create('Latin-ASCII');
        $safeFilename = $transliteratorToASCII->transliterate($transliterator->transliterate($fileEntity->getName()));
        $safeFilename = preg_replace("#\s#", '_', $safeFilename);
        $symlink .= '/' . $fileEntity->getHash();
        $filesystem->mkdir($symlink);
        if (!$filesystem->exists($symlink) || !is_dir($symlink)) {
            $result->error = 'Unable create temp catalog (2)';

            return $result;
        }
        $symlink .= '/' . $safeFilename;
        $result->symlink = $symlink;

        return $result;
    }
    /**
     * @param $
     * @return \StdClass {path: string, error: string}
     */
    protected function getDirPathObject(?DrvCatalogs $catalogEntity, Ausers $user, Request $request, Filesystem $filesystem) : \StdClass
    {
        $result = new \StdClass();
        $result->path = '';
        $result->error = '';

        $relativePath = $this->getParameter('app.wusb_catalog_root');
        $userPath = $this->generateUserPath($user->getId());

        $catalogIdSubpath = '';
        if ($catalogEntity) {
            $catalogIdSubpath = '/' . $catalogEntity->getId();
        }
        $result->path = $request->server->get('DOCUMENT_ROOT') . $relativePath . '/' . $userPath .   $catalogIdSubpath;
        $filesystem->mkdir($result->path);

        if (!$filesystem->exists($result->path) || !is_dir($result->path)) {
            $result->error = 'Unable create catalog';
        }

        return $result;
    }

    /**
     * @param $
     * @return
    */
    protected function hasAccessToFile(
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager,
        array &$data,
        TranslatorInterface $t,
        ?DrvFile &$fileEntity,
        FilePermissionService $filePermissionService
    ) : bool
    {

        $csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        $domain = null;
        /*if ($csrfToken != $request->request->get('_token')) {
            $data = [
                'status' => 'error',
                'error' => $t->trans('You have not access to this page', [], $domain)
            ];

            return false;
        }*/

        $isPublic = false;
        if (!$fileEntity) {
            /**
             * @var DrvFileRepository $fileRepository
             */
            $fileRepository = $this->container->get('doctrine')->getRepository(DrvFile::class);
            $filter = [
                'id' => intval($request->query->get('i')),
                'isDeleted' => false
            ];

            $fileEntity = $fileRepository->findOneBy($filter);
        }


        if ($fileEntity && $fileEntity->getIsPublic()) {
            $isPublic = true;
        }
        if (!$this->getUser() && !$isPublic) {
            $data = [
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', null)
            ];

            return false;
        }



        if (!$fileEntity) {
            $data =[
                'status' => 'error',
                'error' => $this->l($t, 'File not found', $domain)
            ];
            return false;
        }

        if ($fileEntity && $fileEntity->getIsPublic()) {
            return true;
        } else if (!$filePermissionService->hasAccessToFile($this->getUser()->getId(), $fileEntity)) {
            $data =[
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', $domain)
            ];
            return false;
        }

        return true;
    }

    /**
     * @Route("/drivemv.json", name="drivemovefiles")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveMoveFilesAction(Request $request,
                                          TranslatorInterface $t,
                                          AppService $oAppService,
                                          Filesystem $filesystem,
                                          CsrfTokenManagerInterface $csrfTokenManager)
    {
        $csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        $domain = null;
        if ($csrfToken != $request->request->get('_token')) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }

        $domain = 'wusb_filesystem';
        // Create db record
        /**
         * @var DrvCatalogsRepository $catalogRepository
         */
        $catalogRepository = $this->container->get('doctrine')->getRepository(DrvCatalogs::class);
        /**
         * @var DrvFileRepository $fileRepository
         */
        $fileRepository = $this->container->get('doctrine')->getRepository(DrvFile::class);

        // build id lists
        $idListStr = $request->request->get('ls', '');
        $idList = explode(',', $idListStr);
        $fileIdList = [];
        $catalogIdList = [];
        $targetCatalogId = intval($request->request->get('t'));
        if ($targetCatalogId) {
            $catalogIdList[] = $targetCatalogId;
        }
        $sz = count($idList);
        for ($i = 0; $i < $sz; $i++) {
            $id = $idList[$i];
            $type = strpos($id, 'fi') === false ? 'c' : 'f';
            if ('f' === $type) {
                $id = intval(trim(str_replace('fi', '', $id) ) );
                if ($id) {
                    $fileIdList[] = $id;
                }
            } else {
                $id = intval(trim(str_replace('f', '', $id) ) );
                if ($id) {
                    $catalogIdList[] = $id;
                }
            }
        }
        // get all user files
        $fileEntities = [];
        if ($fileIdList) {
            $fileEntities = $fileRepository->findBy([
                'id' => $fileIdList,
                'userId' => $user->getId()
            ]);
        }
        // get all user catalogs
        $catalogEntities = [];
        if ($catalogIdList) {
            $catalogEntities = $catalogRepository->findBy([
                'id' => $catalogIdList,
                'userId' => $user->getId()
            ]);
        }
        // check target access
        $targetCatalogEntity = null;
        foreach ($catalogEntities as $dirEntity) {
            if ($dirEntity->getId() == intval($request->request->get('t'))) {
                $targetCatalogEntity = $dirEntity;
                if ($dirEntity->getUserId() != $user->getId()) {
                    return $this->_json([
                        'status' => 'error',
                        'error' => $this->l($t, 'You have not access to this page')
                    ]);
                }
                break;
            }
        }
        // move phisical file
        // change file parent in db
        $dirPathObject = $this->getDirPathObject($targetCatalogEntity, $user, $request, $filesystem);
        if ($dirPathObject->error) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, $dirPathObject->error)
            ]);
        }
        foreach ($fileEntities as $fileEntity) {
            if ($fileEntity->getUserId() != $user->getId()) {
                continue;
            }
            $filePathObject = $this->getFilePathObject($fileEntity, $user, $request, $filesystem);
            if (!$filePathObject->error) {
                if (file_exists($filePathObject->symlink)) {
                    $filesystem->remove($filePathObject->symlink);
                }
                $success = false;
                $error = '';
                try {
                    $filesystem->rename($filePathObject->path, $dirPathObject->path . '/' . $fileEntity->getId() . $filePathObject->ext, true);
                    $success = true;
                } catch (\Exception $exception) {
                    $error = $exception->getMessage();
                }
                if (!$success) {
                    return $this->_json([
                        'status' => 'error',
                        'error' => $this->l($t, 'mup failed! ' . $error)
                    ]);
                }

                // db
                $fileEntity->setCatalogEntity($targetCatalogEntity);

            }
        }
        $em = $this->container->get('doctrine')->getManager();
        // init tree
        TreeAlgorithms::$parentIdFieldName = 'parentId';
        $flatList = $catalogRepository->getFlatIdListByUserId( intval($user->getId() ) );
        $tree = TreeAlgorithms::buildTreeFromFlatList($flatList, true);
        $rootNode = new \StdClass;
        $rootNode->id = 0;
        $rootNode->parentId = -1;
        $rootNode->children = [];
        for ($i = 0; $i < count($tree); $i++) {
            $rootNode->children[] = $tree[$i];
        }
        // change catalog parent in db (use tree for validate action)
        foreach ($catalogEntities as $catalogEntity) {
            if ($catalogEntity->getUserId() != $user->getId()) {
                continue;
            }
            if ($catalogEntity->getId() == $targetCatalogEntity->getId()) {
                continue;
            }
            $nodes = TreeAlgorithms::getNodesByNodeId($rootNode, $targetCatalogEntity->getId());
            $doContinue = false;
            foreach ($nodes as $node) {
                if ($node->id == $catalogEntity->getId()) {
                    $doContinue = true;
                    break;
                }
            }
            if ($doContinue) {
                continue;
            }
            // db
            $targetCatalogId = 0;
            if ($targetCatalogEntity) {
                $targetCatalogId = $targetCatalogEntity->getId();
            }
            $catalogEntity->setParentId($targetCatalogId);
        }
        $em->flush();

        // get current dir list
        $catalogId = 0;
        if ($targetCatalogEntity) {
            $catalogId = $targetCatalogEntity->getId();
        }
        return $this->_json($this->getFileList($catalogId, 0, $request, $filesystem, $user));

    }

    /**
     * @Route("/drivermls.json", name="driveremovefiles")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveRemoveFilesAction(Request $request,
                                         TranslatorInterface $t,
                                         AppService $oAppService,
                                         Filesystem $filesystem,
                                         CsrfTokenManagerInterface $csrfTokenManager)
    {
        $csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        $domain = null;
        if ($csrfToken != $request->request->get('_token')) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }

        $domain = 'wusb_filesystem';
        // Create db record
        /**
         * @var DrvCatalogsRepository $catalogRepository
         */
        $catalogRepository = $this->container->get('doctrine')->getRepository(DrvCatalogs::class);
        /**
         * @var DrvFileRepository $fileRepository
         */
        $fileRepository = $this->container->get('doctrine')->getRepository(DrvFile::class);

        // build id lists
        $idListStr = $request->request->get('ls', '');
        $idList = explode(',', $idListStr);
        $fileIdList = [];
        $catalogIdList = [];
        $targetCatalogId = intval($request->request->get('t'));

        $sz = count($idList);
        for ($i = 0; $i < $sz; $i++) {
            $id = $idList[$i];
            $type = strpos($id, 'fi') === false ? 'c' : 'f';
            if ('f' === $type) {
                $id = intval(trim(str_replace('fi', '', $id) ) );
                if ($id) {
                    $fileIdList[] = $id;
                }
            } else {
                $id = intval(trim(str_replace('f', '', $id) ) );
                if ($id) {
                    $catalogIdList[] = $id;
                }
            }
        }
        // get all user files
        $fileEntities = [];
        if ($fileIdList) {
            $fileEntities = $fileRepository->findBy([
                'id' => $fileIdList,
                'userId' => $user->getId()
            ]);
        }
        // get all user catalogs
        $catalogEntities = [];
        if ($catalogIdList) {
            $catalogIdList = $this->reachCatalogList($catalogIdList, (int)$user->getId(), $oAppService);
            $catalogEntities = $catalogRepository->findBy([
                'id' => $catalogIdList,
                'userId' => $user->getId()
            ]);
            $addFileEntities = $fileRepository->findBy([
                'catalogId' => $catalogIdList,
                'userId' => $user->getId()
            ]);
            if ($addFileEntities) {
                $fileEntities = array_merge($fileEntities, $addFileEntities);
            }
        }
        $response = $this->removePhisFiles($fileEntities, $user, $request, $filesystem, $t);
        if ($response) {
            return $this->_json($response);
        }
        $em = $this->container->get('doctrine')->getManager();

        // change catalog parent in db (use tree for validate action)
        foreach ($catalogEntities as $catalogEntity) {
            if ($catalogEntity->getUserId() != $user->getId()) {
                continue;
            }
            // db
            $catalogEntity->setIsDeleted(true);
        }
        $em->flush();

        // get current dir list
        $r  = $this->getFileList($targetCatalogId, 0, $request, $filesystem, $user);
        return $this->_json($r);
    }

    /**
     * @Route("/driveers.json", name="driveerasefiles")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveEraseFilesAction(Request $request,
                                           TranslatorInterface $t,
                                           AppService $oAppService,
                                           Filesystem $filesystem,
                                           CsrfTokenManagerInterface $csrfTokenManager)
    {
        $csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        $domain = null;
        if ($csrfToken != $request->request->get('_token')) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }

        $domain = 'wusb_filesystem';
        /**
         * @var DrvFileRepository $fileRepository
         */
        $fileRepository = $this->container->get('doctrine')->getRepository(DrvFile::class);

        // get all deleted and no erased user files
        $fileEntities = $fileRepository->findBy([
            'userId' => (int)$user->getId(),
            'isDeleted' => true,
            'isNoErased' => true
        ], null, 100);


        $response = $this->removePhisFiles($fileEntities, $user, $request, $filesystem, $t);
        $oAppService->save();
        if ($response) {
            return $this->_json($response);
        }
        $em = $this->container->get('doctrine')->getManager();
        $em->flush();

        // get current dir list
        return $this->_json([]);

    }

    /**
     * @Route("/wusbsetlang", name="drivechooselang")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveChooseLangAction(Request $request,
                                           TranslatorInterface $t,
                                           AppService $appService,
                                           CsrfTokenManagerInterface $csrfTokenManager)
    {
        $csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        $domain = null;
        if ($csrfToken != $request->request->get('_token')) {
            return new RedirectResponse('/d/drive/a2/clang/?no_token');
        }
        $lang = $request->request->get('lang', 'en');

        $users = [];
        $user = $this->getUser();
        $guestId = $appService->getHash($request, $lang);
        if ($user && ($user instanceof Ausers)) {
            $users[] = $user;
            if ($user->getGuestId()) {
                $guestId = $user->getGuestId();
            }
        } else {
            $saved = $this->request->cookies->get('guest_id');
            if ($saved) {
                $guestId = $saved;
            }
        }

        if ($guestId) {
            // TODO index to guest_id
            $users = $this->container->get('doctrine')->getRepository(Ausers::class)
                ->findBy([
                    'guestId' => $guestId
                ]);
        }

        if (!$users) {
            $user = new Ausers();
            $user->setGuestId($guestId);
            $users[] = $user;
        }

        if ($users) {
            $em = $this->container->get('doctrine')->getManager();
            foreach ($users as $user) {
                $user->setLang($lang);
                $em->persist($user);
            }
            $em->flush();
        }

        $cookie = Cookie::create('guest_id', $guestId, time() + 365*24*3600);
        $response = new RedirectResponse('/d/drive/');
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * @param $
     * @return
    */
    protected function getFileList($parentId, $mode, Request $request, Filesystem $filesystem, Ausers $user)
    {
        // Create db record
        /**
         * @var DrvCatalogsRepository $catalogRepository
         */
        $catalogRepository = $this->container->get('doctrine')->getRepository(DrvCatalogs::class);

        $sParentId = $parentId;
        if ($parentId === 0) {
            $sParentId = null;
        }

        $filter = [
            'userId' => $user->getId(),
            'parentId' => $parentId,
            'isDeleted' => false,
            'isHide' => false
        ];
        if ($mode === 1) {
            unset($filter['isHide']);
        }
        $listData = $catalogRepository->findBy($filter);

        $list = [];
        $relativePath = $this->getParameter('app.wusb_catalog_root');
        $userPath = $this->generateUserPath($user->getId());

        $qntCatalogs = 0;
        $qntFiles = 0;
        $size = 0;
        foreach ($listData as $drvCatalogs) {
            $path = $request->server->get('DOCUMENT_ROOT') . $relativePath . '/' . $userPath . '/' . $drvCatalogs->getId();
            /*if (!$filesystem->exists($path) || !is_dir($path)) {
                continue;
            }*/
            $item = [
                'name' => $drvCatalogs->getName(),
                'type' => 'c',
                'i'  => $drvCatalogs->getId(),
                'h'  => $drvCatalogs->getIsHide(),
                's'  => AppService::getHumanFilesize($drvCatalogs->getSize(), 2),
                'qf' => $drvCatalogs->getQuantityFiles(),
                'qc' => $drvCatalogs->getQuantityCatalogs(),
                'ct' => AppService::sqzDatetime($drvCatalogs->getCreatedTime()),
                'ut' => AppService::sqzDatetime($drvCatalogs->getUpdatedTime()),
            ];
            $list[] = $item;
            $qntCatalogs++;
            $size += $drvCatalogs->getSize();
        }
        $realParentId = $parentId;
        $parentCatalog = $catalogRepository->find($parentId);

        $files = [];
        $parentCatalogPath = '';
        if ($parentCatalog) {
            $realParentId = $parentCatalog->getParentId();
            // TODO if mode
            $files = $parentCatalog->getFiles();
            $parentCatalogPath = '/' . $parentCatalog->getId();
        } else {
            $files = $this->container->get('doctrine')->getRepository(DrvFile::class)->findBy([
                'userId' => $user->getId(),
                'catalogId' => null,
                'isHidden' => false,
                'isDeleted' => false
            ]);
        }

        $em = $this->getDoctrine()->getManager();
        $sizeIsModify = false;
        foreach ($files as $drvFile) {
            $path = $request->server->get('DOCUMENT_ROOT') . $relativePath . '/' . $userPath . $parentCatalogPath . '/' . $drvFile->getId();
            /*if (!$filesystem->exists($path) ) {
                continue;
            }*/

            if ($drvFile->getIsDeleted() === true) {
                continue;
            }
            $currentFilesize = $drvFile->getSize();
            if (0 == $currentFilesize) {
                $pathObject = $this->getFilePathObject($drvFile, $user, $request, $filesystem);
                if (file_exists($pathObject->path)) {
                    $currentFilesize = filesize($pathObject->path);
                    $drvFile->setSize($currentFilesize);
                    $sizeIsModify = true;
                    $em->persist($drvFile);
                }
            }

            $createdTime = AppService::sqzDatetime($drvFile->getCreatedTime());
            $updatedTime = AppService::sqzDatetime($drvFile->getUpdatedTime());

            $item = [
                'name' => $drvFile->getName(),
                'type' => $drvFile->getType()[0] ?? 'u',// TODO
                'i' => $drvFile->getId(),
                'h' => $drvFile->getIsHidden(),
                's' => AppService::getHumanFilesize($currentFilesize, 2),
                'ct' => $createdTime,
                'ut' => $updatedTime,
                'ctd' => AppService::desqzDatetime($createdTime),
                'upd' => AppService::desqzDatetime($updatedTime),
            ];
            $list[] = $item;
            $size += $drvFile->getSize();
            $qntFiles++;
        }
        if ($parentCatalog) {
            $parentCatalog->setSize($size);
            $parentCatalog->setQuantityFiles($qntFiles);
            $parentCatalog->setQuantityCatalogs($qntCatalogs);
            $em->persist($parentCatalog);
            $em->flush();
        } else if ($sizeIsModify) {
            $em->flush();
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


        /**
         * @var DrvCatalogsRepository $catalogRepository
         */
        $repository = $this->container->get('doctrine')->getRepository(DrvBookmark::class);
        $entity = $repository->findOneBy(["userId" => $user->getId()]);
        $bm = [];
        if ($entity) {
            $bm = unserialize($entity->getBm());
        }

        return [
            'ls' => $list,
            'p' => $realParentId,
            'bc' => $breadCrumbs,
            "bm" => $bm
        ];
    }

    /**
     * @param $
     * @return
    */
    protected function l(TranslatorInterface $t,  $s,  $domain = 'wusb_filesystem', $params = [])
    {
        $locale = 'en';
        /**
         * @var Ausers $user
         *
        */
        $user = $this->getUser();
        if (!$user || !($user instanceof Ausers)) {
            $guestId = $this->request->cookies->get('guest_id');
            if ($guestId) {
                $this->container->get('doctrine')->getRepository(Ausers::class)->findOneBy([
                    'guestId' => $guestId
                ]);
            }
        }
        if ($user && ($user instanceof Ausers) ) {
            $x = BitReader::get(intval($user->getBSettings()), 1);
            if (1 === $x) {
                $locale = 'ru';
            }
        }
        // ($t, 'You have not access to this page', $domain)
        return $t->trans($s, $params, $domain, $locale);
    }
    /**
     * @Route("/space.json", name="drivegetspace", methods={"GET"})
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveGetSpace(Request $request,
                                               TranslatorInterface $t,
                                               Filesystem $filesystem,
                                               CsrfTokenManagerInterface $csrfTokenManager)
    {
        $user = $this->getUser();
        if (!$user) {
            $data = [
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page',  null)
            ];

            return $this->_json($data);
        }

        // Get all user files sizes
        /**
         * @var DrvFileRepository $filesRepository
        */
        $filesRepository = $this->getDoctrine()->getRepository(DrvFile::class);
        $size = $filesRepository->getCurrentSize($user);
        $allowSize = $this->getTotalSize($user);
        if ($allowSize <= $size) {
            $seconds = $this->getLegalTimeIntervalSeconds();
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'Your busy all {allowSize}',  'wusb_filesystem', [
                    '{allowSize}' => AppService::getHumanFilesize($allowSize, 0, 3, false)
                ]),
                'noLeftSpace' => 1,
                'freePD' => AppService::getHumanFilesize($filesRepository->getFreePerDay((int)$user->getId(), $seconds), 0, 3, false),
                'freePW' => AppService::getHumanFilesize($filesRepository->getFreePerWeek((int)$user->getId(), $seconds), 0, 3, false),
                'freePM' => AppService::getHumanFilesize($filesRepository->getFreePerMonth((int)$user->getId(), $seconds), 0, 3, false),
                'freeP6M' => AppService::getHumanFilesize($filesRepository->getFreePer6Months((int)$user->getId(), $seconds), 0, 3, false)
            ]);
        }

        return $this->_json(['status' => 'ok']);
    }

    private function createCatalog(Request $request, Filesystem $filesystem, $catalogId)
    {
        $relativePath = $this->getParameter('app.wusb_catalog_root');
        $userPath = $this->generateUserPath($this->getUser()->getId());
        $path = $request->server->get('DOCUMENT_ROOT') . $relativePath . '/' . $userPath . '/' . $catalogId;
        $filesystem->mkdir($path);

        return $path;
    }

    private function removeSymlink(Filesystem $filesystem, $path)
    {
        if (!empty($path) ) {
            if (file_exists($path)) {
                $filesystem->remove($path);
            }
            $a = explode('/', $path);
            unset($a[count($a) - 1]);
            $symlinkDir = implode('/', $a);
            if (file_exists($symlinkDir)) {
                rmdir($symlinkDir);
            }
        }
    }

    private function removePhisFiles($fileEntities, Ausers  $user, Request $request, Filesystem $filesystem, TranslatorInterface $t)
    {
        $success = true;
        foreach ($fileEntities as $fileEntity) {
            if ($fileEntity->getUserId() != $user->getId()) {
                continue;
            }
            if ($this->itIsLegalRm($fileEntity)) {
                if ($fileEntity->getWdPublic() == 1) {
                    $fileEntity->setIsDeleted(true);
                    $fileEntity->setIsNoErased(false);
                    $fileEntity->setWdPublic(6);
                }
                $filePathObject = $this->getFilePathObject($fileEntity, $user, $request, $filesystem);
                if (!$filePathObject->error) {
                    $success = false;
                    $error = '';

                    try {
                        if (file_exists($filePathObject->symlink)) {
                            $this->removeSymlink($filesystem, $filePathObject->symlink);
                        }

                        if (file_exists($filePathObject->path)) {
                            $filesystem->remove($filePathObject->path);
                        }
                        $success = true;
                    } catch (\Exception $exception) {
                        $error = $exception->getMessage();
                        $success = false;
                    }
                    // db
                    $fileEntity->setIsDeleted(true);
                    $fileEntity->setIsNoErased(false);
                }
            } else {
                $fileEntity->setIsDeleted(true);
            }
        }

        if (!$success) {
            return [
                'status' => 'error',
                'error' => $this->l($t, 'remove failed! ') . $error
            ];
        }

        return [];
    }

    /**
     * @Route("/drivegetfileprm.json", name="drivegetfileprm", methods={"GET"})
     * Ge tfile permissions
     * @param $
     * @return
     */
    public function driveGetFilePermission(
        Request $request,
        TranslatorInterface $t,
        FilePermissionService $filePermissionService)
    {
        if (!$this->getUser()) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', '')
            ]);
        }

        $fileId = intval($request->query->get('i') );

        if ($fileId > 0) {
            if (!$filePermissionService->isOwner($this->getUser()->getId(), $fileId, $response)) {
                return $this->_json($response);
            }
            /**
             * @var DrvFileRepository $fileRepository
             */
            $fileRepository = $this->get('doctrine')->getRepository(DrvFile::class);
            // remove phisical + symlink
            $fileEntity = $fileRepository->find($fileId);

            $response = new StdClass();
            $response->status = 'ok';
            $response->shareMode = $filePermissionService->getShareModeAsJstring($fileEntity->getId(), $fileEntity->getIsPublic());
            $response->flink = $request->server->get('HTTP_HOST')
                . '/d/drive/?action=share&i=' . $fileId;
            $response->uls = $filePermissionService->getUsersForLastFile();


            return $this->_json($response);
        }

        return $this->_json(['status' => 'ok']);
    }

    /**
     * @Route("/drivermfileprm.json", name="drivermfileprm", methods={"POST"})
     * @param $
     * @return
     */
    public function driveRemoveFilePermission(
        Request $request,
        TranslatorInterface $t,
        AppService $appService,
        FilePermissionService $filePermissionService)
    {
        if (!$this->getUser()) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', '')
            ]);
        }

        $fileId = intval($request->request->get('f') );
        $userId = intval($request->request->get('i') );

        if (!$filePermissionService->isOwner($this->getUser()->getId(), $fileId, $response)) {
            return $this->_json($response);
        }


        if ($fileId > 0 && $userId > 0) {
            /**
             * @var DrvFileRepository $filePertmissionsRepository
             */
            $filePertmissionsRepository = $this->container->get('doctrine')->getRepository(DrvFilePermissions::class);
            // remove phisical + symlink
            $entity = $filePertmissionsRepository->findOneBy([
                'userId' => $userId,
                'fileId' => $fileId
            ]);
            if ($entity) {
                $em = $this->get('doctrine')->getManager();
                $em->remove($entity);
                $em->flush();
            }

            $qnt = $filePertmissionsRepository->count([
                "fileId" => $fileId
            ]);
            if ($qnt == 0) {
                $entFile = $appService->find(DrvFile::class, $fileId);
                if ($entFile->getModeratus() == 1 && $entFile->getIsPublic() != 1) {
                    $entFile->setModeratus(0);
                    $appService->save($entFile);
                }
            }

            $response = new StdClass();
            $response->status = 'ok';
            $response->id = $userId;

            return $this->_json($response);
        }

        return $this->_json(['status' => 'ok']);
    }

    /**
     * @Route("/drivesavefileprm.json", name="drivesavefileprm", methods={"POST"})
     * @param $
     * @return
     */
    public function driveSaveFilePermission(
        Request $request,
        TranslatorInterface $t,
        FilePermissionService $filePermissionService)
    {
        if (!$this->getUser()) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', '')
            ]);
        }
        $fileId = intval($request->request->get('i') );
        $permission = intval($request->request->get('p') );

        if (!$filePermissionService->isOwner($this->getUser()->getId(), $fileId, $response)) {
            $response['error'] = $this->l($t, $response['error'], null);
            return $this->_json($response);
        }

        if ($fileId > 0) {
            $filePermissionService->saveFilePermission($fileId, (1 == $permission));
        }

        return $this->_json(['status' => 'ok']);
    }
    /**
     * @Route("/driveaddfileusr.json", name="driveaddfileuser", methods={"POST"})
     * @param $
     * @return
     */
    public function driveAddFileUser(
        Request $request,
        TranslatorInterface $t,
        FilePermissionService $filePermissionService)
    {
        if (!$this->getUser()) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', '')
            ]);
        }
        $fileId = intval($request->request->get('i') );
        $userId = intval($request->request->get('u') );

        if (!$filePermissionService->isOwner($this->getUser()->getId(), $fileId, $response)) {
            $response['error'] = $this->l($t, $response['error'], null);
            return $this->_json($response);
        }

        if ($fileId > 0) {
            $filePermissionService->addFileUser($fileId, $userId);

        }

        return $this->_json(['status' => 'ok']);
    }

    /**
     * @Route("/drivesrchusr.json", name="drivesrchusr", methods={"POST"})
     * @param $
     * @return
     */
    public function driveSearchUser(
        Request $request,
        TranslatorInterface $t,
        FilePermissionService $filePermissionService)
    {
        if (!$this->getUser()) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', '')
            ]);
        }
        $fileId = intval($request->request->get('i') );
        $fragment = strval($request->request->get('s') );

        if (!$filePermissionService->isOwner($this->getUser()->getId(), $fileId, $response)) {
            $response['error'] = $this->l($t, $response['error'], null);
            return $this->_json($response);
        }

        if ($fileId > 0) {
            $repository = $this->get('doctrine')->getRepository(Ausers::class);
            $list = $repository->searchByLogin($fragment);
            $response = new StdClass();
            $response->ls = $list;
            $response->status = 'ok';
            return $this->_json($response);
        }

        return $this->_json(['status' => 'ok']);
    }

    /**
     * @Route("/driveversion.json", name="driveversion", methods={"GET"})
     */
    public function getVersion()
    {
        return $this->_json(['v' => self::VERSION]);
    }

    private function getLegalTimeIntervalSeconds(): int
    {
        return 3600 * 24 * 193; // 193 = 366/2 + 10
    }

    private function itIsLegalRm(DrvFile $fileEntity): bool
    {
        $createdTime = $fileEntity->getCreatedTime();
        if (!$createdTime) {

            return false;
        }
        $ts = $createdTime->getTimestamp();

        return ((time() - $ts) > $this->getLegalTimeIntervalSeconds());
    }

    private function reachCatalogList(array $catalogIdList, int $userId, AppService $oAppService): array
    {
        TreeAlgorithms::$parentIdFieldName = 'parentId';
        $aFlatList = $this->reachCatalogListGetFlatSource($userId, $oAppService);

        $aTrees = TreeAlgorithms::buildTreeFromFlatList($aFlatList);
        $res = [];
        foreach ($catalogIdList as $catalogId) {
            $subcatalogs = $this->reachCatalogGetBranch($aTrees, $catalogId);
            $res = array_merge($res, $subcatalogs);
        }

        $res = array_unique($res);

        return $res;
    }

    private function reachCatalogGetBranch(array $trees, int $catalogId): array
    {
        foreach ($trees as $tree) {
            $node = TreeAlgorithms::findById($tree, $catalogId);
            if (!$node) {
                continue;
            }
            $list = TreeAlgorithms::getBranchIdList($node);

            if (!$list) {
                continue;
            }

            return $list;
        }

        return [$catalogId];
    }

    private function reachCatalogListGetFlatSource(int $userId, AppService $appService): array
    {
        /**
         * @var DrvCatalogsRepository $repository
        */
        $repository = $appService->repository(DrvCatalogs::class);

        return $repository->getFlatIdListByUserId($userId);
    }

    private function getTotalSize(Ausers  $user): int
    {
        if (in_array($user->getId(), [33])) {
            return 1024*1024*1024 * 100;
        }
        return intval($this->getParameter('app.wusb_max_space') );

    }

    /**
     * @Route("/wisbmark.json", name="drivesyncbookmark")
     * @param Request $oRequest
     * @param TranslatorInterface $t
     * @param $
     * @return
     */
    public function driveSyncBookmarksAction(Request $request,
                                          TranslatorInterface $t,
                                          CsrfTokenManagerInterface $csrfTokenManager)
    {
        $csrfToken = $csrfTokenManager
            ? $csrfTokenManager->getToken('authenticate')->getValue()
            : null;
        if ($csrfToken != $request->request->get('_token')) {
            $domain = null;
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page', $domain)
            ]);
        }

        // Create db record
        /**
         * @var DrvCatalogsRepository $catalogRepository
         */
        $repository = $this->container->get('doctrine')->getRepository(DrvBookmark::class);
        $repository->save(serialize(json_decode($request->get('ls'))), $this->getUser()->getId());

        return $this->_json([
            "status" => "ok"
        ]);

    }

    /**
     * @Route("/wusbmodls.json", name="drivelsmod")
     */
    public function driveGetModerationListAction(Request $request,
                                          AppService $appService,
                                          TranslatorInterface $t,
                                          Filesystem $filesystem,
                                          UserService $userService
    )
    {
        if (!$userService->isAdmin($this->getUser())) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }
        $r = $appService->repository(DrvFile::class);
        $raw = $r->findBy([
            "moderatus" => 1
        ], ["createdTime" => "DESC"]);
        $ls = [];
        /**
         * @var DrvFile $ent
        */
        foreach ($raw as $ent) {
            $ls[] = [
                "name" => $ent->getName(),
                "i" => (int)$ent->getId(),
                "L" => $this->getAdminFileLink($ent, $request, $filesystem, $t, $appService),
                "s" => AppService::getHumanFilesize($ent->getSize(), 1, 2, false)

            ];
        }

        return $this->_json([
            "status" => "ok",
            "ls" => $ls
        ]);
    }

    /**
     * @Route("/moderatus", name="wusbmoderatus")
     */
    public function index(UserService $userService, AppService $appService)
    {
        $this->_oAppService = $appService;
        if (!$userService->isAdmin($this->getUser())) {
            return $this->redirectToRoute('home');
        }
        return $this->render('wusb_moderatus/index.html.twig', [
            'controller_name' => 'UsbController',
            'pageHeading' => 'Понашерили...',
            'bEnableChooseSiteVersionButton' => true,
        ]);
    }

    private function getAdminFileLink(DrvFile $fileEntity, Request $request, Filesystem $filesystem, TranslatorInterface $t, AppService  $appService): string
    {
        $user = $appService->find(Ausers::class, $fileEntity->getUserId());
        if (null === $user) {
            return '#';
        }
        $filePathObject = $this->getFilePathObject($fileEntity, $user, $request, $filesystem);
        $path = $filePathObject->path;

        if (!empty($filePathObject->error)) {
            return "javascript:alert({$this->l($t, $filePathObject->error)})";;
        }
        $symlink = $filePathObject->symlink;

        if (!$filesystem->exists($symlink) && $filesystem->exists($path)) {
            symlink($path, $symlink);
        }
        if (!$filesystem->exists($symlink)) {
            return "javascript:alert({$this->l($t, 'Unable create copy')})";;

        }

        return str_replace($request->server->get('DOCUMENT_ROOT'), '', $symlink);
    }

    /**
     * @Route("/wusbmodrm.json", name="drivemodrm")
     */
    public function driveModerationRmAction(Request $request,
                                                 AppService $appService,
                                                 TranslatorInterface $t,
                                                 UserService $userService
    )
    {
        if (!$userService->isAdmin($this->getUser())) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }
        $r = $appService->repository(DrvFile::class);
        $id = $request->get('i');
        /**
         * @var DrvFile $ent
        */
        $ent = $r->find($id);
        if ($ent) {
            $ent->setModeratus(3);
            $ent->setIsDeleted(true);
            $ent->setIsNoErased(true);
            $ent->setWdPublic(6);
            $appService->save($ent);

            $userId = $ent->getUserId();
            /**
             * @var Ausers $user
            */
            $user = $appService->find(Ausers::class, strval($userId));
            if ($user) {
                $cnt = $user->getBanCount();
                ++$cnt;
                $user->setBanCount($cnt);
                if ($cnt >= 3) {
                    $nR = 0;
                    $appService->query("UPDATE drv_file SET is_deleted = 1, wd_public = 6 WHERE user_id = :userId", $nR, [
                        'userId' => $user->getId()
                    ]);
                    $bannedUser = new BanUsers();
                    $methods = get_class_methods($user);
                    foreach($methods as $method) {
                        if (strpos($method, 'get') === 0) {
                            $v = $user->$method();
                            $method = str_replace('get', 'set', $method);
                            if(method_exists($user, $method)) {
                                $bannedUser->$method($v);
                            }
                        }
                    }
                    $appService->save($bannedUser);
                    if ($user->getRole() != 2 && $user->getRole() != 1) {
                        $this->sendBanLetter($user, $appService);
                        $appService->remove($user);
                    }
                } else {
                    $appService->save($user);
                }
            }

            return $this->_json([
                "status" => "ok",
                "i" => $id
            ]);
        }

        return $this->_json([
            "status" => "err"
        ]);
    }

    public function sendBanLetter(Ausers $user, AppService $appService)
    {
        $siteAdminEmail = $this->getParameter('app.siteAdminEmail');
        $subject = $appService->l($user, 'You banned in service ') . $this->getParameter('app.siteName');

        $sHtml = '<p> ' .
            $appService->l($user, 'You banned in service') . ' '
            . ' '. $this->getParameter('app.siteName') . '</p>';
        $sHtml .= '<p> ' .
            $appService->l($user, 'Reason') . ': '
            . $appService->l($user, 'Inappropriate content.')
            . '</p>';
        $mail = new SimpleMail();
        $mail->setSubject($subject);
        $mail->setFrom($siteAdminEmail);
        $mail->setTo($user->getEmail());
        $mail->setBody($sHtml, 'text/html', 'UTF-8');
        $mail->send();
    }

    /**
     * @Route("/wusbmodok.json", name="wusbmodok")
     */
    public function driveModerationOkAction(Request $request,
                                            AppService $appService,
                                            TranslatorInterface $t,
                                            UserService $userService
    )
    {
        if (!$userService->isAdmin($this->getUser())) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }
        $r = $appService->repository(DrvFile::class);
        $id = $request->get('i');
        $ent = $r->find($id);
        if ($ent) {
            $ent->setModeratus(2);
            $appService->save($ent);
            return $this->_json([
                "status" => "ok",
                "i" => $id
            ]);
        }

        return $this->_json([
            "status" => "err"
        ]);
    }

    /**
     * @Route("/wusbmodjpg.json", name="wusbmodjpg")
     */
    public function driveModerationGetJpgAction(Request $request,
                                            AppService $appService,
                                            Filesystem $filesystem,
                                            TranslatorInterface $t,
                                            UserService $userService
    )
    {
        if (!$userService->isAdmin($this->getUser())) {
            return $this->_json([
                'status' => 'error',
                'error' => $this->l($t, 'You have not access to this page')
            ]);
        }
        $r = $appService->repository(DrvFile::class);
        $id = $request->get('i');
        $ent = $r->find($id);
        if ($ent) {
            $filePathObject = $this->getFilePathObject($ent, $this->getUser(), $request, $filesystem);
            $path = $filePathObject->path;

            if (!empty($filePathObject->error)) {
                return $this->_json([
                    "status" => "err",
                    "msg" => $this->l($t, $filePathObject->error)
                ]);
            }

            $tmp = sys_get_temp_dir() . '/tmp.jpg';
            $this->resizeAndAddBg($path, $tmp, 320, 480, [0, 0, 0]);
            $data = base64_encode(file_get_contents($tmp));


            return $this->_json([
                "status" => "ok",
                "d" => $data
            ]);
        }

        return $this->_json([
            "status" => "err"
        ]);
    }

    private function resizeAndAddBg($srcPath, $destPath, $nWidth, $nHeight, $color)
    {
        if (!file_exists($srcPath)) {
            return;
        }
        $image = new \Imagick($srcPath);
        $sz = $image->getImageGeometry();
        $srcW = $sz['width'];
        $srcH = $sz['height'];
        $isLandscape = $srcW > $srcH;

        $isSrcLgBg = $srcW > $nWidth || $srcH > $nHeight;
        $newW = $srcW;
        $newH = $srcH;

        //это случай, когда изображение больше фона
        if ($isSrcLgBg) {
            if ($isLandscape) {
                $nScale = $nWidth / $srcW;
            } else {
                $nScale = $nHeight / $srcH;
            }
            $newW = round($srcW * $nScale);
            $newH = round($srcH * $nScale);
        }

        $im = new \Imagick();
        $im->readImage($srcPath);
        $im->resizeImage($newW, $newH,\Imagick::FILTER_CATROM , 1,TRUE );
        $im->setImageFormat("jpg");
        $im->writeImage($destPath);
    }

    /**
     * @Route("/drivesetadv.json", name="drivesetadvstate")
     */
    public function driveSetAdvStateAction(
      Request $request,
      TranslatorInterface $t,
      AppService $appService
    )
    {
        $state = intval($request->request->get("s"));
        $user = $this->getUser();
        /**
         * @var AUsers $user
        */
        if ($user && method_exists($user, 'setAdvAgree')) {
            $user->setAdvAgree($state);
            $appService->save($user);
        }
        return $this->_json(["state" => $state]);
    }
}

