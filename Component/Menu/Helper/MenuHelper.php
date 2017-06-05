<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:58.
 */

namespace Umbrella\CoreBundle\Component\Menu\Helper;

use Umbrella\CoreBundle\Component\Breadcrumb\Breadcrumb;
use Umbrella\CoreBundle\Component\Menu\MenuAuthorizationChecker;
use Umbrella\CoreBundle\Component\Menu\MenuRouteMatcher;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;
use Umbrella\CoreBundle\Component\Menu\MenuProvider;
use Umbrella\CoreBundle\Component\Menu\MenuRendererProvider;
use Umbrella\CoreBundle\Component\Menu\Renderer\MenuRendererInterface;

/**
 * Class MenuHelper
 */
class MenuHelper
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
     * @return MenuRendererInterface
     */
    public function getRenderer($name)
    {
        return $this->rendererProvider->get($name);
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
     * @param bool $checkChild
     * @return bool
     */
    public function isCurrent(MenuNode $node, $checkChild = true)
    {
        return $checkChild
            ? $this->matcher->isCurrentOrHasChildCurrent($node)
            : $this->matcher->isCurrent($node);
    }

    /**
     * @param Menu $menu
     * @return null|MenuNode
     */
    public function getCurrentNode(Menu $menu)
    {
        return $this->retrieveCurrentNode($menu->root);
    }

    /**
     * @param Menu $menu
     * @return Breadcrumb
     */
    public function buildBreadcrumb(Menu $menu)
    {
        $node = $this->retrieveCurrentNode($menu->root);

        $bc = new Breadcrumb();
        $bc->translationPrefix = $menu->translationPrefix;

        while ($node !== null) {
            if ($node->type == MenuNode::TYPE_PAGE) {
                $bc->prependItem($node->label, $node->url);
            }
            $node = $node->parent;
        }
        return $bc;
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
