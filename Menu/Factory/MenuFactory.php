<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:11
 */
namespace Umbrella\CoreBundle\Menu\Factory;


use Umbrella\CoreBundle\Core\BaseService;
use Umbrella\CoreBundle\Menu\MenuNode;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class MenuFactory
 * @package Umbrella\CoreBundle\Menu\Factory
 */
class MenuFactory extends BaseService
{
    /**
     * @return MenuNode
     */   
    public function rootNode()
    {
        $node = new MenuNode();
        $node->type = MenuNode::TYPE_ROOT;
        return $node;
    }

    /**
     * @param array $options
     * @return MenuNode
     */
    public function headerNode(array $options = array())
    {
        $node = new MenuNode();
        $node->type = MenuNode::TYPE_HEADER;

        if (isset($options['label'])) {
            $node->text = $options['label'];
        }

        if (isset($options['roles'])) {
            $node->roles = ArrayUtils::to_array($options['roles']);
        }

        return $node;
    }

    /**
     * @param array $options
     * @return MenuNode
     */
    public function pageNode(array $options = array())
    {
        $node = new MenuNode();
        $node->type = MenuNode::TYPE_PAGE;

        if (isset($options['label'])) {
            $node->text = $options['label'];
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
                    $node->routeParams = isset($action['params']) and is_array($action['params']) ? $action['params'] : array();
                    $node->url = $this->container->get('router')->generate($node->route, $node->routeParams);
                }

            } else {
                $node->route = $action;
                $node->url = $this->container->get('router')->generate($node->route);
            }
        }
        return $node;
    }
}