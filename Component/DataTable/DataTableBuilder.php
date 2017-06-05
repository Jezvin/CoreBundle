<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:55.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\DataTable\Model\Column\Column;
use Umbrella\CoreBundle\Component\DataTable\Model\Column\SequenceColumn;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTableQuery;

/**
 * Class DataTableBuilder.
 */
class DataTableBuilder
{
    /**
     * @var ContainerInterface
     */
    protected $container;

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
     * @var OptionsResolver
     */
    protected $dtResolver;

    /**
     * DataTableBuilder constructor.
     *
     * @param ContainerInterface $container
     * @param OptionsResolver    $dtResolver
     * @param array              $options
     */
    public function __construct(ContainerInterface $container, OptionsResolver $dtResolver, array $options = array())
    {
        $this->container = $container;
        $this->qb = $this->container->get('doctrine.orm.entity_manager')->createQueryBuilder();
        $this->dtResolver = $dtResolver;
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
     *
     * @return $this
     */
    public function add($id, $columnClass, array $options = array())
    {
        $this->columns[$id] = array(
            'class' => $columnClass,
            'options' => $options,
        );

        return $this;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->columns[$id]);
    }

    /**
     * @param $id
     *
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
            $this->resolvedTable = new DataTable($this->container);
            $this->resolvedTable->query = new DataTableQuery($this->qb);
            $this->resolvedTable->columns = $this->resolveColumns();

            $options = $this->dtResolver->resolve($this->options);
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
        $hasSequenceColumn = false;

        foreach ($this->columns as $id => $column) {
            $resolvedColumn = $this->createColumn($column['class']);
            $resolver = new OptionsResolver();
            $resolvedColumn->configureOptions($resolver);

            $column['options']['id'] = $id;
            $options = $resolver->resolve($column['options']);
            $resolvedColumn->setOptions($options);

            // always put sequence column at first position and avoid multiple add
            if (is_a($resolvedColumn, SequenceColumn::class)) {
                if (!$hasSequenceColumn) {
                    array_unshift($resolvedColumns, $resolvedColumn);
                    $hasSequenceColumn = true;
                }
            } else {
                $resolvedColumns[] = $resolvedColumn;
            }
        }

        return $resolvedColumns;
    }

    /**
     * @param $class
     *
     * @return Column
     */
    protected function createColumn($class)
    {
        if (!is_subclass_of($class, Column::class) and !$class == Column::class) {
            throw new \InvalidArgumentException("Class '$class' must extends Column class.");
        }

        $column = new $class($this->container);

        return $column;
    }
}
