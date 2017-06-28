<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 30/05/17
 * Time: 19:34
 */

namespace Umbrella\CoreBundle\Component\Menu\Model;

/**
 * Class Menu
 * @package Umbrella\CoreBundle\Component\Menu\Model
 */
class Menu implements \IteratorAggregate, \Countable
{
    /**
     * @var MenuNode
     */
    public $root;

    /**
     * @var string
     */
    public $translationPrefix = 'menu.';


    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->root->children);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->root->children);
    }
}