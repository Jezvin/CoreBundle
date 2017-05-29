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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class FormTypeExtension
 * @package Umbrella\CoreBundle\Extension
 */
class FormTypeExtension extends AbstractTypeExtension
{

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $this->setView($view, $form, 'label_class', 'col-sm-2');
        $this->setView($view, $form, 'group_class', 'col-sm-10');

        $label = empty($view->vars['label']) ? $view->vars['name'] : $view->vars['label'];
        $view->vars['label'] = $options['label_prefix'] . $label;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setAttribute($builder, $options, 'label_class');
        $this->setAttribute($builder, $options, 'group_class');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'label_class',
            'group_class'
        ));

        $resolver->setDefault('label_prefix', 'form.label.');
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

    /* Helper */

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @param $optionName
     */
    protected function setAttribute(FormBuilderInterface $builder, array $options, $optionName)
    {
        if (isset($options[$optionName])) {
            $builder->setAttribute($optionName, $options[$optionName]);
        }
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param $attributeName
     * @param $defaultValue
     */
    protected function setView(FormView $view, FormInterface $form, $attributeName, $defaultValue)
    {
        if ($form->getConfig()->hasAttribute($attributeName)) { // if attribute is defined -> set it to view
            $view->vars[$attributeName] = $form->getConfig()->getAttribute($attributeName);

        } elseif ($form->getRoot()->getConfig()->hasAttribute($attributeName)) { // else if root has attribute defined -> set it to view
            $view->vars[$attributeName] = $form->getRoot()->getConfig()->getAttribute($attributeName);

        } else { // else set default value to view
            $view->vars[$attributeName] = $defaultValue;
        }
    }
}