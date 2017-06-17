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
use Symfony\Component\Routing\RouterInterface;
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
    protected $templated = false;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * Choice2Type constructor.
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
        $this->router = $router;
    }

    /**
     * @inheritdoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-options'] = htmlspecialchars(json_encode($this->buildJsOptions($view, $form, $options)));

        // avoid use some values
        $view->vars['placeholder'] = $view->vars['placeholder'] === null ? null : '';
        $view->vars['expanded'] = false;
    }

    protected function buildJsOptions(FormView $view, FormInterface $form, array $options)
    {
        $jsOptions = array();
        $jsSelect2Options = array();

        $jsSelect2Options['placeholder'] = empty($options['placeholder']) ? $options['placeholder'] : $this->translator->trans($options['placeholder']);
        $jsSelect2Options['allowClear'] = $view->vars['required'] !== true; // allow clear if not required
        $jsSelect2Options['minimumInputLength'] = $options['min_search_length'];

        if (isset($options['template']) && !empty($options['template'])) {
            $jsOptions['template'] = $options['template'];
        }

        if (isset($options['ajax_load_route']) && !empty($options['ajax_load_route'])) {
            $jsOptions['ajax_url'] = $this->router->generate($options['ajax_load_route']);
        }

        $jsOptions['select2'] = $jsSelect2Options;

        return $jsOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'ajax_load_route',
            'min_search_length',
            'template'
        ));

        $resolver->setDefaults(array(
            'min_search_length' => 0,
            'attr' => array(
                'class' => 'js-select2',
                'style' => 'width: 100%',
            )
        ));

        $resolver->setAllowedTypes('min_search_length', 'int');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
