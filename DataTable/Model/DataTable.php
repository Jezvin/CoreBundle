<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46
 */

namespace Umbrella\CoreBundle\DataTable\Model;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
    public $lengthMenu = array(25, 50, 100);

    /**
     * @var int
     */
    public $pageLength = 25;

    /**
     * @var bool
     */
    public $lengthChange = false;

    /**
     * @var bool
     */
    public $fixedHeader = false;

    /**
     * @var string
     */
    public $template = 'UmbrellaCoreBundle:DataTable:datatable.html.twig' ;

    /**
     * @var string
     */
    public $ajaxUrl;

    /**
     * @var string
     */
    public $ajaxType = 'POST';

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
     * @var array
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
        $this->query->handleRequest($this, $request);
    }

    /**
     * @return array
     */
    public function getResults()
    {
        if ($this->results === null) {
            $this->query->init($this->entityName);
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
            $results = $this->getResults();

            foreach ($results as $result) {
                $row = array();

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
        $count = $this->query->count();

        return array(
            'draw' => $this->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $this->fetchedResults
        );
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'entity',
            'ajax_url'
        ));

        $resolver->setDefined(array(
            'id',
            'ajax_type',
            'class',
            'template',
            'length_change',
            'length_menu',
            'page_length',
            'fixed_header'
        ));

        $resolver->setAllowedTypes('length_change', 'bool');
        $resolver->setAllowedTypes('length_menu', 'array');
        $resolver->setAllowedTypes('page_length', 'int');
        $resolver->setAllowedTypes('fixed_header', 'bool');
    }

    /**
     * @inheritdoc
     */
    public function setOptions(array $options = array())
    {
        $this->id = ArrayUtils::get($options, 'id', $this->id);
        $this->class = ArrayUtils::get($options, 'class');
        $this->template = ArrayUtils::get($options, 'template', $this->template);

        $this->ajaxUrl = ArrayUtils::get($options, 'ajax_url');
        $this->ajaxType = ArrayUtils::get($options, 'ajax_type', $this->ajaxType);

        $this->entityName = ArrayUtils::get($options, 'entity');

        $this->lengthChange = ArrayUtils::get($options, 'length_change', $this->lengthChange);
        $this->lengthMenu = ArrayUtils::get($options, 'length_menu', $this->lengthMenu);
        $this->pageLength = ArrayUtils::get($options, 'page_length', $this->pageLength);

        $this->fixedHeader = ArrayUtils::get($options, 'fixed_header', $this->fixedHeader);
    }
}