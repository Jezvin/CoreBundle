<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:10
 */

namespace Umbrella\CoreBundle\DataTable;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;

/**
 * Class DataTableQueryBuilder
 * @package Umbrella\CoreBundle\Model\Table
 */
class DataTableQueryBuilder
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * DataTableQueryBuilder constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->queryBuilder = $em->createQueryBuilder();
    }

    /**
     * Empty query builder
     */
    public function reset()
    {
        $this->queryBuilder = $this->em->createQueryBuilder();
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * Build query builder
     *
     * @param DataTable $table
     */
    public function build(DataTable $table)
    {
        $this->queryBuilder->select($table->getEntityAlias());
        $this->queryBuilder->from($table->getEntityName(), $table->getEntityAlias());

        /** @var Column $column */
        foreach ($table->getColumns() as $column) {
            // do something good here
        }
    }
    
    

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->queryBuilder->getQuery()->getResult();
    }

}