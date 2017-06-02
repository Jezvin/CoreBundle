<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 02/06/17
 * Time: 21:08
 */

namespace Umbrella\CoreBundle\Core;

use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait ContainerHelperTrait
 *
 * @property ContainerInterface $container
 */
trait ContainerHelperTrait
{

    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    protected function getParameter($name, $default = null)
    {
        return $this->container->hasParameter($name)
            ? $this->container->getParameter($name)
            : $default;
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
        return $this->container->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param $persistentObjectName
     * @param null $persistentManagerName
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($persistentObjectName, $persistentManagerName = null)
    {
        return $this->container->get('doctrine')->getRepository($persistentObjectName, $persistentManagerName);
    }

    /**
     * @param null $name
     *
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function em($name = null)
    {
        return $this->container->get('doctrine')->getManager($name);
    }

    /**
     * @return Logger
     */
    protected function logger()
    {
        return $this->container->get('logger');
    }

    /**
     * @return string
     */
    protected function rootDir()
    {
        return $this->container->get('kernel')->getRootDir();
    }

    /**
     * @return string
     */
    protected function srcDir()
    {
        return $this->rootDir() . '/../src/';
    }

    /**
     * @return string
     */
    protected function webDir()
    {
        return $this->rootDir() . '/../web/';
    }

}