<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 04/07/17
 * Time: 22:28
 */

namespace Umbrella\CoreBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TagType
 */
class TagType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'class' => 'js-umbrella-tag'
            )
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return TextType::class;
    }

}