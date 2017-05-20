<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 23:17
 */

namespace Umbrella\CoreBundle\Toolbar;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormInterface;

/**
 * Class Toolbar
 * @package Umbrella\CoreBundle\Toolbar
 */
abstract class AbstractToolbar implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    /**
     * @return FormInterface
     */
    public abstract function getForm();

    /**
     * @param QueryBuilder $qb
     * @param array $data
     */
    public abstract function buildQuery(QueryBuilder $qb, array $data);

}