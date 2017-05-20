<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:43
 */
namespace Umbrella\CoreBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\DataTable\Builder\DataTableBuilder;
use Umbrella\CoreBundle\Utils\StringUtils;

/**
 * Class DataTableType
 * @package Umbrella\CoreBundle\DataTable
 */
class DataTableType
{

    /**
     * @param QueryBuilder $qb
     * @param array $options
     */
    public function buildQuery(QueryBuilder $qb, array $options = array())
    {

    }

    /**
     * @param DataTableBuilder $builder
     * @param array $options
     */
    public function buildDataTable(DataTableBuilder $builder, array $options = array())
    {

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * @return string
     */
    public function getId()
    {
        $reflexion = new \ReflectionClass($this);
        $className = preg_replace('/Type$/', '', $reflexion->getShortName());
        return StringUtils::to_underscore($className);
    }
}