<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 12:58.
 */

namespace Umbrellac\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrellac\CoreBundle\Services\SearchHandler;

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
     * @var SearchHandler
     */
    protected $searchHandler;

    /**
     * EntitySubscriber constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->searchHandler = $container->get(SearchHandler::class);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->searchHandler->indexEntity($args->getObject());
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->searchHandler->indexEntity($args->getObject());
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