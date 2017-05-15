<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46
 */

namespace Umbrella\CoreBundle\DataTable;
use Umbrella\CoreBundle\DataTable\Renderer\ColumnRendererInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Column
 * @package Umbrella\CoreBundle\DataTable
 */
class Column
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var boolean
     */
    protected $sortable = true;

    /**
     * @var string|null
     */
    protected $defaultSorting = null;

    /**
     * @var string
     */
    protected $class;
    
    /**
     * @var string
     */
    protected $width;

    /**
     * @var mixed
     */
    protected $renderer;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * Column constructor.
     * @param $id
     * @param array $options
     */
    public function __construct($id, array $options = array())
    {
        $this->id = $id;
        $this->name = ArrayUtils::get($options, 'name', $id);
        $this->sortable = ArrayUtils::get($options, 'sortable', true);
        $this->defaultSorting = ArrayUtils::get($options, 'default_sorting');
        $this->class = ArrayUtils::get($options, 'class');
        $this->width = ArrayUtils::get($options, 'width');
        $this->width = ArrayUtils::get($options, 'property');

        $this->options = $options;
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->renderer instanceof ColumnRendererInterface) {
            return $this->renderer->render($this, $this->options);
        }

        if ($this->renderer instanceof \Closure) {
            return call_user_func($this->renderer, [$this, $this->options]);
        }

        return '';
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * @return null|string
     */
    public function getDefaultSorting()
    {
        return $this->defaultSorting;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }
}