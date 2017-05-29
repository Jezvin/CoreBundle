<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 12:58.
 */

namespace Umbrella\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\Extension\SearchableInterface;

/**
 * Class EntitySubscriber.
 */
class EntitySubscriber implements EventSubscriber
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * EntitySubscriber constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof SearchableInterface) {
            $this->indexEntity($entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof SearchableInterface) {
            $this->indexEntity($entity);
        }
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
                $this->logger->error("Enable to reach property path '$propertyPath' for search.");
            }
        }
        $entity->setSearchable($search);
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
        );
    }
}
