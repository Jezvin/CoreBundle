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
use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\Core\BaseCommand;
use Umbrella\CoreBundle\Extension\SearchableInterface;
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
     * @var OutputInterface
     */
    protected $output;

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
        $this->output = $output;
        $this->em = $this->em();
        SQLUtils::disableSQLLog($this->em);

        $entitiesClass = $this->em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();

        foreach ($entitiesClass as $entityClass) {
            if (is_subclass_of($entityClass, SearchableInterface::class)) {
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

        $this->output->writeln("+ Indexing entity $entityClass");

        $count = 0;
        while (($entity = $iterable->next()) !== false) {
            ++$count;
            $this->indexEntity($entity[0]);

            if ($count % 50 == 0) {
                $this->em->flush();
                $this->em->clear();
            }
        }

        $this->em->flush();
        $this->em->clear();

        $this->output->writeln("| $count entity indexed");
    }

    /**
     * @param SearchableInterface $entity
     */
    protected function indexEntity(SearchableInterface $entity)
    {
        $propertyAccess = PropertyAccess::createPropertyAccessor();
        $search = '';
        foreach ($entity->getSearchableFields() as $propertyPath) {
            try {
                $search .= trim($propertyAccess->getValue($entity, $propertyPath)).' ';
            } catch (\Exception $e) {
                $this->output->writeln("! [error] Enable to reach property path '$propertyPath' for search.");
            }
        }
        $entity->setSearchable($search);
        $this->em->persist($entity);
    }
}
