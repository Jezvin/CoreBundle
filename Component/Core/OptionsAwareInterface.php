<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 19:03.
 */

namespace Umbrellac\CoreBundle\Component\Core;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface OptionsAwareInterface.
 */
interface OptionsAwareInterface
{
    /**
     * @param array $options
     */
    public function setOptions(array $options = array());

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);
}
