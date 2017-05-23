<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 23:17
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
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Toolbar
 * @package Umbrella\CoreBundle\Toolbar
 */
abstract class AbstractToolbar implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    /**
     * @var $options
     */
    protected $options;

    /**
     * AbstractToolbar constructor.
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * @param FormFactory $factory
     * @return FormInterface
     */
    public abstract function getForm(FormFactory $factory);

    /**
     *
     * @param QueryBuilder $qb
     * @param array $data
     * @return QueryBuilder
     */
    public abstract function buildQuery(QueryBuilder $qb, array $data);


    /**
     * @param Request $request
     * @param QueryBuilder $qb
     */
    public function handleRequest(Request $request, QueryBuilder $qb)
    {
        $form = $this->getForm($this->container->get('form.factory'));
        $form->handleRequest($request);

        $data = $form->getData();
        $this->buildQuery($qb, ArrayUtils::to_array($data));
    }

    /**
     * @return FormView
     */
    public function getFormView()
    {
        return $this->getForm($this->container->get('form.factory'))->createView();
    }

    /* Helper */

    /**
     * @param FormFactory $factory
     * @return FormBuilderInterface
     */
    protected function createFormBuilder(FormFactory $factory)
    {
        return $factory->createBuilder(FormType::class, null, array(
            'validation_groups' => false
        ));
    }

}