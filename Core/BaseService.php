<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 13:17.
 */

namespace Umbrella\CoreBundle\Core;

use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseService.
 */
class BaseService
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * BaseService constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $container->get('logger');
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    protected function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    /**
     * @param $id
     *
     * @return object
     */
    protected function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @param $id
     * @param array $parameters
     * @param null  $domain
     * @param null  $locale
     *
     * @return string
     */
    protected function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param $persistentObjectName
     * @param null $persistentManagerName
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($persistentObjectName, $persistentManagerName = null)
    {
        return $this->get('doctrine')->getRepository($persistentObjectName, $persistentManagerName);
    }

    /**
     * @param null $name
     *
     * @return EntityManager
     */
    protected function em($name = null)
    {
        return $this->get('doctrine')->getManager($name);
    }

    /**
     * @return \Twig_Environment
     */
    protected function twig()
    {
        return $this->get('twig');
    }

    /**
     * @return string
     */
    protected function srcDir()
    {
        return $this->get('kernel')->getRootDir().'/../src/';
    }

    /**
     * @return string
     */
    protected function webDir()
    {
        return $this->get('kernel')->getRootDir().'/../web/';
    }

    /**
     * @return string
     */
    protected function appDir()
    {
        return $this->get('kernel')->getRootDir().'/';
    }
}
