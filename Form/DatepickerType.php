<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/05/17
 * Time: 23:42
 */
namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatepickerType
 * @package Umbrella\CoreBundle\Form
 */
class DatepickerType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'class' => 'js-datepicker'
            )
        ));
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return TextType::class;
    }
}