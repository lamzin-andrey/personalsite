<?php
namespace App\Command;

use App\Service\AppService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Landlib\SimpleMail;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CheckPhdMessagesCommand extends Command
{
	// the name of the command (the part after "bin/console")
	protected static $defaultName = 'app:check_phd';


	public function __construct($name = null, AppService $oAppService, ContainerInterface $oContainer)
	{
		$this->_oContainer = $oContainer;
		$this->_oAppService = $oAppService;
		parent::__construct(static::$defaultName);
	}

	protected function configure()
	{

	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$aData = $this->_oAppService->getUnprocessedPhdMessages(true);
		if ($aData) {
			$siteAdmin = $this->_oContainer->getParameter('app.siteAdminEmail');
			$sPhdAdmin = $this->_oContainer->getParameter('app.phdAdminEmail');
			//$x = $this->_oContainer->getParameter('app.');
			$sHtml = '<p>Привет!</p><p>Новое сообщение на фэцэ.</p>
	<p>Пока тебе тут обезьяны мерещатся, лось уйдёт!</p>';
			$oMailer = new SimpleMail();
			$oMailer->setSubject('Новое сообщение на фэцэ');
			$oMailer->setBody($sHtml, 'text/html', 'UTF-8');
			$oMailer->setFrom($siteAdmin);
			$oMailer->setTo($sPhdAdmin);
			$s = $oMailer->send();
			var_dump($s);die;
		}

		return 0;
	}

}