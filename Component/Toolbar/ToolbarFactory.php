<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 23/05/17
 * Time: 20:03.
 */

namespace Umbrella\CoreBundle\Component\Toolbar;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\Action\ActionsBuilder;

/**
 * Class ToolbarFactory.
 */
class ToolbarFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DataTableFactory constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $class
     * @param array $options
     *
     * @return Toolbar
     */
    public function create($class, array $options = array())
    {
        if (!is_subclass_of($class, Toolbar::class)) {
            throw new \InvalidArgumentException("Class '$class' must extends Toolbar class.");
        }

        /** @var Toolbar $toolbar */
        $toolbar = new $class($this->container);
        $resolver = new OptionsResolver();
        $toolbar->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);
        $toolbar->setOptions($resolvedOptions);

        // build Actions
        $actionsBuilder = new ActionsBuilder($this->container);
        $toolbar->buildActions($actionsBuilder, $resolvedOptions);
        $toolbar->actions = $actionsBuilder->getActions();

        // build form
        $formFactory = $this->container->get('form.factory');
        $formBuilder = $formFactory->createBuilder(FormType::class, null, $resolvedOptions['form_options']);
        $toolbar->buildForm($formBuilder, $resolvedOptions);
        $toolbar->form = $formBuilder->getForm();

        return $toolbar;
    }
}
