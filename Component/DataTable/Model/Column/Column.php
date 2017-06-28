<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrellac\CoreBundle\Component\DataTable\Model\Column;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrellac\CoreBundle\Component\Core\OptionsAwareInterface;
use Umbrellac\CoreBundle\Component\DataTable\Renderer\ColumnRendererInterface;
use Umbrellac\CoreBundle\Utils\ArrayUtils;

/**
 * Class Column.
 */
class Column implements OptionsAwareInterface
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
     * @var bool
     */
    public $orderable;

    /**
     * @var string|null
     */
    public $order;

    /**
     * @var string
     */
    public $class;

    /**
        return $this->qb;
     * @var string
     */
    public $width;

    /**
     * @var mixed
     */
    public $renderer;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Column constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function render($entity)
    {
        if ($this->renderer instanceof \Closure) {
            return call_user_func($this->renderer, $this, $entity);
        }

        if ($this->renderer instanceof ColumnRendererInterface) {

            // FIXME
            if ($this->renderer instanceof ContainerAwareInterface) {
                $this->renderer->setContainer($this->container);
            }

            return $this->renderer->render($this, $entity);
        }

        return $this->defaultRender($entity);
    }

    /**
     * @param $entity
     *
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
        $this->id = $options['id'];
        $this->label = ArrayUtils::get($options, 'label', $this->id);
        $this->orderable = ArrayUtils::get($options, 'orderable');
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
        $resolver->setRequired(array(
            'id'
        ));

        $resolver->setDefined(array(
            'label',
            'orderable',
            'order',
            'class',
            'width',
            'renderer',
        ));

        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('renderer', array(
            'null',
            'Umbrella\CoreBundle\Component\DataTable\Renderer\ColumnRendererInterface',
            'callable',
        ));

        $resolver->setAllowedValues('order', ['ASC', 'DESC']);
        $resolver->setDefault('orderable', true);
    }
}
