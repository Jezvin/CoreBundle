<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:10
 */

namespace Umbrella\CoreBundle\DataTable\Model;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Umbrella\CoreBundle\DataTable\Model\Column\Column;
use Umbrella\CoreBundle\DataTable\Model\Column\JoinColumn;
use Umbrella\CoreBundle\DataTable\Model\Column\PropertyColumn;

/**
 * Class DataTableQuery
 * @package Umbrella\CoreBundle\DataTable\Model
 */
class DataTableQuery
{

    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var QueryBuilder
     */
    protected $qbCount;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityAlias;

    /**
     * DataTableQueryBuilder constructor.
     * @param QueryBuilder $qb
     * @param string $entityAlias
     */
    public function __construct(QueryBuilder $qb, $entityAlias = 'e')
    {
        $this->qb = $qb;
        $this->qbCount = clone $qb;
        $this->em = $qb->getEntityManager();
        $this->entityAlias = $entityAlias;
    }

    /**
     * Build query
     *
     * @param DataTable $table
     */
    public function build(DataTable $table)
    {
        $this->qb->select($this->entityAlias);
        $this->qb->from($table->entityName, $this->entityAlias);
        $this->buildJoin($table->columns);

        $this->qbCount->select("COUNT($this->entityAlias.id)");
        $this->qbCount->from($table->entityName, $this->entityAlias);
    }

    /**
     * @param array $columns
     */
    protected function buildJoin(array $columns)
    {
        /** @var Column $column */
        foreach ($columns as $column) {
            if (!is_a($column, JoinColumn::class)) {
                continue;
            }

            /** @var JoinColumn $column */
            switch ($column->queryJoin) {
                case Join::LEFT_JOIN:
                    $this->qb->leftJoin($this->entityAlias . '.' . $column->join, $column->join);
                    $this->qb->addSelect($column->join);
                    break;

                case Join::INNER_JOIN:
                    $this->qb->innerJoin($this->entityAlias . '.' . $column->join, $column->join);
                    $this->qb->addSelect($column->join);
                    break;

            }
        }
    }

    /**
     * @param DataTable $table
     * @param Request $request
     * @throws \Exception
     */
    public function handleRequest(DataTable $table, Request $request)
    {
        // pagination
        $start = $request->get('start', 0);
        $length = $request->get('length');
        
        $this->qb->setFirstResult($start);
        if ($length !== null) {
            $this->qb->setMaxResults($length);
        }

        // order by
        $orders = $request->get('order', array());
        foreach ($orders as $order) {
            if (!isset($order['column']) or !isset($order['dir'])) {
                continue; // request valid ?
            }

            $idx = $order['column'];
            $dir = $order['dir'];

            if (!isset($table->columns[$idx])) {
                continue; // column exist ?
            }

            /** @var PropertyColumn $column */
            $column = $table->columns[$idx];

            if (!is_a($column, PropertyColumn::class)) {
                continue; // is entity column ?
            }

            $this->qb->addOrderBy($this->entityAlias . '.' . $column->propertyPath, $dir == 'asc' ? 'ASC' : 'DESC');
        }
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->qb->getQuery()->getResult();
    }

    /**
     * @return integer
     */
    public function count()
    {
        return $this->qbCount->getQuery()->getSingleScalarResult();
    }

}