<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 23:17.
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrellac\CoreBundle\Component\Core\OptionsAwareInterface;
use Umbrellac\CoreBundle\Component\Toolbar\Action\ActionsBuilder;
use Umbrellac\CoreBundle\Utils\ArrayUtils;

/**
 * Class Toolbar.
 */
class Toolbar implements OptionsAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var null|array
     */
    protected $formOptions;

    // options

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $class;

    // Model

    /**
     * @var FormInterface|null
     */
    public $form;

    /**
     * @var array
     */
    public $actions;

    /**
     * Toolbar constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ActionsBuilder $builder
     * @param array $options
     */
    public function buildActions(ActionsBuilder $builder, array $options)
    {
    }

    /**
     * @param FormBuilder $builder
     * @param array $options
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
    }

    /**
     * @param QueryBuilder $qb
     * @param $data
     */
    public function filter(QueryBuilder $qb, $data)
    {

    }

    /**
     * @param QueryBuilder $qb
     * @param Request      $request
     */
    final public function handleRequest(QueryBuilder $qb, Request $request)
    {
        if ($this->form) {
            $this->form->handleRequest($request);
            $this->filter($qb, $this->form->getData());
        }
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->formOptions = ArrayUtils::get($options, 'form_options');
        $this->template = ArrayUtils::get($options, 'template');
        $this->class = ArrayUtils::get($options, 'class');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array(
            'form_options',
            'template',
            'class'
        ));

        $resolver->setAllowedTypes('form_options', 'array');

        $resolver->setDefault('form_options', array(
            'validation_groups' => false,
            'label_class' => 'hidden',
            'group_class' => 'col-sm-12',
        ));
        $resolver->setDefault('template', 'UmbrellacCoreBundle:Toolbar:toolbar.html.twig');
    }

    /* Helper */

    /**
     * @return FormBuilderInterface
     */
    final protected function createFormBuilder()
    {
        return $this->formFactory->createNamedBuilder('toolbar', FormType::class, null, $this->formOptions);
    }
}
