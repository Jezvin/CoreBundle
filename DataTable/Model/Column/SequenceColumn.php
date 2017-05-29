<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 15:15.
 */

namespace Umbrella\CoreBundle\DataTable\Model\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SequenceColumn.
 */
class SequenceColumn extends PropertyColumn
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('order', 'ASC');
        $resolver->setDefault('class', 'sequence-column');
    }
}
