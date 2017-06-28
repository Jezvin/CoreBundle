<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:51.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrellac\CoreBundle\Component\Menu\Model\Menu;

/**
 * Class MenuProvider.
 */
class MenuProvider
{
    /**
     * @var array
     */
    protected $menus = array();

    /**
     * @var MenuBuilder
     */
    protected $builder;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * MenuProvider constructor.
     *
     * @param ContainerInterface $container
     * @param MenuBuilder        $builder
     * @param array              $menus
     */
    public function __construct(ContainerInterface $container, MenuBuilder $builder, array $menus = array())
    {
        $this->container = $container;
        $this->builder = $builder;
        $this->menus = $menus;
    }

    /**
     * @param $name
     *
     * @return Menu
     */
    public function get($name)
    {
        if (!isset($this->menus[$name])) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        if (!is_array($this->menus[$name]) || 2 !== count($this->menus[$name])) {
            throw new \InvalidArgumentException(sprintf('The service definition for menu "%s" is invalid. It should be an array (serviceId, method)', $name));
        }

        list($id, $method) = $this->menus[$name];

        return $this->container->get($id)->$method($this->builder);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->menus[$name]);
    }
}
