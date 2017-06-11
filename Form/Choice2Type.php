<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 18:51.
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Choice2Type.
 */
class Choice2Type extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var bool
     */
    private $templated = false;

    /**
     * Choice2Type constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $placeholder = $view->vars['placeholder'];

        $jsonOptions = array();

        // select2 placeholder
        $jsonOptions['placeholder'] = empty($placeholder) ? $placeholder : $this->translator->trans($placeholder);

        // select 2 allowClear
        if ($view->vars['required'] !== true) {
            $jsonOptions['allowClear'] = true;
        }

        // select 2 template
        if (!$this->templated && $options['template'] !== null) {
            /** @var ChoiceView $choice */
            foreach ($view->vars['choices'] as $idx => $choice) {
                $template = (string)call_user_func($options['template'], $choice->value, $choice->label, $idx);
                $choice->attr['data-template'] = htmlspecialchars($template);
            }
            $this->templated = true;
            $jsonOptions['template'] = true;
        }

        $view->vars['attr']['data-options'] = htmlspecialchars(json_encode($jsonOptions));

        // avoid use some values
        $view->vars['placeholder'] = $placeholder === null ? null : '';
        $view->vars['expanded'] = false;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'class' => 'js-select2',
                'style' => 'width: 100%',
            ),
        ));

        $resolver->setDefault('template', null);
        $resolver->setAllowedTypes('template', array('callable', 'null'));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
