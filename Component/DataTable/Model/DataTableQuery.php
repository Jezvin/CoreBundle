<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:10.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Umbrella\CoreBundle\Component\DataTable\Model\Column\Column;
use Umbrella\CoreBundle\Component\DataTable\Model\Column\JoinColumn;
use Umbrella\CoreBundle\Component\DataTable\Model\Column\PropertyColumn;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class DataTableQuery.
 */
class DataTableQuery
{
    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityAlias;

    /**
     * DataTableQuery constructor.
     * @param EntityManager $em
     * @param string $entityAlias
     */
    public function __construct(EntityManager $em, $entityAlias = 'e')
    {
        $this->em = $em;
        $this->qb = $em->createQueryBuilder();
        $this->entityAlias = $entityAlias;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * Build query.
     *
     * @param DataTable $table
     */
    public function build(DataTable $table)
    {
        $this->qb->select($this->entityAlias);
        $this->qb->from($table->entityName, $this->entityAlias);
        $this->buildJoin($table->columns);

        if ($table->queryClosure) {
            call_user_func($table->queryClosure, $this->qb);
        }
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
                    $this->qb->leftJoin($this->entityAlias.'.'.$column->join, $column->join);
                    $this->qb->addSelect($column->join);
                    break;

                case Join::INNER_JOIN:
                    $this->qb->innerJoin($this->entityAlias.'.'.$column->join, $column->join);
                    $this->qb->addSelect($column->join);
                    break;
            }
        }
    }

    /**
     * @param Request $request
     * @param DataTable $table
     */
    public function handleRequest(Request $request, DataTable $table)
    {
        if ('GET' === $table->loadMethod || 'HEAD' === $table->loadMethod  || 'TRACE' === $table->loadMethod ) {
            $data = $request->query->all();
        } else {
            $data = $request->request->all();
        }

        $start = ArrayUtils::get($data, 'start', 0);
        $length = ArrayUtils::get($data,'length');
        $orders = ArrayUtils::get($data,'order', array());

        // pagination
        $this->qb->setFirstResult($start);
        if ($length !== null) {
            $this->qb->setMaxResults($length);
        }

        // toolbar
        if ($table->getToolbar() !== null) {
            $table->getToolbar()->handleRequest($this->qb, $request);
        }

        // order by

        foreach ($orders as $order) {
            if (!isset($order['column']) || !isset($order['dir'])) {
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

            $this->qb->addOrderBy($this->entityAlias.'.'.$column->propertyPath, $dir == 'asc' ? 'ASC' : 'DESC');
        }
    }

    /**
     * @return Paginator
     */
    public function getResults()
    {
        // hack to keep order : see https://github.com/doctrine/doctrine2/issues/3426
        $result = new Paginator($this->qb);
        $result->getIterator();

        return $result;
    }
}
