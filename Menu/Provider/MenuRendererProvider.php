<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 11:30.
 */

namespace Umbrella\CoreBundle\Menu\Provider;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\Core\BaseService;
use Umbrella\CoreBundle\Menu\Renderer\MenuRendererInterface;

/**
 * Class MenuRendererProvider.
 */
class MenuRendererProvider extends BaseService
{
    /**
     * @var array
     */
    protected $renderersId = array();

    /**
     * MenuRendererProvider constructor.
     *
     * @param ContainerInterface $container
     * @param array              $renderersId
     */
    public function __construct(ContainerInterface $container, array $renderersId = array())
    {
        parent::__construct($container);
        $this->renderersId = $renderersId;
    }

    /**
     * @param $name
     *
     * @return MenuRendererInterface
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
