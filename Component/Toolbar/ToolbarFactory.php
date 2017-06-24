<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 23/05/17
 * Time: 20:03.
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;

/**
 * Class ToolbarFactory.
 */
class ToolbarFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DataTableFactory constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $class
     * @param array $options
     *
     * @return Toolbar
     */
    public function create($class, array $options = array())
    {
        if (!is_subclass_of($class, Toolbar::class)) {
            throw new \InvalidArgumentException("Class '$class' must extends AbstractToolbar class.");
        }

        /** @var Toolbar $toolbar */
        $toolbar = new $class($options);
        $toolbar->setContainer($this->container);

        return $toolbar;
    }
}
