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
use Symfony\Component\OptionsResolver\OptionsResolver;
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
        $this->columns[$id] = array(
            'class' => $columnClass,
            'options' => $options
        );
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
            $this->resolvedTable->setContainer($this->container);
            $this->resolvedTable->columns = $this->resolveColumns();

            $resolver = new OptionsResolver();
            $this->resolvedTable->configureOptions($resolver);
            $options = $resolver->resolve($this->options);
            $this->resolvedTable->setOptions($options);

        }
        return $this->resolvedTable;
    }

    /**
     * @return array
     */
    protected function resolveColumns()
    {
        $resolvedColumns = array();
        foreach ($this->columns as $id => $column) {

            $resolvedColumn = $this->createColumn($id, $column['class']);
            $resolver = new OptionsResolver();
            $resolvedColumn->configureOptions($resolver);
            $options = $resolver->resolve($column['options']);
            $resolvedColumn->setOptions($options);
            $resolvedColumns[] = $resolvedColumn;
        }
        return $resolvedColumns;
    }

    /**
     * @param $id
     * @param $class
     * @return Column
     */
    protected function createColumn($id, $class)
    {
        if (!is_subclass_of($class, Column::class)) {
            throw new \InvalidArgumentException("Class '$class' must extends Column class.");
        }

        $column = new $class($id);
        $column->setContainer($this->container);

        return $column;
    }
}