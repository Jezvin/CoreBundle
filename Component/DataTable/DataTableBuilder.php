<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:55.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\DataTable\Model\Column\Column;
use Umbrella\CoreBundle\Component\DataTable\Model\Column\SequenceColumn;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;

/**
 * Class DataTableBuilder.
 */
class DataTableBuilder
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var array
     */
    private $options = array();

    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @var string
     */
    private $rowUrl;

    /**
     * @var string
     */
    private $loadUrl;

    /**
     * @var string
     */
    private $sequenceUrl;

    /**
     * @var \Closure
     */
    private $queryClosure;

    /**
     * @var array
     */
    private $columns = array();

    /**
     * @var DataTable|null
     */
    private $resolvedTable = null;

    /**
     * DataTableBuilder constructor.
     *
     * @param ContainerInterface $container
     * @param array              $options
     */
    public function __construct(ContainerInterface $container, array $options = array())
    {
        $this->container = $container;
        $this->router = $container->get('router');
        $this->options = $options;
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
     * @param $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param $route
     * @param array $params
     */
    public function setLoadAction($route, array $params = array())
    {
        $this->loadUrl = $this->router->generate($route, $params);
    }

    /**
     * @param $route
     * @param array $params
     */
    public function setRowAction($route, array $params = array())
    {
        $this->rowUrl = $this->router->generate($route, $params);
    }

    /**
     * @param $route
     * @param array $params
     */
    public function setSequenceAction($route, array $params = array())
    {
        $this->sequenceUrl = $this->router->generate($route, $params);
    }

    /**
     * @param \Closure $queryClosure
     * @return \Closure
     */
    public function setQuery(\Closure $queryClosure)
    {
        return $this->queryClosure = $queryClosure;
    }

    /**
     * @return DataTable
     */
    public function getTable()
    {
        if ($this->resolvedTable === null) {
            $this->resolvedTable = new DataTable($this->container);
            $this->resolvedTable->columns = $this->resolveColumns();

            $this->resolvedTable->setOptions($this->options);

            $this->resolvedTable->loadUrl = $this->loadUrl;
            $this->resolvedTable->loadMethod = $this->method;

            $this->resolvedTable->sequenceUrl = $this->sequenceUrl;
            $this->resolvedTable->sequenceMethod = $this->method;

            $this->resolvedTable->rowUrl = $this->rowUrl;
            $this->resolvedTable->rowMethod = $this->method;

            $this->resolvedTable->queryClosure = $this->queryClosure;
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
        if (!is_subclass_of($class, Column::class) && !$class == Column::class) {
            throw new \InvalidArgumentException("Class '$class' must extends Column class.");
        }

        $column = new $class($this->container);

        return $column;
    }
}
