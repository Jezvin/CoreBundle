<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 13:46
 */
namespace Umbrella\CoreBundle\Component\Tree\Model;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Core\OptionsAwareInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Tree
 */
class Tree implements OptionsAwareInterface, ContainerAwareInterface
{

    /**
     * @var string
     */
    public $id;

    // Options

    /**
     * @var string
     */
    public $translationPrefix;

    /**
     * @var string
     */
    public $class;

    /**
     * @var bool
     */
    public $collapsable;

    /**
     * @var bool
     */
    public $startExpanded;

    /**
     * @var string
     */
    public $entityName;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $templateRow;

    use ContainerAwareTrait;

    // Model

    /**
     * @var TreeQuery
     */
    public $query;

    /**
     * @var array
     */
    private $results = null;

    /**
     * Tree constructor.
     */
    public function __construct()
    {
        $this->id = 'tree_'.substr(md5(uniqid('', true)), 0, 12);
    }

    /**
     * @return array
     */
    public function getResults()
    {
        if ($this->results === null) {
            $this->query->build($this);
            $this->results = $this->query->getResults();
        }

        return $this->results;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->id = ArrayUtils::get($options, 'id');
        $this->class = ArrayUtils::get($options, 'class');
        $this->collapsable = ArrayUtils::get($options, 'collapsable');
        $this->startExpanded = ArrayUtils::get($options, 'start_expanded');
        $this->template = ArrayUtils::get($options, 'template');
        $this->templateRow = ArrayUtils::get($options, 'template_row');
        $this->entityName = ArrayUtils::get($options, 'entity');
        $this->translationPrefix = ArrayUtils::get($options, 'translationPrefix');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'entity',
        ));

        $resolver->setDefined(array(
            'id',

            'class',
            'collapsable',
            'start_expanded',
            'template',
            'template_row',

            'translation_prefix',
        ));

        $resolver->setAllowedTypes('collapsable', array('bool'));
        $resolver->setAllowedTypes('start_expanded', array('bool'));

        $resolver->setDefault('id', $this->id);
        $resolver->setDefault('class', 'umbrella-tree');
        $resolver->setDefault('collapsable', true);
        $resolver->setDefault('start_expanded', true);
        $resolver->setDefault('template', 'UmbrellaCoreBundle:Tree:tree.html.twig');
        $resolver->setDefault('template_row', 'UmbrellaCoreBundle:Tree:tree_row.html.twig');
    }
}