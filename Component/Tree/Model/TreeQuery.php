<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 14:58
 */

namespace Umbrella\CoreBundle\Component\Tree\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

/**
 * Class TreeQueryBuilder
 */
class TreeQuery
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
     * TreeQuery constructor.
     * @param QueryBuilder $qb
     * @param string $entityAlias
     */
    public function __construct(QueryBuilder $qb, $entityAlias = 'e')
    {
        $this->qb = $qb;
        $this->em = $qb->getEntityManager();
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
     * @param Tree $tree
     */
    public function build(Tree $tree)
    {
        $this->qb->select($this->entityAlias);

        $this->qb->from($tree->entityName, $this->entityAlias);
        $this->qb->addSelect('children');
        $this->qb->leftJoin($this->entityAlias . '.children', 'children');
        $this->qb->andWhere($this->entityAlias . '.parent IS NULL');
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->qb->getQuery()->getResult();
    }

}