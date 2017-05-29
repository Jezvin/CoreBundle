<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 13:47.
 */

namespace Umbrella\CoreBundle\Utils;

use Doctrine\ORM\EntityManager;

/**
 * Class SQLUtils.
 */
class SQLUtils
{
    /**
     * @param EntityManager $em
     */
    public static function disableSQLLog(EntityManager $em)
    {
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
    }
}
