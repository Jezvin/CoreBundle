<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Core\OptionsAwareInterface;
use Umbrella\CoreBundle\Component\Routing\UmbrellaRoute;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;
use Umbrella\CoreBundle\Component\Toolbar\Toolbar;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DataTable.
 */
class DataTable implements OptionsAwareInterface
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
     * @var array
     */
    public $lengthMenu;

    /**
     * @var int
     */
    public $pageLength;

    /**
     * @var bool
     */
    public $lengthChange;

    /**
     * @var bool
     */
    public $fixedHeader;

    /**
     * @var bool
     */
    public $sortable;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $loadUrl;

    /**
     * @var string
     */
    public $loadMethod;

    /**
     * @var string
     */
    public $sequenceUrl;

    /**
     * @var string
     */
    public $sequenceMethod;

    /**
     * @var string
     */
    public $rowUrl;

    /**
     * @var string
     */
    public $rowMethod;

    /**
     * @var string
     */
    public $entityName;

    /**
     * @var \Closure|null
     */
    public $queryClosure;

    // Model

    /**
     * @var array
     */
    public $columns = array();

    /**
     * @var DataTableQuery
     */
    private $query;

    /**
     * @var Toolbar
     */
    private $toolbar;

    /**
     * @var Paginator
     */
    private $results = null;

    /**
     * @var array
     */
    private $fetchedResults = null;

    /**
     * @var int
     */
    private $draw;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * DataTable constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
       $this->container = $container;
       $this->router = $container->get('router');
       $this->query = new DataTableQuery($container->get('doctrine.orm.entity_manager'));
    }

    /**
     * @param Request $request
     */
    public function handleRequest(Request $request)
    {
        $this->draw = $request->get('draw');
        $this->query->handleRequest($request, $this);
    }

    /**
     * @return Paginator
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
     * @return Toolbar
     */
    public function getToolbar()
    {
        return $this->toolbar;
    }

    /**
     * @return array
     */
    protected function fetchAll()
    {
        if ($this->fetchedResults === null) {
            $this->fetchedResults = array();
            foreach ($this->getResults() as $result) {
                $row = array();

                // Add row id data
                $row['DT_RowId'] = PropertyAccess::createPropertyAccessor()->getValue($result, 'id');

                foreach ($this->columns as $column) {
                    $row[] = $column->render($result);
                }

                $this->fetchedResults[] = $row;
            }
        }
    }

    /**
     * @return array
     */
    public function getApiResults()
    {
        $this->fetchAll();

        return array(
            'draw' => $this->draw,
            'recordsTotal' => count($this->results),
            'recordsFiltered' => count($this->results),
            'data' => $this->fetchedResults,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'entity',
            'ajax_load_route',
        ));

        $resolver->setDefined(array(
            'id',

            'method',

            'ajax_load_route',
            'ajax_sequence_route',
            'ajax_row_route',

            'entity',
            'query',

            'class',
            'template',
            'length_change',
            'length_menu',
            'page_length',
            'fixed_header',
            'toolbar',
            'toolbar_options',
            'sortable',

            'translation_prefix',
        ));

        $resolver->setAllowedTypes('length_change', 'bool');
        $resolver->setAllowedTypes('length_menu', 'array');
        $resolver->setAllowedTypes('page_length', 'int');
        $resolver->setAllowedTypes('fixed_header', 'bool');
        $resolver->setAllowedTypes('toolbar', ['Umbrella\CoreBundle\Component\Toolbar\AbstractToolbar', 'string']);
        $resolver->setAllowedTypes('toolbar_options', 'array');
        $resolver->setAllowedTypes('sortable', 'bool');
        $resolver->setAllowedTypes('query', ['null', 'callable']);

        $resolver->setDefault('id', $this->id);
        $resolver->setDefault('template', 'UmbrellaCoreBundle:DataTable:datatable.html.twig');
        $resolver->setDefault('method', 'GET');
        $resolver->setDefault('length_change', false);
        $resolver->setDefault('length_menu', array(25, 50, 100));
        $resolver->setDefault('page_length', 25);
        $resolver->setDefault('fixed_header', false);
        $resolver->setDefault('toolbar_options', array());
        $resolver->setDefault('sortable', false);
        $resolver->setDefault('translation_prefix', 'table.');
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = array())
    {
        $this->id = ArrayUtils::get($options, 'id', 'table_'.substr(md5(uniqid('', true)), 0, 12));
        $this->class = ArrayUtils::get($options, 'class');
        $this->template = ArrayUtils::get($options, 'template');

        $this->loadUrl = UmbrellaRoute::createFromOptions($options['ajax_load_route'])->generateUrl($this->router);
        $this->loadMethod = ArrayUtils::get($options, 'method');

        if (isset($options['ajax_sequence_route'])) {
            $this->sequenceUrl = UmbrellaRoute::createFromOptions($options['ajax_sequence_route'])->generateUrl($this->router);
        }
        $this->sequenceMethod = ArrayUtils::get($options, 'method');

        if (isset($options['ajax_row_route'])) {
            // set an random id to replace it on view
            $this->rowUrl = UmbrellaRoute::createFromOptions($options['ajax_row_route'])->generateUrl($this->router, ['id' => 123456789]);
        }
        $this->rowMethod = ArrayUtils::get($options, 'method');

        $this->entityName = ArrayUtils::get($options, 'entity');
        $this->queryClosure = ArrayUtils::get($options, 'query');

        $this->lengthChange = ArrayUtils::get($options, 'length_change');
        $this->lengthMenu = ArrayUtils::get($options, 'length_menu');
        $this->pageLength = ArrayUtils::get($options, 'page_length');

        $this->fixedHeader = ArrayUtils::get($options, 'fixed_header');
        $this->sortable = ArrayUtils::get($options, 'sortable');

        $this->translationPrefix = ArrayUtils::get($options, 'translation_prefix');

        if (isset($options['toolbar'])) {
            $this->toolbar = is_string($options['toolbar'])
                ? $this->container->get(ToolbarFactory::class)->create($options['toolbar'], $options['toolbar_options'])
                : $options['toolbar'];
        }
    }

}
