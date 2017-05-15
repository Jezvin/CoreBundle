<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46
 */

namespace Umbrella\CoreBundle\DataTable;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DataTable
 * @package Umbrella\CoreBundle\Model\Table
 */
class DataTable
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $template = 'UmbrellaCoreBundle:DataTable:datatable.html.twig' ;

    /**
     * @var string
     */
    protected $ajaxUrl;

    /**
     * @var string
     */
    protected $ajaxType = 'POST';

    /**
     * @var array
     */
    protected $columns = array();

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $entityAlias;

    /**
     * @var DataTableQueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var Pager
     */
    protected $pager;

    /**
     * @var array
     */
    protected $results = null;

    /**
     * DataTable constructor.
     * @param ContainerInterface $container
     * @param $id
     * @param array $options
     */
    public function __construct(ContainerInterface $container, $id, array $options = array())
    {
        $this->id = $id;
        $this->queryBuilder = new DataTableQueryBuilder($container->get('doctrine.orm.entity_manager'));
        $this->pager = new Pager();
        
        $this->class = ArrayUtils::get($options, 'class');
        $this->template = ArrayUtils::get($options, 'template', $this->template);

        if (isset($options['ajax'])) {
            if (is_array($options['ajax'])) {
                $this->ajaxUrl = ArrayUtils::get($options['ajax'], 'url');
                $this->ajaxType = ArrayUtils::get($options['ajax'], 'type', $this->ajaxType);
            } else {
                $this->ajaxUrl = $options['ajax'];
            }
        }
    }

    /**
     * @param $entityName
     * @param string $entityAlias
     * @return $this
     */
    public function setEntity($entityName, $entityAlias = 'e')
    {
        $this->entityName = $entityName;
        $this->entityAlias = $entityAlias;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }
    /**
     * @return string
     */
    public function getEntityAlias()
    {
        return $this->entityAlias;
    }

    /**
     * @return DataTableQueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * @param $id
     * @param $columnClass
     * @param array $options
     * @return $this
     */
    public function add($id, $columnClass, array $options = array())
    {
        $column = new $columnClass($id, $options);
        return $this->addColumn($column);
    }

    /**
     * @param Column $column
     * @return $this
     */
    public function addColumn(Column $column)
    {
        $this->columns[$column->getId()] = $column;
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function removeColumn($id)
    {
        unset($this->columns[$id]);
        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->columns);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->ajaxUrl;
    }

    /**
     * @return string
     */
    public function getAjaxType()
    {
        return $this->ajaxType;
    }

    /**
     * @return Pager
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        if ($this->results === null) {
            $this->queryBuilder->build($this);
            $this->results = $this->queryBuilder->getResults();
        }
        return $this->results;
    }

}