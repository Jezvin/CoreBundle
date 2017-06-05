<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/06/17
 * Time: 22:43
 */
namespace Umbrella\CoreBundle\Component\Routing;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class EntityRouteCollection
 */
class EntityRouteCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PropertyAccess
     */
    private $accessor;

    /**
     * @var array
     */
    private $routeCollection = array();

    /**
     * EntityRouteCollection constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $id
     * @param $name
     * @param array $params
     */
    public function add($id, $name, array $params = array())
    {
        $this->routeCollection[$id] = array(
            'name' => $name,
            'params' => $params,
            'path_params' => $this->getPathVariables($name)
        );
    }

    /**
     * @param $id
     */
    public function remove($id)
    {
        unset($this->routeCollection[$id]);
    }


    /**
     * Clear collection
     */
    public function clear()
    {
        $this->routeCollection = array();
    }

    /**
     * @param array $actions
     */
    public function set(array $actions)
    {
        foreach ($actions as $id => $value) {

            if (is_array($value)) {
                $this->add(
                    $id,
                    ArrayUtils::get($value, 'route', ''),
                    ArrayUtils::get($value, 'params', array())
                );
            } else {
                $this->add($id, $value);
            }
        }
    }

    /**
     * @param $id
     * @return array|null
     */
    public function get($id)
    {
        return isset($this->routeCollection[$id]) ? $this->routeCollection[$id] : null;
    }

    /**
     * @param $id
     * @param $entity
     * @param int $referenceType
     * @return string
     */
    public function generateUrl($id, $entity, $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $route = $this->get($id);

        if (in_array('id', $route['path_params'])) {
            $params = array_merge(
                $route['params'],
                ['id' => $this->accessor->getValue($entity, 'id')]
            );
        } else {
            $params = $route['params'];
        }

        return $this->router->generate($route['name'], $params, $referenceType);
    }

    /**
     * @param $name
     * @return array
     */
    private function getPathVariables($name)
    {
        $routeCollection = $this->router->getRouteCollection();
        $route = $routeCollection->get($name);

        if ($route === null) {
            throw new \InvalidArgumentException("No route found with name '$route'.");
        }

        return $route->compile()->getPathVariables();
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routeCollection);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->routeCollection);
    }
}