<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46
 */

namespace Umbrella\CoreBundle\DataTable\Model\Column;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Model\OptionsAwareInterface;
use Umbrella\CoreBundle\DataTable\Renderer\ColumnRendererInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Column
 * @package Umbrella\CoreBundle\DataTable\Model\Column
 */
class Column implements OptionsAwareInterface, ContainerAwareInterface
{

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $label;

    /**
     * @var boolean
     */
    public $orderable = true;

    /**
     * @var string|null
     */
    public $order = null;

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
    
    
    use ContainerAwareTrait;

    /**
     * Column constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param $entity
     * @return string
     */
    public function render($entity) {
        if ($this->renderer instanceof \Closure) {
            return call_user_func($this->renderer, $this, $entity);
        }

        if ($this->renderer instanceof ColumnRendererInterface) {
            if ($this->renderer instanceof ContainerAwareInterface) {
                $this->renderer->setContainer($this->container);
            }
            return $this->renderer->render($this, $entity);
        }
        
        return $this->defaultRender($entity);
    }

    /**
     * @param $entity
     * @return string
     */
    public function defaultRender($entity)
    {
        return (string) $entity;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->label = ArrayUtils::get($options, 'label', $this->id);
        $this->orderable = ArrayUtils::get($options, 'orderable', true);
        $this->order = ArrayUtils::get($options, 'order');
        $this->class = ArrayUtils::get($options, 'class');
        $this->width = ArrayUtils::get($options, 'width');
        $this->renderer = ArrayUtils::get($options, 'renderer');
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'label',
            'orderable',
            'order',
            'class',
            'width',
            'renderer'
        ));

        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('renderer', array(
            'null',
            'Umbrella\CoreBundle\DataTable\Renderer\ColumnRendererInterface',
            'callable'
        ));

        $resolver->setAllowedValues('order', ['ASC', 'DESC']);
    }
}