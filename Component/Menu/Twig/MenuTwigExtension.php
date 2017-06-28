<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:58.
 */

namespace Umbrella\CoreBundle\Component\Menu\Twig;

use Umbrellac\CoreBundle\Component\Breadcrumb\Breadcrumb;
use Umbrellac\CoreBundle\Component\Menu\Helper\MenuHelper;
use Umbrellac\CoreBundle\Component\Menu\MenuAuthorizationChecker;
use Umbrellac\CoreBundle\Component\Menu\MenuRouteMatcher;
use Umbrellac\CoreBundle\Component\Menu\Model\Menu;
use Umbrellac\CoreBundle\Component\Menu\Model\MenuNode;
use Umbrellac\CoreBundle\Component\Menu\MenuProvider;
use Umbrellac\CoreBundle\Component\Menu\MenuRendererProvider;

/**
 * Class MenuTwigExtension.
 */
class MenuTwigExtension extends \Twig_Extension
{
    /**
     * @var MenuHelper
     */
    private $helper;

    /**
     * MenuTwigExtension constructor.
     * @param MenuHelper $helper
     */
    public function __construct(MenuHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('menu_get', array($this, 'get')),
            new \Twig_SimpleFunction('menu_render', array($this, 'render'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('menu_is_granted_node', array($this, 'isGranted')),
            new \Twig_SimpleFunction('menu_is_current_node', array($this, 'isCurrent')),
            new \Twig_SimpleFunction('menu_get_current_node', array($this, 'getCurrentNode')),
            new \Twig_SimpleFunction('menu_get_current_node_title', array($this, 'getCurrentNodeTitle')),
            new \Twig_SimpleFunction('menu_render_breadcrumb',
                array($this, 'renderBreadcrumb'),
                array('needs_environment' => true, 'is_safe' => array('html'))),
        );
    }

    /**
     * @param $name
     *
     * @return Menu
     */
    public function get($name)
    {
        return $this->helper->get($name);
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function render($name)
    {
        return $this->helper->getRenderer($name)->render($this->get($name));
    }

    /**
     * @param MenuNode $node
     *
     * @return bool
     */
    public function isGranted(MenuNode $node)
    {
        return $this->helper->isGranted($node);
    }

    /**
     * @param MenuNode $node
     * @return bool
     */
    public function isCurrent(MenuNode $node)
    {
        return $this->helper->isCurrent($node);
    }

    /**
     * @param $name
     * @return null|MenuNode
     */
    public function getCurrentNode($name)
    {
        $menu = $this->helper->get($name);
        return $this->helper->getCurrentNode($menu);
    }


    /**
     * @param $name
     * @param string $default
     *
     * @return string
     */
    public function getCurrentNodeTitle($name, $default = '')
    {
        $menu = $this->helper->get($name);
        $currentNode = $this->helper->getCurrentNode($menu);
        return $currentNode ? ($menu->translationPrefix . $currentNode->label) : $default;
    }

    /**
     * @param \Twig_Environment $twig
     * @param $name
     * @return string
     */
    public function renderBreadcrumb(\Twig_Environment $twig, $name)
    {
        $menu = $this->helper->get($name);
        $bc = $this->helper->buildBreadcrumb($menu);
        return $twig->render($bc->template, [ 'breadcrumb' => $bc ]);
    }
}
