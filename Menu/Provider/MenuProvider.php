<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:51.
 */

namespace Umbrella\CoreBundle\Menu\Provider;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\Core\BaseService;
use Umbrella\CoreBundle\Menu\Factory\MenuFactory;
use Umbrella\CoreBundle\Menu\MenuNode;

/**
 * Class MenuProvider.
 */
class MenuProvider extends BaseService
{
    /**
     * @var array
     */
    protected $builders = array();

    /**
     * @var MenuFactory
     */
    protected $factory;

    /**
     * MenuProvider constructor.
     *
     * @param ContainerInterface $container
     * @param MenuFactory        $factory
     * @param array              $builders
     */
    public function __construct(ContainerInterface $container, MenuFactory $factory, array $builders = array())
    {
        parent::__construct($container);
        $this->factory = $factory;
        $this->builders = $builders;
    }

    /**
     * @param $name
     *
     * @return MenuNode
     */
    public function get($name)
    {
        if (!isset($this->builders[$name])) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        if (!is_array($this->builders[$name]) || 2 !== count($this->builders[$name])) {
            throw new \InvalidArgumentException(sprintf('The menu builder definition for the menu "%s" is invalid. It should be an array (serviceId, method)', $name));
        }

        list($id, $method) = $this->builders[$name];

        return $this->container->get($id)->$method($this->factory);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->builders[$name]);
    }
}
