<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:10
 */

namespace Umbrella\CoreBundle\DataTable\Model\Column;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class ActionColumn
 * @package Umbrella\CoreBundle\DataTable\Model\Column
 */
class ActionColumn extends Column
{

    protected static $TEMPLATE = array(
        'show' => '<a data-xhr-href="__url__" title="See"><i class="fa fa-eye"></i></a>',
        'edit' => '<a data-xhr-href="__url__" title="Edit"><i class="fa fa-pencil t"></i></a>',
        'delete' => '<a data-xhr-href="__url__" title="Delete" data-confirm="Confirm delete ?"<i class="fa fa-times text-red"></i></a>',
        '__default' => '<a data-xhr-href="__url__"><i class="fa fa-question"></i></a>',
    );

    /**
     * @var array
     */
    public $actions;

    /**
     * @var array
     */
    protected $resolvedRoutes = array();

    /**
     * @var PropertyAccess
     */
    protected $accessor;

    /**
     * ActionsColumn constructor.
     * @param $id
     */
    public function __construct($id)
    {
        parent::__construct($id);
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Generate url from route name registered
     *
     * @param $name
     * @param $entity
     * @return string
     */
    public function generateRouteUrl($name, $entity)
    {
        if (!isset($this->resolvedRoutes[$name])) {
            throw new \InvalidArgumentException("No route registered with name '$name'");
        }
        return $this->__generateRouteUrl($this->resolvedRoutes[$name], $entity);
    }

    /**
     * Generate url from resolved Route
     *
     * @param array $resolvedRoute
     * @param $entity
     * @return string
     */
    protected function __generateRouteUrl(array $resolvedRoute, $entity)
    {
        // If route as path variable id => map id with entity id
        if (in_array('id', $resolvedRoute['path_params'])) {
            $params = array_merge(
                $resolvedRoute['params'],
                array(
                    'id' => $this->accessor->getValue($entity, 'id')
                )
            );
        } else {
            $params = $resolvedRoute['params'];
        }
        return $this->container->get('router')->generate($resolvedRoute['route'], $params);
    }

    /**
     * @param $entity
     * @return string
     */
    public function defaultRender($entity)
    {
        $html = '';
        foreach ($this->resolvedRoutes as $name => $resolvedRoute) {
            $url = $this->__generateRouteUrl($resolvedRoute, $entity);
            $template = ArrayUtils::get(self::$TEMPLATE, $name, self::$TEMPLATE['__default']);
            $html .= str_replace('__url__', $url, $template) . '&nbsp;';
        }
        return $html;
    }

    /**
     * Resolve route action
     */
    protected function resolveActions()
    {
        $this->resolvedRoutes = array();

        $router = $this->container->get('router');
        $routeCollection = $router->getRouteCollection();

        foreach ($this->actions as $name => $action) {
            if (is_array($action)) {
                $route = ArrayUtils::get($action, 'route', '');
                $routeParams = ArrayUtils::get($action, 'params', array());
            } else {
                $route = $action;
                $routeParams = array();
            }

            $routeObj = $routeCollection->get($route);

            if ($routeObj === null) {
                throw new \InvalidArgumentException("No route found with name '$route'.");
            }

            $routePathVars = $routeObj->compile()->getPathVariables();

            $this->resolvedRoutes[$name] = array(
                'route' => $route,
                'params' => $routeParams,
                'path_params' => $routePathVars
            );
        }
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        parent::setOptions($options);
        $this->actions = ArrayUtils::get($options, 'actions');

        $this->resolveActions();
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'actions'
        ));
        $resolver->setAllowedTypes('actions', 'array');

        $resolver->setDefault('actions', array());
        $resolver->setDefault('orderable', false);
    }

}