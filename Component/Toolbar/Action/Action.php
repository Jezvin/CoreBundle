<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/06/17
 * Time: 22:07
 */
namespace Umbrella\CoreBundle\Component\Toolbar\Action;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Component\Core\OptionsAwareInterface;
use Umbrella\CoreBundle\Component\Routing\UmbrellaRoute;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Action
 */
class Action implements OptionsAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RouterInterface
     */
    private $router;

    // options

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $xhr;

    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $iconClass;

    /**
     * Action constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->router = $container->get('router');
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->url = UmbrellaRoute::createFromOptions($options['action'])->generateUrl($this->router);
        $this->xhr = ArrayUtils::get($options, 'xhr');
        $this->template = ArrayUtils::get($options, 'template');
        $this->class = ArrayUtils::get($options, 'class');
        $this->iconClass = ArrayUtils::get($options, 'icon_class');
        $this->title = ArrayUtils::get($options, 'title');

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'action',
            'title',
        ));

        $resolver->setDefined(array(
            'template',
            'action',
            'xhr',
            'class',
            'icon_class',
            'title'
        ));

        $resolver->setAllowedTypes('action', ['array', 'string']);
        $resolver->setAllowedTypes('xhr', 'boolean');

        $resolver->setDefault('xhr', true);
        $resolver->setDefault('template', 'UmbrellaCoreBundle:Toolbar:action.html.twig');
    }
}