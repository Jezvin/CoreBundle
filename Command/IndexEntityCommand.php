<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 13:35.
 */

namespace Umbrella\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Umbrella\CoreBundle\Component\Core\BaseCommand;
use Umbrella\CoreBundle\Handler\SearchHandler;
use Umbrella\CoreBundle\Utils\SQLUtils;

/**
 * Class IndexEntityCommand.
 */
class IndexEntityCommand extends BaseCommand
{
    const CMD_NAME = 'umbrella:entity:index';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var SearchHandler
     */
    protected $searchHandler;

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName(self::CMD_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->em = $this->em();
        $this->searchHandler = $this->getContainer()->get(SearchHandler::class);
        SQLUtils::disableSQLLog($this->em);

        $entitiesClass = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();

        foreach ($entitiesClass as $entityClass) {
            if ($this->searchHandler->isSearchable($entityClass)) {
                $this->indexAllEntity($entityClass);
            }
        }
    }

    /**
     * @param $entityClass
     */
    protected function indexAllEntity($entityClass)
    {
        $query = $this->em->createQuery("SELECT e FROM $entityClass e");
        $iterable = $query->iterate();

        $this->io->writeln("+ Indexing $entityClass");

        $count = 0;
        while (($entity = $iterable->next()) !== false) {
            ++$count;
            $this->searchHandler->indexEntity($entity[0]);

            if ($count % 50 == 0) {
                $this->em->flush();
                $this->em->clear();
            }
        }

        $this->em->flush();
        $this->em->clear();

        $this->io->writeln("| $count entity indexed");
    }
}
