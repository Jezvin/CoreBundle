<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 18:51.
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
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

        // select 2 attributes
        $select2Attributes = array();
        $select2Attributes['placeholder'] = empty($placeholder) ? $placeholder : $this->translator->trans($placeholder);

        if ($view->vars['required'] !== true) {
            $select2Attributes['allowClear'] = true;
        }

        $view->vars['attr']['data-select2-options'] = htmlspecialchars(json_encode($select2Attributes));

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
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
