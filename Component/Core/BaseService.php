<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 02/06/17
 * Time: 21:24
 */

namespace Umbrella\CoreBundle\Component\Core;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseService
 */
class BaseService
{

    use ContainerHelperTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * BaseService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}