<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 29/05/17
 * Time: 21:52.
 */
namespace Umbrella\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceTypeExtension.
 */
class ChoiceTypeExtension extends AbstractTypeExtension
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('translate_choice', true);
        $resolver->setAllowedTypes('translate_choice', [ 'bool' ]);

        $resolver->setNormalizer('choice_label', function (Options $options, $value) {
            if ($value === null && $options['translate_choice']) {
                return function ($value, $key, $index) {
                    return empty($key) ? $key : 'form.choice.' . strtolower($key);
                };
            } else {
                return $value;
            }
        });
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return ChoiceType::class;
    }
}
