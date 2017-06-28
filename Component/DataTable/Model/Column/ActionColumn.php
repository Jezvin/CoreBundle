<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:10.
 */

namespace Umbrellac\CoreBundle\Component\DataTable\Model\Column;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Umbrellac\CoreBundle\Component\Routing\EntityRouteCollection;
use Umbrellac\CoreBundle\Component\Routing\UmbrellaRoute;
use Umbrellac\CoreBundle\Utils\ArrayUtils;

/**
 * Class ActionColumn.
 */
class ActionColumn extends Column
{
    protected static $TEMPLATE = array(
        'show' => '<a data-xhr-href="__url__" title="See"><i class="fa fa-eye"></i></a>',
        'edit' => '<a data-xhr-href="__url__" title="Edit"><i class="fa fa-pencil"></i></a>',
        'delete' => '<a data-xhr-href="__url__" title="Delete" data-confirm="Confirm delete ?"<i class="fa fa-times text-red"></i></a>',
        '__default' => '<a data-xhr-href="__url__">__action__</a>',
    );

    /**
     * @var array
     */
    private $routeCollection = array();

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ActionColumn constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->router = $container->get('router');
    }

    /**
     * @param $id
     * @param $entity
     *
     * @return string
     */
    public function generateUrl($id, $entity)
    {
        if (!array_key_exists($id, $this->routeCollection)) {
            throw new \InvalidArgumentException("No router with id '$id' registered");
        }
        return $this->routeCollection[$id]->generateEntityUrl($this->router, $entity);
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function defaultRender($entity)
    {
        $html = '';

        /** @var  UmbrellaRoute $route */
        foreach ($this->routeCollection as $id => $route) {
            $template = ArrayUtils::get(self::$TEMPLATE, $id, self::$TEMPLATE['__default']);
            $html .= str_replace(
                array(
                    '__url__',
                    '__action__',
                ),
                array(
                    $route->generateEntityUrl($this->router, $entity),
                    $id,
                ),
                $template).'&nbsp;';
        }
        return $html;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        foreach ($options['actions'] as $id => $value) {
            $this->routeCollection[$id] = UmbrellaRoute::createFromOptions($value);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'actions',
        ));
        $resolver->setAllowedTypes('actions', 'array');

        $resolver->setDefault('actions', array());
        $resolver->setDefault('orderable', false);
        $resolver->setDefault('class', 'disable-row-click');
    }
}
