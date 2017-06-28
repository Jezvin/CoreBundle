<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/05/17
 * Time: 23:42.
 */

namespace Umbrellac\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatepickerType.
 */
class DatepickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'dd/mm/yyyy',
            'attr' => array(
                'class' => 'js-datepicker',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DateType::class;
    }
}
