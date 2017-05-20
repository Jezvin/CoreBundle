<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:51
 */
namespace Umbrella\CoreBundle\DataTable\Factory;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Builder\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\DataTable\Model\DataTable;

/**
 * Class DataTableFactory
 * @package Umbrella\CoreBundle\DataTable\Factory
 */
class DataTableFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DataTableFactory constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $typeClass
     * @param array $options
     * @return DataTable
     */
    public function create($typeClass, array $options = array())
    {
        return $this->createBuilder($typeClass, $options)->getTable();
    }

    /**
     * @param string $typeClass
     * @param array $options
     * @return DataTableBuilder
     */
    public function createBuilder($typeClass = 'Umbrella\CoreBundle\DataTable\DataTableType', array $options = array())
    {
        $type = $this->createType($typeClass);

        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $builder = new DataTableBuilder($this->container, $options);
        $type->buildDataTable($builder, $options);
        $type->buildQuery($builder->getQueryBuilder(), $options);

        return $builder;
    }

    /**
     * @param $typeClass
     * @return DataTableType
     */
    protected function createType($typeClass)
    {
        if ($typeClass !== DataTableType::class and !is_subclass_of($typeClass, DataTableType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends DataTableType class.");
        }

        $type = new $typeClass();
        if (is_a($type, ContainerAwareInterface::class)) {
            $type->setContainer($this->container);
        }

        return $type;
    }

}