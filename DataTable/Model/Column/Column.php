<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46
 */

namespace Umbrella\CoreBundle\DataTable\Model\Column;
use Umbrella\CoreBundle\DataTable\Model\OptionResolverInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Column
 * @package Umbrella\CoreBundle\DataTable\Model\Column
 */
abstract class Column implements OptionResolverInterface
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var boolean
     */
    public $sortable = true;

    /**
     * @var string|null
     */
    public $defaultSorting = null;

    /**
     * @var string
     */
    public $class;
    
    /**
     * @var string
     */
    public $width;

    /**
     * @var mixed
     */
    public $renderer;

    /**
     * Column constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param array $options
     */
    public function resolveOptions(array $options = array())
    {
        $this->name = ArrayUtils::get($options, 'name', $this->id);
        $this->sortable = ArrayUtils::get($options, 'sortable', true);
        $this->defaultSorting = ArrayUtils::get($options, 'default_sorting');
        $this->class = ArrayUtils::get($options, 'class');
        $this->width = ArrayUtils::get($options, 'width');
        $this->width = ArrayUtils::get($options, 'property');
    }

    /**
     * @param $result
     */
    abstract public function render($result);
}