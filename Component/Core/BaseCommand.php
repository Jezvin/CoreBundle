<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 13:35.
 */

namespace Umbrellac\CoreBundle\Component\Core;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Class BaseCommand.
 */
class BaseCommand extends ContainerAwareCommand
{

    /**
     * @param null $name
     *
     * @return EntityManager
     */
    protected function em($name = null)
    {
        return $this->getContainer()->get('doctrine')->getManager($name);
    }

    /**
     * @param $persistentObjectName
     * @param null $persistentManagerName
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($persistentObjectName, $persistentManagerName = null)
    {
        return $this->getContainer()->get('doctrine')->getRepository($persistentObjectName, $persistentManagerName);
    }
}
