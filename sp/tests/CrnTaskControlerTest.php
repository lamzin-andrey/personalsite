<?php

namespace App\Tests;

use App\Entity\CrnIntervals;
use App\Entity\CrnTasks;
use App\Service\AppService;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CrnTaskControlerTest extends WebTestCase
{
    public function setUp()
    {
		//Access to db
		static::$kernel = static::createKernel();
		static::$kernel->boot();
		$this->oContainer = static::$kernel->getContainer();
		$this->em = $this->oContainer
			->get('doctrine')
			->getManager();
    }
    /**
     * Тестирует метод AppService::_getAllTaskIntervalsAsSeconds
     * @param $
     * @return
    */
    public function test_getAllTaskIntervalsAsSeconds()
    {
		/** @var EntityRepository  $oRepository */
		$oRepository = $this->em->getRepository('App:CrnTasks');
		$aRaw = $oRepository->findBy(['codename' => 'ftesttask']);
		foreach ($aRaw as $o) {
			$aData[] = $o->getId();
		}
		if ($aData) {
			$oRepository = $this->em->getRepository('App:CrnIntervals');
			$oQueryBuilder = $oRepository->createQueryBuilder('t');
			$e = $oQueryBuilder->expr();
			$oQueryBuilder->delete()
					->where($e->in('t.taskId', $aData) )
					->getQuery()->execute();

			$oRepository = $this->em->getRepository('App:CrnTasks');
			$oQueryBuilder = $oRepository->createQueryBuilder('t');
			$oQueryBuilder->delete()
				->where($e->in('t.id', $aData) )
				->getQuery()->execute();
		}

		//Надо создать задачу с одним интервалом
		$oTask = new CrnTasks();
		$oTask->setName('FTest task');
		$oTask->setCodename('ftesttask');
		$this->em->persist($oTask);
		$this->em->flush();

		/** @var EntityRepository  $oRepository */
		$oRepository = $this->em->getRepository('App:CrnIntervals');
		$oInterval = new CrnIntervals();
		$oStartTime = new \DateTime();
		$oStartTime->setDate(2020,2,28);
		$oStartTime->setTime(16,9,45);
		$oInterval->setTaskId($oTask->getId());
		$oInterval->setStartDatetime($oStartTime);
		$this->em->persist($oInterval);
		$this->em->flush();

		$oEndTime = new \DateTime();
		$oEndTime->setDate(2020,2,28);
		$oEndTime->setTime(16,14,28);
		$oInterval->setEndDatetime($oEndTime);

		$className = 'App\Service\AppService';
		$class = new \ReflectionClass($className);
		$method = $class->getMethod('_getAllTaskIntervalsAsSeconds');
		$method->setAccessible(true);
		$obj = new $className($this->oContainer);
		$nSecs = $method->invoke($obj, $oTask, $oInterval);

		$method = $class->getMethod('_recalculateTaskTime');
		$method->setAccessible(true);
		$obj = new $className($this->oContainer);
		$method->invoke($obj, $oTask, $oInterval);

		$this->assertTrue($nSecs == 283);
    }

	/**
	 * @param string $methodName
	 * @param $argument
	 */
	private function _call($methodName, $argument) {
		$className = 'YourClassName';
		$class = new ReflectionClass($className);
		$method = $class->getMethod($methodName);
		$method->setAccessible(true);
		$obj = new $className();
		$data = $method->invoke($obj, $argument);
		return $data;
	}
	/**
	 * @usage $method->invoke($obj, $argument_1, ...);
	 * @param string $methodName
	 * @param $className,
	 * @param &$method,
	 * @param &$obj,
	 */
	private function _open($methodName, $className, &$method, &$obj) {
		$class = new ReflectionClass($className);
		$method = $class->getMethod($methodName);
		$method->setAccessible(true);
		$obj = new $className();
	}
}
