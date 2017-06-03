<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 18:43.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DataTableType.
 */
class DataTableType
{
    /**
     * @param QueryBuilder $qb
     * @param array        $options
     */
    public function buildQuery(QueryBuilder $qb, array $options = array())
    {
    }

    /**
     * @param DataTableBuilder $builder
     * @param array            $options
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
}
