<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 23:11
 */

namespace Umbrella\CoreBundle\Breadcrumb;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Traversable;
use Umbrella\CoreBundle\Menu\MenuNode;

/**
 * Class Breadcrumb
 * @package Umbrella\CoreBundle\Breadcrumb
 */
class Breadcrumb implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @var array
     */
    protected $items = array();

    /**
     * @param MenuNode $currentNode
     * @return Breadcrumb
     */
    public static function constructFromMenu(MenuNode $currentNode)
    {
        $bc = new Breadcrumb();
        while($currentNode !== null) {
            if ($currentNode->type == MenuNode::TYPE_PAGE) {
                $bc->prependItem($currentNode->text, $currentNode->url);
            }
            $currentNode = $currentNode->parent;
        }
        return $bc;
    }

    /**
     * @param $text
     * @param string $url
     * @param array $translationParameters
     * @return $this
     */
    public function addItem($text, $url = "", array $translationParameters = array())
    {
        $b = new BreadcrumbItem($text, $url, $translationParameters);
        $this->items[] = $b;
        return $this;
    }

    /**
     * @param $text
     * @param string $url
     * @param array $translationParameters
     * @return $this
     */
    public function prependItem($text, $url = "", array $translationParameters = array())
    {
        $b = new BreadcrumbItem($text, $url, $translationParameters);
        array_unshift($this->items, $b);
        return $this;
    }

    /**
     * Clear breadcrumb
     */
    public function clear()
    {
        $this->items = array();
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}