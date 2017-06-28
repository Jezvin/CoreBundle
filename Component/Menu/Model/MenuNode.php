<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:05.
 */

namespace Umbrella\CoreBundle\Component\Menu\Model;

/**
 * Class MenuNode.
 */
class MenuNode implements \IteratorAggregate, \Countable
{
    const TYPE_ROOT = 'ROOT';
    const TYPE_HEADER = 'HEADER';
    const TYPE_PAGE = 'PAGE';

    const DFT_ICON_CLASS = 'fa fa-circle-o';
    const DFT_URL = '#';
    const DFT_TARGET = '_self';

    /**
     * @var string
     */
    public $type;

    /**
     * @var MenuNode
     */
    public $parent;

    /**
     * @var array
     */
    public $children = array();

    /**
     * @var string
     */
    public $iconClass = self::DFT_ICON_CLASS;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $url = self::DFT_URL;

    /**
     * @var string
     */
    public $target = self::DFT_TARGET;

    /**
     * @var array
     */
    public $roles = array();


    /* Keep route and routeParams options for url matcher */

    /**
     * @var string
     */
    public $route;

    /**
     * @var array
     */
    public $routeParams = array();

    /**
     * @return int|mixed
     */
    public function getLevel()
    {
        return $this->parent ? $this->parent->getLevel() + 1 : 0;
    }

    /**
     * @param MenuNode $child
     *
     * @return $this
     */
    public function addChild(MenuNode $child)
    {
        $child->parent = $this;
        $this->children[] = $child;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->children);
    }
}
