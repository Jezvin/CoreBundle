<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 29/05/17
 * Time: 19:49
 */

namespace Umbrella\CoreBundle\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UmbrellaFormExtension
 * @package Umbrella\CoreBundle\Extension
 */
class FormExtension extends AbstractTypeExtension
{

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['form_label_class'])) {
            $view->vars['form_label_class'] = $options['form_label_class'];
        }

        if (isset($options['form_group_class'])) {
            $view->vars['form_group_class'] = $options['form_group_class'];
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'form_label_class',
            'form_group_class'
        ));
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}