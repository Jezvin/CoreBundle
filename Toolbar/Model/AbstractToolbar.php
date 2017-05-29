<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 23:17.
 */

namespace Umbrella\CoreBundle\Toolbar\Model;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Toolbar.
 */
abstract class AbstractToolbar implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var
     */
    protected $options;

    /**
     * AbstractToolbar constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * @param FormFactory $factory
     *
     * @return FormInterface
     */
    abstract public function createForm(FormFactory $factory);

    /**
     * @param QueryBuilder $qb
     * @param array        $data
     *
     * @return QueryBuilder
     */
    abstract public function buildQuery(QueryBuilder $qb, array $data);

    /**
     * @return FormInterface
     */
    final public function getForm()
    {
        return $this->createForm($this->container->get('form.factory'));
    }

    /**
     * @return FormView
     */
    final public function getFormView()
    {
        return $this->getForm()->createView();
    }

    /**
     * @param QueryBuilder $qb
     * @param Request      $request
     */
    public function handleRequest(QueryBuilder $qb, Request $request)
    {
        $form = $this->getForm();
        $form->handleRequest($request);
        $this->buildQuery($qb, $form->getData());
    }

    /* Helper */

    /**
     * @param FormFactory $factory
     *
     * @return FormBuilderInterface
     */
    protected function createFormBuilder(FormFactory $factory)
    {
        return $factory->createNamedBuilder('toolbar', FormType::class, null, array(
            'validation_groups' => false,
            'label_class' => 'hidden',
            'group_class' => 'col-sm-12',
        ));
    }
}
