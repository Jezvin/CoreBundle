<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:58.
 */

namespace Umbrella\CoreBundle\Component\Menu\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;
use Umbrella\CoreBundle\Component\Menu\MenuProvider;
use Umbrella\CoreBundle\Component\Menu\MenuRendererProvider;

/**
 * Class MenuTwigExtension.
 */
class MenuTwigExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var MenuProvider
     */
    protected $menuProvider;

    /**
     * @var MenuRendererProvider
     */
    protected $menuRendererProvider;

    /**
     * MenuTwigExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->requestStack = $container->get('request_stack');
        $this->menuProvider = $container->get(MenuProvider::class);
        $this->menuRendererProvider = $container->get(MenuRendererProvider::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_menu', array($this, 'get')),
            new \Twig_SimpleFunction('is_granted_menu', array($this, 'isGranted')),
            new \Twig_SimpleFunction('get_current_menu', array($this, 'getCurrentNode')),
            new \Twig_SimpleFunction('get_current_menu_title', array($this, 'getCurrentNodeTitle')),
            new \Twig_SimpleFunction('render_menu', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param $name
     *
     * @return Menu
     */
    public function get($name)
    {
        return $this->menuProvider->get($name);
    }

    /**
     * @param $name
     *
     * @return null|MenuNode
     */
    public function getCurrentNode($name)
    {
        return $this->get($name)->findCurrent($this->requestStack->getMasterRequest());
    }

    /**
     * @param $name
     * @param string $default
     *
     * @return string
     */
    public function getCurrentNodeTitle($name, $default = '')
    {
        $menu = $this->get($name);
        $current = $menu->findCurrent($this->requestStack->getMasterRequest());

        return $current ? $menu->translationPrefix . $current->label : $default;
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function render($name)
    {
        return $this->menuRendererProvider->get($name)->render($this->get($name));
    }

    /**
     * @param MenuNode $node
     *
     * @return bool
     */
    public function isGranted(MenuNode $node)
    {
        return $node->isGranted($this->container->get('security.authorization_checker'));
    }
}