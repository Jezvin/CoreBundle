<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 23:11.
 */

namespace Umbrella\CoreBundle\Component\Breadcrumb;

use Symfony\Component\HttpFoundation\Request;
use Umbrellac\CoreBundle\Component\Menu\Model\Menu;
use Umbrellac\CoreBundle\Component\Menu\Model\MenuNode;

/**
 * Class Breadcrumb.
 */
class Breadcrumb implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @var array
     */
    protected $items = array();

    /**
     * @var string
     */
    public $translationPrefix = 'breadcrumb.';

    /**
     * @var string
     */
    public $template = 'UmbrellacCoreBundle:Breadcrumb:breadcrumb.html.twig';

    /**
     * @param $label
     * @param string $url
     *
     * @return $this
     */
    public function addItem($label, $url = '')
    {
        $b = new BreadcrumbItem($label, $url);
        $this->items[] = $b;

        return $this;
    }

    /**
     * @param $label
     * @param string $url
     *
     * @return $this
     */
    public function prependItem($label, $url = '')
    {
        $b = new BreadcrumbItem($label, $url);
        array_unshift($this->items, $b);

        return $this;
    }

    /**
     * Clear breadcrumb.
     */
    public function clear()
    {
        $this->items = array();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
