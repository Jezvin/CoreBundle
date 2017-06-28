<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 15:03
 */

namespace Umbrellac\CoreBundle\Component\Tree;


use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TreeType
 */
class TreeType
{
    /**
     * @param QueryBuilder $qb
     * @param array        $options
     */
    public function buildQuery(QueryBuilder $qb, array $options = array())
    {
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }
}