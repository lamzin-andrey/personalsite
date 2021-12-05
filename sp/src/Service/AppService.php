<?php
namespace App\Service;

use App\Entity\Ausers;
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
use Symfony\Contracts\Translation\TranslatorInterface;

class AppService
{

	/** @property FormInterface $_oForm Сюда можно передать форму для более простой работы с ними */
	private $_oForm;

    /** @property TranslatorInterface $t */
    private $t;

    /** @property Request $request */
    private $request;

    const SQZ_NUMS_ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	public function __construct(ContainerInterface $container,
                                ?ViewDataService $oViewDataService = null,
                                ?FileUploaderService $oFileUploaderService = null,
                                TranslatorInterface $t)
	{
		$this->oContainer = $container;
		$this->request = $container->get('request_stack')->getCurrentRequest();
		$this->translator = $container->get('translator');
		$this->oViewDataService = $oViewDataService;
		$this->oFileUploaderService = $oFileUploaderService;
		$this->t = $t;
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
	 *
	 * @param 
	 * @return ViewDataService
	*/
	public function getFileUploaderService() : FileUploaderService
	{
		return $this->oFileUploaderService;
	}
	//TODO избавиться от security.token_storage
	public function getAuthUser()
	{
	    $token = $this->oContainer->get('security.token_storage')->getToken();
	    $oUser = null;
	    if ($token) {
            $oUser = $token->getUser();
	    }
		return $oUser;
	}
	/**
	 * Добавляет $oBuilder поле для загрузки .PSD файла со всеми необходимыми параметрами
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
	 * Добавляет $oBuilder поле для загрузки zip файла со всеми необходимыми параметрами
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
	 * Установить сообщение об ошибке полю формы (полезно в том члучае, если оно должно кастомизироваться в зависимости от ситуации)
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
			$sMessage = $this->localizeFormError($sMessage);
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
	public function json(array $aData)
	{
		if (!isset($aData['status'])) {
			$aData['status'] = 'ok';
		}
		$oResponse = new Response( json_encode($aData) );
		$oResponse->headers->set('Content-Type', 'application/json');
		return $oResponse;
	}
	/**
	 * TODO всюду заменить вызовы специальными репозиториями
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
	public function getUserAvatarImageSrc() : string
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
     * @param $f = 'md5' You can set sha1, sha256 e. t. c
	 * @return string by default md5(HTTP_USER_AGENT . microtime . additionalParams)
	*/
	public function getHash(Request $request, string $additionalParams = '', string $f = 'md5') : string
	{
	    return $f($request->server->get('HTTP_USER_AGENT') . '-' . microtime(false) . $additionalParams);
	}

	public static function getHumanFilesize(int $n, int $percision = 3, int $maxOrder = 3, bool $pack = true) : string
    {
        return self::getHumanValue($n,
            ['b', 'Kb', 'Mb', 'Gb', 'Tb'],
            1024,
            $percision,
            $maxOrder,
            $pack
        );
    }

    public static function getHumanValue(int $n, array $units, int $divider = 1000, int $percision = 3, int $maxOrder = 3, bool $pack = true) : string
    {
        $unitIterator = 0;
        $r = strval($n) . ' ' . $units[$unitIterator];
        if ($pack) {
            $a = explode('.', $r);
            $int = $a[0];
            $add = $a[1] ?? '';
            $buf = [];
            $buf[] = dechex($int);
            if ($add) {
                $buf[] = dechex($add);
            }
            $buf[] =  $units[$unitIterator];
            $r = implode('g', $buf);
        }



        $unitsSz = count($units);
        do {
            $n = round($n, $percision);
            $s = strval($n);
            $a = explode('.', $s);
            $int = $a[0];
            $add = $a[1] ?? '';
            if (strlen($int) <= $maxOrder || $unitIterator > ($unitsSz - 1) ) {
                if ($pack) {
                    $buf = [];
                    $buf[] = dechex($int);
                    if ($add) {
                        $buf[] = dechex($add);
                    }
                    $buf[] = $units[$unitIterator];
                    return implode('g', $buf);
                }
                return $s . ' ' . $units[$unitIterator];
            }
            $n = $n / $divider;
            $unitIterator++;
        } while(true);

        return $r;
    }

    public function l(?Ausers $user, string $s, ?string $domain = 'wusb_filesystem', $params = []) : string
    {
        $t = $this->t;
        $locale = $this->getUserLocale($user);

        return $t->trans($s, $params, $domain, $locale);
    }

    /**
     *  $request->setLocale does not work. In onKernelRequest too.
    */
    protected function localizeFormError(string $message) : string
    {
        $locale = $this->getUserLocale();

        if ('en' === $locale) {
            $ruYamlFile = __DIR__ . '/../../translations/validators.ru.yaml';
            $map = $this->parseReversive($ruYamlFile);
            return ($map[$message] ?? $message);
        }

        return $message;
    }

    /**
     * @param $
     * @return
    */
    protected function parseReversive(string $fileName) : array
    {
        $map = [];
        if (file_exists($fileName)) {
            $a = explode("\n", file_get_contents($fileName));
            foreach ($a as $line) {
                $pair = explode(': ', $line);
                $val = trim($pair[0]);
                $key = trim($pair[1] ?? '');
                $map[$key] = $val;
            }
        }

        return $map;
    }

    /**
     * @param $
     * @return
    */
    public function getUserLocale(?Ausers $user = null) : string
    {
        $locale = 'en';
        /**
         * @var Ausers $user
        */
        if (!$user || !($user instanceof Ausers)) {
            $user = $this->getAuthUser();
        }

        if (!$user || !($user instanceof Ausers)) {
            $guestId = null;
            if ($this->request) {
                $guestId = $this->request->cookies->get('guest_id');
            }

            if ($guestId) {
                $user = $this->oContainer->get('doctrine')->getRepository(Ausers::class)->findOneBy([
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

        return $locale;
    }

    public static function sqzDatetime(?\DateTime $dateTime) : string
    {
        if (!$dateTime) {
            return '2021-11-23 00:00:00';
        }
        $sDt = $dateTime->format('Y,m,d,H,i,s');
        $aDt = explode(',', $sDt);
        $year = self::sqz($aDt[0]);
        $month = self::sqz($aDt[1]);
        $day = self::sqz($aDt[2]);
        $hour = self::sqz($aDt[3]);
        $min = self::sqz($aDt[4]);
        $sec = self::sqz($aDt[5]);

        return $day . $month . $year . $hour . $min . $sec;

    }

    /**
     * Encode number as index in string.
     * If number < 59 return index of self::SQZ_NUMS_ALPHABET
     * If number >= 59:
     *  If number >= 1900 AND N - 1900 < 59 AND n < 2000 return X[index of self::SQZ_NUMS_ALPHABET]
     *  If number >= 2000 AND N - 2000 < 59 return Y[index of self::SQZ_NUMS_ALPHABET]
     *  If N - Z > 59  return Znumber
    */
    public static function sqz(string $s) : string
    {
        $n = intval($s);
        $s = strval($n);
        $symbols = self::SQZ_NUMS_ALPHABET;

        $limit = 59;
        if ($n < $limit) {
            return $symbols[$n];
        }

        // If number >= 59:
        if ($n > 1899 && $n < 3000) {
            $left = 1900;
            $sL = 'X';
            if ($n > 2000) {
                $left = 2000;
                $sL = 'Y';
            }
            $right = $n - $left;
            if ($right < $limit) {
                return $sL . $symbols[$right];
            }
            return 'Z' . $s;
        }

        return 'Z' . $s;

    }

    public static function desqzDatetime(string $sqzDt) : string
    {
        $sz = strlen($sqzDt);
        $day = self::desqz($sqzDt[0]);
        $month = self::desqz($sqzDt[1]);
        $hour = self::desqz($sqzDt[$sz - 3]);
        $min = self::desqz($sqzDt[$sz - 2]);
        $sec = self::desqz($sqzDt[$sz - 1]);

        $yearType = $sqzDt[2];
        if ('Z' == $yearType) {
            $length = $sz - 6;
            $year = substr($sqzDt, 3, $length);
        } else if ('X' == $yearType) {
            $year = '19' . self::desqz($sqzDt[3]);
        } else if ('Y' == $yearType) {
            $year = '20' . self::desqz($sqzDt[3]);
        }

        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':' . $sec;
    }

    public static function desqz(string $s) : string
    {
        $n = strpos(self::SQZ_NUMS_ALPHABET, $s);
        if (false === $n) {
            return '-1';
        }

        $s = strpos($n);

        if ($n < 10) {
            $s = '0' . $s;
        }

        return $s;
    }
}
