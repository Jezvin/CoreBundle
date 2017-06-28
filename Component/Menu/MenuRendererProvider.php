<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 11:30.
 */

namespace Umbrellac\CoreBundle\Component\Menu;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrellac\CoreBundle\Component\Menu\Renderer\MenuRendererInterface;

/**
 * Class MenuRendererProvider.
 */
class MenuRendererProvider
{
    /**
     * @var array
     */
    protected $renderersId = array();

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * MenuRendererProvider constructor.
     *
     * @param ContainerInterface $container
     * @param array              $renderersId
     */
    public function __construct(ContainerInterface $container, array $renderersId = array())
    {
        $this->container = $container;
        $this->renderersId = $renderersId;
    }

    /**
     * @param $name
     * @return object|MenuRendererInterface
     */
    public function get($name)
    {
        if (!isset($this->renderersId[$name])) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        return $this->container->get($this->renderersId[$name]);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->renderersId[$name]);
    }
}
