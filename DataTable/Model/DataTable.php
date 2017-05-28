<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46
 */

namespace Umbrella\CoreBundle\DataTable\Model;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\Toolbar\Model\AbstractToolbar;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DataTable
 * @package Umbrella\CoreBundle\Model\Table
 */
class DataTable implements OptionsAwareInterface, ContainerAwareInterface
{
    /**
     * @var string
     */
    public $id;

    // Options

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
    public $ajaxUrl;

    /**
     * @var string
     */
    public $ajaxType;

    /**
     * @var string
     */
    public $entityName;

    // Model

    /**
     * @var array
     */
    public $columns = array();

    /**
     * @var DataTableQuery
     */
    public $query;

    /**
     * @var AbstractToolbar
     */
    public $toolbar;

    /**
     * @var Paginator
     */
    protected $results = null;

    /**
     * @var array
     */
    protected $fetchedResults = null;


    /* Handle request */
    protected $draw;
    
    use ContainerAwareTrait;

    /**
     * DataTable constructor
     */
    public function __construct()
    {
        $this->id = 'table_' . substr(md5(uniqid('', true)), 0, 12);
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
            'data' => $this->fetchedResults
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'entity'
        ));

        $resolver->setDefined(array(
            'id',
            'ajax_type',
            'ajax_url',
            'ajax_route',
            'class',
            'template',
            'length_change',
            'length_menu',
            'page_length',
            'fixed_header',
            'toolbar',
            'sortable'
        ));

        $resolver->setAllowedTypes('length_change', 'bool');
        $resolver->setAllowedTypes('length_menu', 'array');
        $resolver->setAllowedTypes('page_length', 'int');
        $resolver->setAllowedTypes('fixed_header', 'bool');
        $resolver->setAllowedTypes('toolbar', ['Umbrella\CoreBundle\Toolbar\AbstractToolbar', 'string']);
        $resolver->setAllowedTypes('sortable', 'bool');

        $resolver->setDefault('id', $this->id);
        $resolver->setDefault('template', 'UmbrellaCoreBundle:DataTable:datatable.html.twig');
        $resolver->setDefault('ajax_type', 'POST');
        $resolver->setDefault('length_change', false);
        $resolver->setDefault('length_menu', array(25, 50, 100));
        $resolver->setDefault('page_length', 25);
        $resolver->setDefault('fixed_header', false);
        $resolver->setDefault('sortable', false);
    }

    /**
     * @inheritdoc
     */
    public function setOptions(array $options = array())
    {
        $this->id = ArrayUtils::get($options, 'id');
        $this->class = ArrayUtils::get($options, 'class');
        $this->template = ArrayUtils::get($options, 'template');

        $this->ajaxUrl = ArrayUtils::get($options, 'ajax_url');
        $this->ajaxType = ArrayUtils::get($options, 'ajax_type');

        if (isset($options['ajax_route'])) {
            $this->ajaxUrl = $this->container->get('router')->generate($options['ajax_route']);
        }

        $this->entityName = ArrayUtils::get($options, 'entity');

        $this->lengthChange = ArrayUtils::get($options, 'length_change');
        $this->lengthMenu = ArrayUtils::get($options, 'length_menu');
        $this->pageLength = ArrayUtils::get($options, 'page_length');

        $this->fixedHeader = ArrayUtils::get($options, 'fixed_header');
        $this->sortable = ArrayUtils::get($options, 'sortable');

        if (isset($options['toolbar'])) {
            $this->toolbar = is_string($options['toolbar'])
                ? $this->container->get('umbrella.toolbar_factory')->create($options['toolbar'])
                : $options['toolbar'];
        }
    }
}