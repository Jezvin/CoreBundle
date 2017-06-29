<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/06/17
 * Time: 22:29
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddAction
 */
class AddAction extends Action
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('icon_class', 'fa fa-plus');
        $resolver->setDefault('class', 'btn btn-primary btn-flat');
    }
}