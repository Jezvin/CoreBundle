<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 13:46
 */
namespace Umbrella\CoreBundle\Component\Tree\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Core\OptionsAwareInterface;
use Umbrella\CoreBundle\Component\Tree\Entity\BaseTreeEntity;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Tree
 */
class Tree implements OptionsAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

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
    public $entityRootAlias;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $templateRow;

    /**
     * @var string
     */
    public $relocateUrl;

    /**
     * @var string
     */
    public $relocateType;

    /**
     * @var null|\Closure
     */
    public $queryClosure;

    // Model

    /**
     * @var TreeQuery
     */
    private $query;

    /**
     * @var BaseTreeEntity|null
     */
    private $result = null;

    /**
     * Tree constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->id = 'tree_'.substr(md5(uniqid('', true)), 0, 12);
        $this->query = new TreeQuery($container->get('doctrine.orm.entity_manager'));
    }

    /**
     * @return BaseTreeEntity|null
     */
    public function getResult()
    {
        if ($this->result === null) {
            $this->query->build($this);
            $this->result = $this->query->getResult();
        }

        return $this->result;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->relocateUrl = $this->container->get('router')->generate($options['ajax_relocate_route']);
        $this->relocateType = ArrayUtils::get($options, 'ajax_relocate_type');

        $this->id = ArrayUtils::get($options, 'id');
        $this->class = ArrayUtils::get($options, 'class');
        $this->collapsable = ArrayUtils::get($options, 'collapsable');
        $this->startExpanded = ArrayUtils::get($options, 'start_expanded');
        $this->template = ArrayUtils::get($options, 'template');
        $this->templateRow = ArrayUtils::get($options, 'template_row');
        $this->entityName = ArrayUtils::get($options, 'entity');
        $this->entityRootAlias = ArrayUtils::get($options, 'root_alias');
        $this->translationPrefix = ArrayUtils::get($options, 'translationPrefix');
        $this->queryClosure = ArrayUtils::get($options, 'query');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'entity',
            'ajax_relocate_route',
        ));

        $resolver->setDefined(array(
            'id',
            'root_alias',

            'ajax_relocate_route',
            'ajax_relocate_type',

            'class',
            'collapsable',
            'start_expanded',
            'template',
            'template_row',

            'translation_prefix',
            'query',
        ));



        $resolver->setAllowedTypes('collapsable', array('bool'));
        $resolver->setAllowedTypes('start_expanded', array('bool'));
        $resolver->setAllowedTypes('query', array('null', 'callable'));

        $resolver->setDefault('id', $this->id);
        $resolver->setDefault('ajax_relocate_type', 'POST');
        $resolver->setDefault('class', 'umbrella-tree');
        $resolver->setDefault('collapsable', true);
        $resolver->setDefault('start_expanded', true);
        $resolver->setDefault('template', 'UmbrellaCoreBundle:Tree:tree.html.twig');
        $resolver->setDefault('template_row', 'UmbrellaCoreBundle:Tree:tree_row.html.twig');
    }
}