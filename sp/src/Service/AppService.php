<?php
namespace App\Service;

use App\Entity\CrnIntervals;
use App\Entity\CrnTasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManager;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use App\Entity\Main;
//use \Landlib\Text2Png;
use Doctrine\Common\Collections\Criteria;
//use Landlib\RusLexicon;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityRepository;

class AppService
{

	/** @property FormInterface $_oForm Сюда можно передать форму для более простой работы с ними */
	private $_oForm;

	public function __construct(ContainerInterface $container, ?ViewDataService $oViewDataService = null, ?FileUploaderService $oFileUploaderService = null)
	{
		$this->oContainer = $container;
		$this->translator = $container->get('translator');
		$this->oViewDataService = $oViewDataService;
		$this->oFileUploaderService = $oFileUploaderService;
	}
	/**
	 * Выводит тип перевозки (например, "Грузовая, термобудка" или "Пассажирская")
	 * @param \App\Entity\Main $oItem
	 * @return string
	*/
	public function getCarsTypes(Main $oItem) : string
	{
		$a = [];
		if ($oItem->getBox()) {
			$a[] = $this->translator->trans('Avenger'); 
		}
		
		if ($oItem->getPeople()) {
			$a[] = $this->translator->trans('Passenger'); 
		}
		
		if ($oItem->getTerm()) {
			$a[] = $this->translator->trans('Termobox'); 
		}
		$s = join($a, ', ');
		$s = mb_strtolower($s, 'utf-8');
		$s = $this->capitalize($s);
		return $s;
	}
	/**
	 * Возвращает строку байтов PNG изображения номера телефона
	 * @param int $nId идентификатор объявления 
	 * @return void
	*/
	public function getPhoneAsImage(int $nId):void
	{
		$oRepository = $this->oContainer->get("doctrine")->getRepository("App:Main");
		$aPhone = $oRepository->createQueryBuilder('m')
            ->andWhere('m.id = :id')
            ->setParameter('id', $nId)
            ->select('m.phone')
            ->getQuery()
			->getOneOrNullResult();
		$sPhone = $this->translator->trans('Phone not found');
		if ($aPhone) {
			$sPhone = ($aPhone['phone'] ?? $sPhone);
			$sPhone = $this->formatPhone($sPhone);
		}
		$oT2p = new Text2Png($sPhone);
		$oT2p->setFontSize(24);
		$oT2p->pngResponse();
	}
	/**
	 * Возвращает отформатированый телефонный номер, например "8 (xxx)xxx-xx-xx"
	 * @param string sPhone телефон
	 * @return string
	*/
	public function formatPhone(string $sPhone):string
	{
		$s = $sPhone;
		if (strlen($s) < 11) {
			return $s; 
		}
		$a = [];
		for ($i = strlen($s) - 1, $j = 1; $i > -1; $i--, $j++) {
			$a[] = $s[$i];
			if ($j == 2 || $j == 4) {
				$a[] = '-';
			}
			if ($j == 10) {
				$a[] = '(';
			}
			if ($j == 7) {
				$a[] = ')';
			}
		}
		$s = join('', array_reverse($a));
		return $s;
	}
	/**
	 * Получает актуальный тайтл для страницы со списком объявлений
	 * @param Request $request
	 * @param string $sRegionName кириллическое значение
	 * @param string $sCityName кириллическое значение
	 * @param string string $sAdvTitle = '' Заголовок объявления (указанный пользователем при подаче)
	 * @return string
	*/
	public function getTiltle(Request $request, string $sRegionName, string $sCityName, string $sAdvTitle = '') : string
	{
		$sUrl = $request->server->get('REQUEST_URI');
		$aUrl = explode('?', $sUrl);
		$sUrl = $aUrl[0];
		if ($sUrl == '/') {
			return $this->translator->trans('Default title');
		}
		return $this->getMainHeading($request, $sRegionName, $sCityName, $sAdvTitle);
	}
	/**
	 * Получает актуальный заголовок для страницы со списком объявлений
	 * @param Request $request
	 * @param string $sRegionName кириллическое значение
	 * @param string $sCityName кириллическое значение
	 * @param string string $sAdvTitle = '' Заголовок объявления (указанный пользователем при подаче)
	 * @return string
	*/
	public function getMainHeading(Request $request, string $sRegionName, string $sCityName, string $sAdvTitle = '') : string
	{
		$sUrl = $request->server->get('REQUEST_URI');
		$aUrl = explode('?', $sUrl);
		$sUrl = $aUrl[0];
		if ($sUrl == '/') {
			return $this->translator->trans('Default heading');
		}
		$s = '';
		if ($sCityName && $sRegionName) {
			$s = $this->_modCityName($sCityName);
			$s .= ', ' . $sRegionName;
		} else if ($sRegionName) {
			$s = $this->_modCityName($sRegionName);
		} else  {
			return $this->translator->trans('Default heading');
		}
		$sAdvTitle = trim($sAdvTitle);
		if ($sAdvTitle) {
			$sAdvTitle = ' | ' . $sAdvTitle;
		}
		return $this->translator->trans('Get car in') . ' ' . $s . $sAdvTitle;
	}
	
	/**
	 * Изменяет имя города в соответствии с правилами русского языка, ответ на вопрос "где?"
	 * @param string $s ожидается например "Астрахань, Астраханская область" или "Астраханская область" или "Астрахань"
	 * @return string
	*/
	private function _modCityName(string $s) : string
	{
		return RusLexicon::getCityNameFor_In_the_City($s);
	}
	/**
	 * @param string $s
	 * @return
	**/
	public function cp1251(string $s) : string
	{
		return RusLexicon::cp1251($s);
	}
	//candidate
	/**
	 * Перевести первый символ в верхний регистр
	**/
	public function capitalize(string $s) : string
	{
		$enc = mb_detect_encoding($s, array('UTF-8', 'Windows-1251'));
		$us = mb_convert_case($s, 0, $enc);
		$first_char = mb_substr($us, 0, 1, $enc);
		$tail = mb_substr($s, 1, 1000000, $enc);
		return ($first_char . $tail);
	}
	/**
	 * Тут приходится использовать "кривой" транслит, потому что он уже есть в базе и сайт при этом есть в выдаче Яндекса
	**/
	public function translite_url(string $string) : string
	{
		$string = str_replace('ё','e',$string);
		$string = str_replace('й','i',$string);
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
		$string = str_replace('Й','I',$string);
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
		$string = str_replace('.','',$string);
		$string = str_replace("'",'',$string);
		$string = str_replace(",",'',$string);
		$string = str_replace('\\', '', $string);
		$string = str_replace('?', '', $string);
		return strtolower($string);
	}
	/**
	 * @description
	 * @param 
	 * @return ViewDataService
	*/
	public function getViewDataService() : ViewDataService
	{
		return $this->oViewDataService;
	}
	/**
	 * @description
	 * @param 
	 * @return ViewDataService
	*/
	public function getFileUploaderService() : FileUploaderService
	{
		return $this->oFileUploaderService;
	}
	/**
	 * Добавит в $aWhere фильтр по городу и/или региону
	 * Инициализует кириллические имена города и региона
	 * @param \Doctrine\ORM\QueryBuilder $oQueryBuilder ('App:Main AS  m') для запроса выборки объявлений, @see AdvertListController::_loadAdvList
	 * @param string &$sCyrRegionName
	 * @param string &$sCyrCityName
	 * @param string $sRegion = '' код региона латинскими буквами
     * @param string $sCity = ''   код города латинскими буквами
	*/
	public function setCityConditionAndInitCyrValues(\Doctrine\Common\Collections\Criteria $oCriteria, &$sCyrRegionName, &$sCyrCityName, $sRegion, $sCity, &$nCityId, &$nRegionId) : void
	{
		if ($sRegion) {
			//всегда сначала загружаем по региону
			$oRepository = $this->oContainer->get('doctrine')->getRepository('App:Regions');
			//TODO use Criteria. How use RegionsRepository??
			/*$aRegions = $oRepository->findBy([
				'codename' => $sRegion
			]);*/
			$aRegions = $this->oContainer->get('App\Repository\RegionsRepository')->findByCodename($sRegion);
			if ($aRegions) {
				$oRegion = current($aRegions);
				if ($oRegion) {
					//$aWhere['region'] = $oRegion->getId();
					$e = Criteria::expr();
					$nRegionId = $oRegion->getId();
					$oCriteria->andWhere( $e->eq('region', $oRegion->getId()) );
					$sCyrRegionName = $oRegion->getRegionName();
					if ($sCity) {
						//Тут в любом случае будет не более десятка записей для сел типа Крайновка или Калиновка. Отфильровать на php
						$aCities = $oRegion->getCities();
						foreach($aCities as $oCity) {
							if ($oCity->getCodename() == $sCity) {
								$sCyrCityName = $oCity->getCityName();
								//$aWhere['city'] = $oCity->getId();
								$nCityId = $oCity->getId();
								$oCriteria->andWhere( $e->eq('city', $oCity->getId()) );
								break;
							}
						}
					}
				}
			}
		}
	}
	
	public function getAuthUser()
	{
		$oUser = $this->oContainer->get('security.token_storage')->getToken()->getUser();
		return $oUser;
	}
	/**
	 * Добавляет $oBuilder поле для загрузки файла со всеми необходимыми параметрами
	*/
	public function addPsdField(string $sUploadDirectory, FormBuilder $oBuilder, string $sFieldName = 'imagefile')
	{
		$sTranslationDomain = 'Psdform';//TODO create file from gz Adform.ru.yml
		$oFileUploader = $this->getFileUploaderService();
		$oFileUploader->setTranslationDomain($sTranslationDomain);
		$oRequest = $this->oContainer->get('request_stack')->getCurrentRequest();
		$oFileUploader->addAllowMimetype('image/vnd.adobe.photoshop');
		//$oFileUploader->addAllowMimetype('image/jpeg');
		$oFileUploader->setFileInputLabel('Append file!');
		$oFileUploader->setMimeWarningMessage('Choose allowed file type');
		//$oFileUploader->addLiipBundleFilter('max_width');

		$subdir = $sUploadDirectory;
		$sTargetDirectory = $oRequest->server->get('DOCUMENT_ROOT') . '/' . $subdir;

		$oFileUploader->setTargetDirectory($sTargetDirectory);

		$aOptions = $oFileUploader->getFileTypeOptions();
		/*$aOptions['attr'] = [
			'style' => 'width:173px;',
			'v-if'  => '!vueFileInputIsEnabled'
		];*/
		$aOptions['translation_domain'] = $sTranslationDomain;
		$oBuilder->add($sFieldName, \Symfony\Component\Form\Extension\Core\Type\FileType::class, $aOptions);
	}

	/**
	 * Добавляет $oBuilder поле для загрузки файла со всеми необходимыми параметрами
	 */
	public function addZipFileField(string $sUploadDirectory, FormBuilder $oBuilder, string $sFieldName = 'imagefile')
	{
		$sTranslationDomain = 'Zipform';//TODO create file from gz Adform.ru.yml
		$oFileUploader = $this->getFileUploaderService();
		$oFileUploader->setTranslationDomain($sTranslationDomain);
		$oRequest = $this->oContainer->get('request_stack')->getCurrentRequest();
		$oFileUploader->addAllowMimetype('application/zip');
		$oFileUploader->setFileInputLabel('Append file!');
		$oFileUploader->setMimeWarningMessage('Choose allowed file type');
		$oFileUploader->setMaxFileSize(102400);

		$subdir = $sUploadDirectory;
		$sTargetDirectory = $oRequest->server->get('DOCUMENT_ROOT') . '/' . $subdir;

		$oFileUploader->setTargetDirectory($sTargetDirectory);

		$aOptions = $oFileUploader->getFileTypeOptions();

		$aOptions['translation_domain'] = $sTranslationDomain;
		$oBuilder->add($sFieldName, \Symfony\Component\Form\Extension\Core\Type\FileType::class, $aOptions);
	}
	/**
	 * Добавляет $oBuilder поле для загрузки файла со всеми необходимыми параметрами
	*/
	public function addUserLogoFileField(string $sUploadDirectory, FormBuilder $oBuilder, string $sFieldName = 'imagefile')
	{
		$sTranslationDomain = 'profileform';
		/** @var \App\Service\FileUploaderService $oFileUploader */
		$oFileUploader = $this->getFileUploaderService();
		$oFileUploader->setTranslationDomain($sTranslationDomain);
		$oRequest = $this->oContainer->get('request_stack')->getCurrentRequest();
		$oFileUploader->addAllowMimetype('image/png');
		$oFileUploader->addAllowMimetype('image/jpeg');
		$oFileUploader->setFileInputLabel('Select logotype');//TODO
		$oFileUploader->setMimeWarningMessage('Choose allowed file type');
		$oFileUploader->setMaxFileSize(4096);
		$oFileUploader->setMaxImageHeight(400);
		$oFileUploader->setMaxImageWidth(400);
		//$oFileUploader->addLiipBundleFilter('max_width');

		$subdir = $sUploadDirectory;
		$sTargetDirectory = $oRequest->server->get('DOCUMENT_ROOT') . '/' . $subdir;

		$oFileUploader->setTargetDirectory($sTargetDirectory);

		$aOptions = $oFileUploader->getFileTypeOptions();
		$aOptions['translation_domain'] = $sTranslationDomain;
		$oBuilder->add($sFieldName, \Symfony\Component\Form\Extension\Core\Type\FileType::class, $aOptions);
	}
	/**
	 * Добавляет $oBuilder поле для загрузки файла со всеми необходимыми параметрами
	 */
	public function addBigImageFileField(string $sUploadDirectory, FormBuilder $oBuilder, string $sFieldName = 'imagefile')
	{
		$sTranslationDomain = 'Psdform';
		/** @var \App\Service\FileUploaderService $oFileUploader */
		$oFileUploader = $this->getFileUploaderService();
		$oFileUploader->setTranslationDomain($sTranslationDomain);
		$oRequest = $this->oContainer->get('request_stack')->getCurrentRequest();
		$oFileUploader->addAllowMimetype('image/png');
		$oFileUploader->addAllowMimetype('image/jpeg');
		$oFileUploader->setFileInputLabel('Append file!');
		$oFileUploader->setMimeWarningMessage('Choose allowed file type');
		$oFileUploader->setMaxFileSize(102400);
		$oFileUploader->setMaxImageHeight(1024000000);
		$oFileUploader->setMaxImageWidth(1024000000);
		//$oFileUploader->addLiipBundleFilter('max_width');

		$subdir = $sUploadDirectory;
		$sTargetDirectory = $oRequest->server->get('DOCUMENT_ROOT') . '/' . $subdir;

		$oFileUploader->setTargetDirectory($sTargetDirectory);

		$aOptions = $oFileUploader->getFileTypeOptions();
		/*$aOptions['attr'] = [
			'style' => 'width:173px;',
			'v-if'  => '!vueFileInputIsEnabled'
		];*/
		$aOptions['translation_domain'] = $sTranslationDomain;
		$oBuilder->add($sFieldName, \Symfony\Component\Form\Extension\Core\Type\FileType::class, $aOptions);
	}

	/**
	 * @param string $sError
	 * @param string $sField
	 * @param FormInterface $oForm
	 **/
	public function addFormError(string $sError, string $sField, ?FormInterface $oForm = null)
	{
		if ($oForm) {
			$this->setForm($oForm);
		}
		$oError = new \Symfony\Component\Form\FormError($this->translator->trans($sError));
		$this->_oForm->get($sField)->addError($oError);
	}
	/**
	 * @param string $sError
	 * @param string $sField
	 * @param FormInterface $oForm
	 **/
	public function setForm(FormInterface $oForm)
	{
		$this->_oForm = $oForm;
	}
	/**
	 * Получить ассоциативный массив сообщений об ошибках
	 * @param FormInterface $oForm
	 * @return array
	*/
	public function getFormErrorsAsArray(FormInterface $oForm) : array
	{
		$aResult = [];
		$nSz = $oForm->getErrors(true)->count();
		if ($nSz) {
			$oCurrentError = $oForm->getErrors(true)->current();
			$sKey = $oCurrentError->getOrigin()->getConfig()->getName();
			$sMessage = $oCurrentError->getMessage();
			$aResult[$sKey] = $sMessage;
		}
		for ($i = 0; $i < $nSz - 1; $i ++) {
			$oCurrentError = $oForm->getErrors(true)->next();
			if (!$oCurrentError) {
				continue;
			}
			$sKey = $oCurrentError->getOrigin()->getConfig()->getName();
			$sMessage = $oCurrentError->getMessage();
			$aResult[$sKey] = $sMessage;
		}
		return $aResult;
	}
	/**
	 * @param FormInterface $oForm
	 * @return
	*/
	public function getFormTokenValue(FormInterface $oForm)
	{
		return $oForm->createView()->children['_token']->vars['value'];
	}
	/**
	 * Удаляет из номера телефона всё, кроме цифр. Ведущий +7 меняет на 8.
	 * @param string $sPhone
	 * @return string
	*/
	public function normalizePhone(string $sPhone) : string
	{
		$phone = trim($sPhone);
		$plus = 0;
		if (isset($phone[0]) && $phone[0] == '+') {
			$plus = 1;
		}
		$s = trim(preg_replace("#[\D]#", "", $phone));
		if ($plus && strlen($s) > 10) {
			$code = substr($s, 0, strlen($s) - 10 );
			$tail = substr($s, strlen($s) - 10 );
			$code++;
			$s = $code . $tail;
		} elseif($plus) {
			$s = '';
		}
		return $s;
	}
	/**
	 * Вернет true если email валидный
	 * @param string $sEmail
	 * @return bool
	*/
	public function isValidEmail(string $sEmail) : bool
	{
		$reg = "#^[\w\.]+[^\.]@[\w]+\.[\w]{2,4}#";
		$n = preg_match($reg, $sEmail, $m);
		return $n;
	}

	/**
	 *
	 * @return \DateTime
	*/
	public function now() : \DateTime
	{
		return new \DateTime();
	}

	public function createDir(string $sDir)
	{
		$a = explode('/', $sDir);
		$aB = ['/'];
		foreach ($a as $s) {
			$aB[] = $s;
			$sPath = join('', $aB);
			if (!file_exists($sPath)) {
				@mkdir($sPath, 755);
			}
			$aB[] = '/';
		}
	}
	/**
	 * bool $isConsole = false
	*/
	public function getUnprocessedPhdMessages(bool $isConsole = false) : array
	{
		$oRepository = $this->oContainer->get('doctrine')->getRepository('App:PhdMessages');
		$oCriteria = Criteria::create();
		$ex = Criteria::expr();

		if ($isConsole){
			/*
			 * SELECT * FROM phd_messages WHERE
			 * 		is_closed != 1
			 * ORDER BY id
			*/
			$oCriteria->where( $ex->neq('isClosed', 1) );
			return $oRepository->matching($oCriteria)->toArray();
		}
		/*
		 * SELECT * FROM phd_messages WHERE
		 * 		is_closed != 1
		 *     AND (operatior_id = 0 OR operatior_id = Im)
		 * ORDER BY id
		*/
		$oUser = null;
		$oToken = $this->oContainer->get('security.token_storage')->getToken();
		if ($oToken) {
			$oUser = $oToken->getUser();
		}
		if (!$oUser || is_string($oUser)) {
			var_dump( $this->oContainer->getParameter('kernel.environment') );
			//if (isConsole)
			return [];
		}

		$nId = $oUser->getId();
		$oCriteria->where( $ex->andX( $ex->neq('isClosed', 1), $ex->orX( $ex->eq('operatorId', 0), $ex->eq('operatorId', $nId) )  ) )
			->orderBy(['id' => 'ASC']);
		return $oRepository->matching($oCriteria)->toArray();
	}
	/**
	 *
	 * @param array $aData
	 * @return  Response
	*/
	public function json (array $aData)
	{
		if (!isset($aData['status'])) {
			$aData['status'] = 'ok';
		}
		$oResponse = new Response( json_encode($aData) );
		$oResponse->headers->set('Content-Type', 'application/json');
		return $oResponse;
	}
	/**
	 * @param string $id for example 'App:Users'
	 * @return ?ServiceEntityRepositoryInterface
	 */
	public function repository(string $id) : ?EntityRepository
	{
		return $this->oContainer->get('doctrine')->getRepository($id);
	}
	/**
	 *
	 * @return Request
	*/
	public function request() : Request
	{
		return $this->oContainer->get('request_stack')->getCurrentRequest();
	}
	/**
	 * Сохраняет модели в базе
	 * Аргументы - оюбъекты Entity
	*/
	public function save()
	{
		$oEm = $this->oContainer->get('doctrine')->getManager();
		$nSz = func_num_args();
		for ($i = 0; $i < $nSz; $i++) {
			$o = func_get_arg($i);
			if ($o) {
				$oEm->persist($o);
			}
		}
		$oEm->flush();
	}

	/**
	 * Останавливает текущую задачу пользователя и запускает переданную
	 * @param CrnTask $oTask
	 * @param int $nUserId
	 * @param bool $bImmediatleSave = false
	 * @return StdClass {stoppedTask:int, runnedTask:int}
	*/
	public function runTask(CrnTasks $oTask, int $nUserId, bool $bImmediatleSave = false) : \StdClass
	{
		$oResult = new \StdClass();
		$oResult->stoppedTask = 0;
		$oResult->runnedTask = $oTask->getId();
		//Найти задачу пользователя с executing = 1
		/** @var EntityManager $oEm */
		$oRepository = $this->repository('App:CrnTasks');
		$oRunnedTask = $oRepository->findOneBy(['ausersId' => $nUserId, 'isExecuted' => true]);
		$oInterval = null;
		if ($oRunnedTask) {
			if ($oRunnedTask->getId() == $oTask->getId()) {
				return $oResult;
			}
			$this->stopTask($oRunnedTask, $oInterval);
			$oResult->stoppedTask = $oRunnedTask->getId();
		}
		//установить переданной задаче executing = 1
		$oTask->setIsExecuted(true);

		//Вставить в intervals запись с переданной задачей и start = now
		$oNewInterval = new CrnIntervals();
		$oNewInterval->setTaskId($oTask->getId());
		$oNewInterval->setStartDatetime($this->now());
		//TODO вернуть true если транзакция завершилась успешно
		if ($bImmediatleSave) {
			$this->save($oTask, $oNewInterval, $oInterval, $oRunnedTask);
		} else {
			$this->save($oNewInterval, $oInterval, $oRunnedTask);
		}
		return $oResult;
	}

	/**
	 *  Я - недоверчивый, пусть пока полежит
	 * Останавливает текущую задачу пользователя и запускает переданную
	 * @param CrnTask $oTask
	 * @param int $nUserId
	 * @param bool $bImmediatleSave = false
	*/
	public function _____runTask(CrnTasks $oTask, int $nUserId, bool $bImmediatleSave = false)
	{
		//Найти задачу пользователя с executing = 1
		/** @var EntityManager $oEm */
		$oEm = $this->oContainer->get('doctrine')->getManager();
		$oQueryBuilder = $oEm->createQueryBuilder();
		$e = $oQueryBuilder->expr();
		$oQueryBuilder
			->select('t.id')
			->from('App:CrnTasks', 't')
			->where($e->eq('t.ausersId', $nUserId))
			->andWhere($e->eq('t.isExecuted', true))
			->setMaxResults(1);

		$aData = $oQueryBuilder->getQuery()->execute();
		$oEm->beginTransaction();
		if (isset($aData[0])) {
			//TODO test it
			$aRunnedTask = $aData[0];
			//Найти последний интервал этой задачи с start != null and end == null
			$oQueryBuilder = $oEm->createQueryBuilder();
			$oQueryBuilder
				->select('i.id')
				->from('App:CrnIntervals', 'i')
				->where($e->eq('i.taskId', $aRunnedTask['id']))
				->andWhere($e->isNotNull('i.startDatetime'))
				->andWhere($e->isNull('i.endDatetime'))
				->setMaxResults(1)
				->orderBy('i.id DESC');
			$aIntervals = $oQueryBuilder->getQuery()->execute();
			//установить end задачи в текущее время
			if (isset($aIntervals[0])) {
				$oQueryBuilder = $oEm->createQueryBuilder();
				$oQueryBuilder
					->update('App:CrnIntervals', 'i')
					->set('i.endDatetime', $this->now())
					->where($e->eq('i.id', $aIntervals[0]['id']));
				$oQueryBuilder->getQuery()->execute();
			}
			//установить найденной задаче executing = 0
			$oQueryBuilder = $oEm->createQueryBuilder();
			$oQueryBuilder
				->update('App:CrnTasks', 't')
				->set('i.isExecuted', false)
				->where($e->eq('t.id', $aRunnedTask['id']));
			$oQueryBuilder->getQuery()->execute();
		}
		//установить переданной задаче executing = 1
		$oTask->setIsExecuted(true);
		if ($bImmediatleSave) {
			$this->save($oTask);
		}
		//Вставить в intervals запись с переданной задачей и start = now
		$oQueryBuilder = $oEm->createQueryBuilder();
		$oQueryBuilder
			//->('App:CrnTasks', 't')
			->set('i.isExecuted', false)
			->where($e->eq('t.id', $aRunnedTask['id']));
		$oQueryBuilder->getQuery()->execute();
		//вернуть true если транзакция завершилась успешно
	}
	/**
	 *
	 * @param string $sRepositoryId
	 * @param int $nEntityId
	*/
	public function deleteEntity(string $sRepositoryId, int $nEntityId)
	{
		$oRepository = $this->repository($sRepositoryId);
		if ($oRepository) {
			$oEntity = $oRepository->find($nEntityId);
			if ($oEntity) {
				$oEm = $this->oContainer->get('doctrine')->getManager();
				/** @var EntityManager $oEm */
				$oEm->remove($oEntity);
				$oEm->flush();
			}
		}
	}
	/**
	 * Обновить поля времени, затраченного на задачу
	 * @param CrnTasks $oRunnedTask
	 * @param CrnIntervals $oInterval последний на момент выполнения интервал задачи, его поле end ещё не сохранено в базе данных, ноу уже установлено в этом аргументе
	*/
	private function _recalculateTaskTime(CrnTasks $oRunnedTask, CrnIntervals $oInterval) : void
	{
		//Обновить поля переданной в аргументе задачи
		$nSecondsTime = $this->_getAllTaskIntervalsAsSeconds($oRunnedTask, $oInterval);
		$this->_setTaskTimeIntervalDisplayFields($oRunnedTask, $nSecondsTime);
		//Обновить поля её родительской задачи
		// - Дойти до вершины дерева
		$this->_setParentTasksTimeIntervalsRecursive($oRunnedTask);
	}
	/**
	 *
	 * @param CrnTasks $oRunnedTask
	*/
	private function _setParentTasksTimeIntervalsRecursive(CrnTasks $oTask) : void
	{
		$nParentId = $oTask->getParentId();
		if ($nParentId == 0) {
			return;
		}
		$oRepository = $this->repository('App:CrnTasks');
		$oParentTask = $oRepository->find($nParentId);
		if (!$oParentTask) {
			return;
		}
		//Найти родительскую задачу и все вложенные в неё зачачи (кроме аргумента, так как он может быть ещё не сохранен)
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		$oCriteria->where( $e->eq('parentId', $oTask->getParentId()), $e->neq('id', $oTask->getId()) );
		$aTasks = $oRepository->matching($oCriteria)->toArray();

		$nSeconds = $oTask->getTotalHours();
		if ($aTasks) {
			foreach ($aTasks as $o) {
				$nSeconds += $o->getTotalSeconds();
			}
		}
		//суммировать интервалы parentTask, добавить к предыдущему
		$nSeconds += $this->_getAllTaskIntervalsAsSeconds($oParentTask);
		//Заполнить родительскую задачу, сохранить, вызвать _setParentTasksTimeIntervalsRecursive передав родительскую
		$this->_setTaskTimeIntervalDisplayFields($oParentTask, $nSeconds);
		$this->save($oParentTask);
		$this->_setParentTasksTimeIntervalsRecursive($oParentTask);
	}
	/**
	 * Получить все интервалы задачи в секундах
	 * @param CrnTasks $oParentTask
	 * @param ?CrnIntervals $oInterval = null "крайний" интервал задачи, может быть не передан
	 * @return int
	*/
	private function _getAllTaskIntervalsAsSeconds(CrnTasks $oTask, ?CrnIntervals $oInterval = null) : int
	{
		$oRepository = $this->repository('App:CrnIntervals');
		$oCriteria = Criteria::create();
		$e = Criteria::expr();
		if ($oInterval) {
			$oCriteria->where(
				$e->andX(
					$e->eq('taskId', $oTask->getId()), $e->neq('id', $oInterval->getId())
				)
			);
		} else {
			$oCriteria->where($e->eq('taskId', $oTask->getId() ) );
		}
		$aIntervals = $oRepository->matching($oCriteria)->toArray();
		$nSecondsTime = 0;
		if ($aIntervals) {
			/** @var CrnIntervals $o */
			foreach ($aIntervals as $o) {
				$nSecondsTime += ( strtotime($o->getEndDatetime()->format('Y-m-d H:i:s'))  - strtotime($o->getStartDatetime()->format('Y-m-d H:i:s')));
			}
		}
		if ($oInterval) {
			$nSecondsTime += ( strtotime($oInterval->getEndDatetime()->format('Y-m-d H:i:s'))  - strtotime($oInterval->getStartDatetime()->format('Y-m-d H:i:s')));
		}
		return $nSecondsTime;
	}
	/**
	 * Установить поля rel... и totlaHours $oRunnedTask
	 * @param CrnTasks $oRunnedTask
	 * @param int $nSecondsTime время в секундах (получено сложением всех интервалов задачи)
	*/
	private function _setTaskTimeIntervalDisplayFields(CrnTasks $oRunnedTask, int $nSecondsTime) : void
	{
		$nYears = floor($nSecondsTime / (3600 * 24 * 365) );
		$oRunnedTask->setRelYears($nYears);
		$nMonths = $nSecondsTime - ($nYears * 3600 * 24 * 365);
		$nMonths = floor($nMonths / (3600 * 24 * 30) );
		$oRunnedTask->setRelMonths($nMonths);
		$nWeeks = $nSecondsTime - ($nYears * 3600 * 24 * 365) - ($nMonths * 3600 * 24 * 30);
		$nWeeks = floor($nWeeks / (3600 * 24 * 7) );
		$oRunnedTask->setRelWeeks($nWeeks);
		$nDays = $nSecondsTime - ($nYears * 3600 * 24 * 365) - ($nMonths * 3600 * 24 * 30) - ($nWeeks * 3600 * 24 * 7);
		$nDays = floor($nDays / (3600 * 24) );
		$oRunnedTask->setRelDays($nDays);
		$nHours = $nSecondsTime - ($nYears * 3600 * 24 * 365) - ($nMonths * 3600 * 24 * 30) - ($nWeeks * 3600 * 24 * 7) - ($nDays * 3600 * 24);
		$nHours = floor($nHours / 3600 );
		$oRunnedTask->setRelHours($nHours);
		$nMinutes = $nSecondsTime - ($nYears * 3600 * 24 * 365) - ($nMonths * 3600 * 24 * 30) - ($nWeeks * 3600 * 24 * 7) - ($nDays * 3600 * 24) - ($nHours * 3600);
		$nMinutes = floor($nMinutes / 60 );
		$oRunnedTask->setRelMinutes($nMinutes);
		$nTotalHours = floor($nSecondsTime / 3600 );
		$oRunnedTask->setTotalHours($nTotalHours);
		$oRunnedTask->setTotalSeconds($nSecondsTime);
	}
	/**
	 * Остановить задачу
	 * @param CrnTasks $oRunnedTask
	 * @param ?CrnIntervals &$oInterval
	 * @param bool $bImmedateSave = false
	*/
	public function stopTask(CrnTasks $oRunnedTask, ?CrnIntervals &$oInterval, bool $bImmedateSave = false) : void
	{
		//TODO test it
		//Найти последний интервал этой задачи с start != null and end == null
		$oRepository = $this->repository('App:CrnIntervals');
		$oQueryBuilder = $oRepository->createQueryBuilder('i');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder
			->where($e->eq('i.taskId', $oRunnedTask->getId()))
			->andWhere($e->isNotNull('i.startDatetime'))
			->andWhere($e->isNull('i.endDatetime'))
			->setMaxResults(1)
			->orderBy('i.id', 'DESC');
		//var_dump($oQueryBuilder->getQuery());die;

		$aIntervals = $oQueryBuilder->getQuery()->execute();


		//установить end задачи в текущее время
		$oInterval = ($aIntervals[0] ?? null);
		if ($oInterval) {
			$oInterval->setEndDatetime($this->now());
		}
		//установить найденой задаче executing = 0
		$oRunnedTask->setIsExecuted(false);
		//Обновить поля найденой задачи
		$this->_recalculateTaskTime($oRunnedTask, $oInterval);
		if ($bImmedateSave) {
			$this->save($oRunnedTask, $oInterval);
		}
	}
	/**
	 * @return string
	*/
	public function getUserAvartarImageSrc() : string
	{
		$oUser = $this->getAuthUser();
		$nLogoId = $oUser->getLogotypeId();
		if ($nLogoId) {
			$oLogoInfo = $this->repository('App:UserMedia')->find($nLogoId);
			if ($oLogoInfo) {
				if (file_exists($this->request()->server->get('DOCUMENT_ROOT') . $oLogoInfo->getPath())) {
					return $oLogoInfo->getPath();
				}
			}
		}
		return $this->getParameter('app.default_cron_friend_logo');
	}

	/**
	 * @param string $sParameterName
	 * @return  string
	*/
	public function getParameter(string $sParameterName) : string
	{
		return $this->oContainer->getParameter($sParameterName);
	}
	/**
	 * Переставить записи в таблице
	 * @param string $sRepositoryId for example 'App:CrnTasks'
	 * @param array $aOrderRecordsInfo for example ['15', '19', '25', '18'] each value is entity.id
     * @param string  $sOrderFieldName = 'delta' имя поля, по которому упорядочиваются записи сущности
	 * @return
	*/
	public function rearrangeRecords(string $sRepositoryId, array $aOrderRecordsInfo, string $sOrderFieldName = 'delta')
	{
        $oRepository = $this->repository($sRepositoryId);
        $oQueryBuilder = $oRepository->createQueryBuilder('t');
        $e = $oQueryBuilder->expr();
        $oQueryBuilder->select('MIN(t.' . $sOrderFieldName . ') AS m')
            ->where($e->in('t.id', $aOrderRecordsInfo));
        $aMinInfo = $oQueryBuilder->getQuery()->getSingleResult();
        $nMin = ($aMinInfo['m'] ?? 0);
        foreach ($aOrderRecordsInfo as $nId) {
            $oQueryBuilder = $oRepository->createQueryBuilder('t');
            $oQueryBuilder->update()->set('t.' . $sOrderFieldName, $nMin)
                ->where($e->eq('t.id', $nId))
                ->getQuery()->execute();
            $nMin++;
        }
	}
	/**
	 * @param string $sRepositoryId
	 * @param string $sPositionFieldName = 'delta'
     * @return int максимальное значение поля $sPositionFieldName в репозитории сущностей $sRepositoryId
	*/
	public function getNextPosition(string $sRepositoryId, string $sPositionFieldName = 'delta') : int
	{
        $oRepository = $this->repository($sRepositoryId);
        $oQueryBuilder = $oRepository->createQueryBuilder('t');
        $oQueryBuilder->select('MAX(t.' . $sPositionFieldName . ') AS m');
        $aMaxInfo = $oQueryBuilder->getQuery()->getSingleResult();
        $nMax = ($aMaxInfo['m'] ?? 0);
        return ($nMax + 1);
	}
	/**
	 * Переместить запись на другую страницу (при постраничной выдаче)
	 * @param int $nId идентификатор сущности (записи)
	 * @param ServiceEntityRepositoryInterface $oRepository
	 * @param string $sDirect  = 'u' 'u' for previous page or 'd' for next page
	 * @param string $sDisplayedOrder = 'ASC' или 'DESC' это порядок сортировки используемый для отображаемых данных
	 * @param array $aFieldsForSelect = ['id', 'name', 'delta'], поля, которые надо выбрать после пермещения записей (чтобы отобразить их для записи, появившейся на странице вместо перемещённой)
	 * @param string $sPositionFieldName = 'delta'
	 * @param string $sIdFieldName = 'id'
	 * @return array В случае успеха ['srcId' => $nId, 'newRec' => ... ] с данными полей из $aFieldsForSelect
	 *                иначе ['msg' => 'error', 'msg' => ... ]
	*/
	public function moveRecordToOtherPage(int $nId, ServiceEntityRepositoryInterface $oRepository, string $sDirect = 'u', string $sDisplayedOrder = 'ASC', array $aFieldsForSelect = ['id', 'name', 'delta'], string $sPositionFieldName = 'delta', string $sIdFieldName = 'id')
	{
		$t = $this->translator;
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->select('t.' . $sPositionFieldName);
		$aDelta = $oQueryBuilder->where( $e->eq('t.id', $nId) )->getQuery()->getSingleResult();
		$nPos = intval($aDelta[$sPositionFieldName]);

		$sOperation = $sDirect == 'd' ? '>=' : '<=';
		$sDirection = $sDirect == 'd' ? 'ASC' : 'DESC';
		if ($sDisplayedOrder == 'DESC') {
			$sDirection = $sDirection == 'ASC' ? 'DESC' : 'ASC';
			$sOperation = $sOperation == '>=' ? '<=' : '>=';
		}
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		$oQueryBuilder->where( 't. ' . $sPositionFieldName . ' ' . $sOperation .  ' ' . $nPos );
		foreach ($aFieldsForSelect as &$item) {
			$item = 't.' . $item;
		}
		$select = implode(', ', $aFieldsForSelect);
		$oQueryBuilder->select($select);
		$oQueryBuilder->orderBy( ('t.' . $sPositionFieldName), $sDirection);
		$oQueryBuilder->setMaxResults(2);

		$aRows = $oQueryBuilder->getQuery()->getResult();


		$nRows = count($aRows);
		if ($nRows == 2) {
			$aRows = array_column($aRows, null, $sIdFieldName);
			$aMovingRow = ($aRows[$nId] ?? []);
			unset($aRows[$nId]);
			$aRow = current($aRows);//it next or prew row
			if ($aMovingRow[$sPositionFieldName] == $aRow[$sPositionFieldName]) {
				if ($sDirect == 'd') {
					$aMovingRow[$sPositionFieldName]++;
				} else {
					$aRow[$sPositionFieldName]++;
				}
			}
			$this->_swapRecords($nId, $aMovingRow[$sPositionFieldName], $aRow[$sIdFieldName], $aRow[$sPositionFieldName], $oRepository, $sPositionFieldName, $sIdFieldName);
			$aResult['newRec'] = $aRow;
			$aResult['srcId'] = $nId;
			return $aResult;
		}
		$aResult['msg'] = $t->trans('Record is ' . ($sDirect == 'd' ? 'last' : 'top'));
		$aResult['status'] = 'error';
		return $aResult;
	}
	/**
	 * @see moveRecordToOtherPage
	 *  Меняет позиции двух записей
	 * @param int $nId1
	 * @param int $nPos1
	 * @param int $nId2
	 * @param int $nPos2
	 * @param ServiceEntityRepositoryInterface $oRepository
	 * @param string $sPositionFieldname = 'delta',
	 * @param string $sIdFieldname = 'id'
	 */
	private function _swapRecords(int $nId1, int $nPos1, int $nId2, int $nPos2, ServiceEntityRepositoryInterface $oRepository, string $sPositionFieldname = 'delta', string $sIdFieldname = 'id')
	{
		//$sql = 'UPDATE ' . $this->table . ' SET delta = ' . $nPos2 . ' WHERE id = ' . $nId1;
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		$e = $oQueryBuilder->expr();
		$oQueryBuilder->update();
		$oQueryBuilder->set( ('t.' . $sPositionFieldname), $nPos2);
		$oQueryBuilder->where( $e->eq( ('t.' . $sIdFieldname), $nId1) );
		$oQueryBuilder->getQuery()->execute();
		//$sql = 'UPDATE ' . $this->table . ' SET delta = ' . $nPos1 . ' WHERE id = ' . $nId2;
		$oQueryBuilder = $oRepository->createQueryBuilder('t');
		$oQueryBuilder->update();
		$oQueryBuilder->set( ('t.' . $sPositionFieldname), $nPos1);
		$oQueryBuilder->where( $e->eq( ('t.' . $sIdFieldname), $nId2) );
		$oQueryBuilder->getQuery()->execute();
	}
}
