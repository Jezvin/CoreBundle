<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 29/05/17
 * Time: 21:52.
 */
namespace Umbrella\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceTypeExtension.
 */
class ChoiceTypeExtension extends AbstractTypeExtension
{
    /**
     * @var bool
     */
    private $prefixed = false;

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!$this->prefixed && !empty($options['choice_prefix'])) {
            /** @var ChoiceView $choice */
            foreach ($view->vars['choices'] as $choice) {
                $choice->label = $options['choice_prefix'] . strtolower($choice->label);
            }
            $this->prefixed = true;
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('choice_prefix', 'form.choice.');
        $resolver->setAllowedTypes('choice_prefix', array('string', 'null'));
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
