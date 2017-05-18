<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:55
 */
namespace Umbrella\CoreBundle\DataTable\Builder;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\DataTable\Model\Column\Column;
use Umbrella\CoreBundle\DataTable\Model\DataTable;
use Umbrella\CoreBundle\DataTable\Model\DataTableQuery;

/**
 * Class DataTableBuilder
 * @package Umbrella\CoreBundle\DataTable\Builder
 */
class DataTableBuilder
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Table id
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $options = array();
    
    /**
     * @var array
     */
    protected $columns = array();

    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var DataTable|null
     */
    protected $resolvedTable = null;


    /**
     * DataTableBuilder constructor.
     * @param ContainerInterface $container
     * @param $id
     * @param array $options
     */
    public function __construct(ContainerInterface $container, $id, array $options = array())
    {
        $this->container = $container;
        $this->qb = $this->container->get('doctrine.orm.entity_manager')->createQueryBuilder();
        $this->id = $id;
        $this->options = $options;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * @param $id
     * @param $columnClass
     * @param array $options
     * @return $this
     */
    public function add($id, $columnClass, array $options = array())
    {
        if (!is_subclass_of($columnClass, Column::class)) {
            throw new \InvalidArgumentException("Class '$columnClass' must extends Column class.");
        }

        /** @var Column $column */
        $column = new $columnClass($id);
        $column->resolveOptions($options);
        $this->columns[$id] = $column;
        return $this;
    }

    /**
     * @param $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->columns[$id]);
    }

    /**
     * @param $id
     * @return Column
     */
    public function get($id)
    {
        return $this->columns[$id];
    }

    /**
     * @return DataTable
     */
    public function getTable()
    {
        if ($this->resolvedTable === null) {
            $this->resolvedTable = new DataTable();
            $this->resolvedTable->query = new DataTableQuery($this->qb);
            $this->resolvedTable->id = $this->id;
            $this->resolvedTable->resolveOptions($this->options);
            $this->resolvedTable->columns = array_values($this->columns);
            $this->resolvedTable->setContainer($this->container);
        }
        return $this->resolvedTable;
    }
}