<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 20:30.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CheckBoxColumn.
 */
class CheckBoxColumn extends Column
{
    /**
     * @param $entity
     *
     * @return string
     */
    public function defaultRender($entity)
    {
        return '<input type="checkbox">';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('orderable', false);
        $resolver->setDefault('class', 'text-center disable-row-click propagate-cell-click');
    }
}
