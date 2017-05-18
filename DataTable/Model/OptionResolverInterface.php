<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 19:03
 */

namespace Umbrella\CoreBundle\DataTable\Model;

/**
 * Interface OptionResolverInterface
 * @package Umbrella\CoreBundle\DataTable\Model
 */
interface OptionResolverInterface
{
    /**
     * @param array $options
     */
    public function resolveOptions(array $options = array());

}