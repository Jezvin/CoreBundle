<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46
 */

namespace Umbrella\CoreBundle\DataTable;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
     * @param $id
     * @param ContainerInterface $container
     */
    public function __construct($id, ContainerInterface $container)
    {
        $this->id = $id;
        $this->queryBuilder = new DataTableQueryBuilder($container->get('doctrine.orm.entity_manager'));
        $this->pager = new Pager();
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