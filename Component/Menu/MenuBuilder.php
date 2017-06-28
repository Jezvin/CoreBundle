<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:11.
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class MenuBuilder.
 */
class MenuBuilder
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Menu
     */
    protected $menu;

    /**
     * MenuBuilder constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->menu = new Menu();
    }

    /**
     * @return MenuNode
     */
    public function createRootNode()
    {
        $node = new MenuNode();
        $node->type = MenuNode::TYPE_ROOT;

        $this->menu->root = $node;
        return $node;
    }

    /**
     * @param array $options
     *
     * @return MenuNode
     */
    public function createHeaderNode(array $options = array())
    {
        $node = new MenuNode();
        $node->type = MenuNode::TYPE_HEADER;

        if (isset($options['label'])) {
            $node->label = $options['label'];
        }

        if (isset($options['roles'])) {
            $node->roles = ArrayUtils::to_array($options['roles']);
        }

        return $node;
    }

    /**
     * @param array $options
     *
     * @return MenuNode
     */
    public function createPageNode(array $options = array())
    {
        $node = new MenuNode();
        $node->type = MenuNode::TYPE_PAGE;

        if (isset($options['label'])) {
            $node->label = $options['label'];
        }

        if (isset($options['icon'])) {
            $node->iconClass = $options['icon'];
        }

        if (isset($options['roles'])) {
            $node->roles = ArrayUtils::to_array($options['roles']);
        }

        if (isset($options['action'])) {
            $action = $options['action'];

            if (is_array($action)) {
                if (isset($action['target'])) {
                    $node->target = $action['target'];
                }

                if (isset($options['url'])) {
                    $node->url = $options['url'];
                }

                if (isset($action['route'])) {
                    $node->route = $action['route'];
                    $node->routeParams = (isset($action['params']) && is_array($action['params'])) ? $action['params'] : array();
                    $node->url = $this->container->get('router')->generate($node->route, $node->routeParams);
                }
            } else {
                $node->route = $action;
                $node->url = $this->container->get('router')->generate($node->route);
            }
        }

        return $node;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
       return $this->menu;
    }
}
