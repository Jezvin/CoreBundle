<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:58.
 */

namespace Umbrella\CoreBundle\Component\Menu\Twig;

use Umbrella\CoreBundle\Component\Breadcrumb\Breadcrumb;
use Umbrella\CoreBundle\Component\Menu\MenuAuthorizationChecker;
use Umbrella\CoreBundle\Component\Menu\MenuRouteMatcher;
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
     * @var MenuProvider
     */
    private $provider;

    /**
     * @var MenuRendererProvider
     */
    private $rendererProvider;

    /**
     * @var MenuRouteMatcher
     */
    private $matcher;

    /**
     * @var MenuAuthorizationChecker
     */
    private $checker;

    /**
     * MenuTwigExtension constructor.
     * @param MenuProvider $provider
     * @param MenuRendererProvider $rendererProvider
     * @param MenuRouteMatcher $matcher
     * @param MenuAuthorizationChecker $checker
     */
    public function __construct(MenuProvider $provider, MenuRendererProvider $rendererProvider, MenuRouteMatcher $matcher, MenuAuthorizationChecker $checker)
    {
        $this->provider = $provider;
        $this->rendererProvider = $rendererProvider;
        $this->matcher = $matcher;
        $this->checker = $checker;
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
        return $this->provider->get($name);
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function render($name)
    {
        return $this->rendererProvider->get($name)->render($this->get($name));
    }

    /**
     * @param MenuNode $node
     *
     * @return bool
     */
    public function isGranted(MenuNode $node)
    {
        return $this->checker->isGranted($node);
    }

    /**
     * @param MenuNode $node
     * @return bool
     */
    public function isCurrent(MenuNode $node)
    {
        return $this->matcher->isCurrentOrHasChildCurrent($node);
    }

    /**
     * @param $name
     * @return null|MenuNode
     */
    public function getCurrentNode($name)
    {
        $menu = $this->get($name);
        return $this->retrieveCurrentNode($menu->root);
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
        $currentNode = $this->retrieveCurrentNode($menu->root);
        return $currentNode ? ($menu->translationPrefix . $currentNode->label) : $default;
    }

    /**
     * @param \Twig_Environment $twig
     * @param $name
     * @return string
     */
    public function renderBreadcrumb(\Twig_Environment $twig, $name)
    {
        $menu = $this->get($name);
        $currentNode = $this->retrieveCurrentNode($menu->root);

        $bc = Breadcrumb::constructFromMenuNode($currentNode, $menu->translationPrefix);
        return $twig->render($bc->template, [ 'breadcrumb' => $bc ]);
    }


    /**
     * @param MenuNode $node
     * @return null|MenuNode
     */
    private function retrieveCurrentNode(MenuNode $node)
    {
        if ($this->matcher->isCurrent($node)) {
            return $node;
        }

        /** @var MenuNode $child */
        foreach ($node as $child) {
            $currentNode = $this->retrieveCurrentNode($child);
            if ($currentNode !== null) {
                return $currentNode;
            }
        }

        return null;
    }
}
