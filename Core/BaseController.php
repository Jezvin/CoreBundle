<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 12:45
 */
namespace Umbrella\CoreBundle\Core;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Umbrella\CoreBundle\AppProxy\AppProxyService;
use Umbrella\CoreBundle\DataTable\Builder\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\Model\DataTable;

/**
 * Class BaseController
 * @package Umbrella\CoreBundle\Core
 */
class BaseController extends Controller
{

    /* Helpers */

    /**
     * @param $id
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    protected function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param $persistentObjectName
     * @param null $persistentManagerName
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($persistentObjectName, $persistentManagerName = null)
    {
        return $this->getDoctrine()->getRepository($persistentObjectName, $persistentManagerName);
    }

    /**
     * @param null $name
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function em($name = null)
    {
        return $this->getDoctrine()->getManager($name);
    }

    /**
     * @return AppProxyService
     */
    protected function appProxy()
    {
        return $this->get('umbrella.app_proxy_service');
    }

    /**
     * @param $type
     * @param array $options
     * @return DataTable
     */
    public function createTable($type, array $options = array())
    {
        return $this->get('umbrella.datatable_factory')->create($type, $options);
    }

    /**
     * @param array $options
     * @param string $type
     * @return DataTableBuilder
     */
    public function createTableBuilder(array $options = array(), $type = 'Umbrella\CoreBundle\DataTable\DataTableType')
    {
        return $this->get('umbrella.datatable_factory')->createBuilder($type, $options);
    }

}