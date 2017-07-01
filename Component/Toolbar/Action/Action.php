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
    protected $container;

    /**
     * @var RouterInterface
     */
    protected $router;

    // options

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $label;

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
     * @var string
     */
    public $translationPrefix;

    /**
     * @var null|array
     */
    public $attributes;

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
        $this->id = $options['id'];

        if (isset($options['action'])) {
            $this->url = UmbrellaRoute::createFromOptions($options['action'])->generateUrl($this->router);
        }

        $this->xhr = ArrayUtils::get($options, 'xhr');
        $this->template = ArrayUtils::get($options, 'template');
        $this->class = ArrayUtils::get($options, 'class');
        $this->iconClass = ArrayUtils::get($options, 'icon_class');
        $this->label = ArrayUtils::get($options, 'label', $this->id);
        $this->translationPrefix = ArrayUtils::get($options, 'translation_prefix');
        $this->attributes = ArrayUtils::get($options, 'attr');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'id',
        ));

        $resolver->setDefined(array(
            'label',
            'action',
            'template',
            'action',
            'xhr',
            'class',
            'icon_class',
            'translation_prefix',
            'attr',
        ));

        $resolver->setAllowedTypes('action', ['array', 'string']);
        $resolver->setAllowedTypes('xhr', 'boolean');
        $resolver->setAllowedTypes('attr', 'array');

        $resolver->setDefault('xhr', true);
        $resolver->setDefault('template', 'UmbrellaCoreBundle:Toolbar\Action:action.html.twig');
        $resolver->setDefault('translation_prefix', 'action.');
        $resolver->setDefault('attr', array());
    }
}