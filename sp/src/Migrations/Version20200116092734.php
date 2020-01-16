<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use App\Entity\OpCodes;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116092734 extends AbstractMigration   implements ContainerAwareInterface
{
	public function setContainer(ContainerInterface $container = null)
	{
		$this->_oContainer = $container;
		$this->_oEm = $container->get('doctrine')->getManager();
		$this->_oRepository = $container->get('doctrine')->getRepository('App:OpCodes');
	}

	public function getDescription() : string
	{
		return 'Добавляет необходимые данные в таблицу op_codes';
	}

	public function up(Schema $schema) : void
	{
		$n = 5;
		// this up() migration is auto-generated, please modify it to your needs
		$opCode = $this->_oRepository->find($n);
		if (!$opCode) {
			$opCode = new OpCodes();
			$opCode->setId($n);
		}
		$opCode->setName('Просто тестирую миграции');
		$this->_oEm->persist($opCode);
		$this->_oEm->flush();
	}

	public function down(Schema $schema) : void
	{
		// this down() migration is auto-generated, please modify it to your needs

	}
}
