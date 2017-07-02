<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model\Column;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Core\OptionsAwareInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

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
     * @var null|\Closure
     */
    public $renderer;

    /**
     * @var null|\Closure
     */
    public $labelRenderer;

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
        return $this->defaultRender($entity);
    }

    /**
     * @param $translationPrefix
     * @return string
     */
    public function renderLabel($translationPrefix)
    {
        if ($this->labelRenderer instanceof \Closure) {
            return call_user_func($this->labelRenderer, $this, $translationPrefix);
        }
        return empty($this->label) ? '' : $this->container->get('translator')->trans($translationPrefix . $this->label);
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
        $this->labelRenderer = ArrayUtils::get($options, 'label_renderer');
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
            'label_renderer'
        ));

        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('renderer', array('null', 'callable'));
        $resolver->setAllowedTypes('label_renderer', array('null', 'callable'));
        $resolver->setAllowedValues('order', ['ASC', 'DESC']);

        $resolver->setDefault('orderable', true);
    }
}
