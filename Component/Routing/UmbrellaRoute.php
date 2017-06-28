<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 24/06/17
 * Time: 17:21
 */

namespace Umbrellac\CoreBundle\Component\Routing;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class UmbrellaRoute
 */
class UmbrellaRoute
{
    /**
     * @var
     */
    public $route;

    /**
     * @var array
     */
    public $params = array();

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * UmbrellaRoute constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * @param $options
     * @return UmbrellaRoute
     */
    public static function createFromOptions($options)
    {
        $route = new UmbrellaRoute();

        if (is_array($options)) {
            // options = [
            //  'route' => "route name",
            //  "params" => [ ...]
            // ]

            if (!isset($options['route'])) {
                throw new \InvalidArgumentException("Enable to create UmbrellaRoute, missing 'route' key");
            }

            $route->route = $options['route'];
            if (isset($options['params']) && is_array($options['params'])) {
                $route->params = $options['params'];
            }

        } else {
            // $options = "route name"
            $route->route = $options;
        }

        return $route;
    }

    /**
     * @param RouterInterface $router
     * @param array $params
     * @param int $referenceType
     * @return string
     */
    public function generateUrl(RouterInterface $router, array $params = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $params = array_merge($this->params, $params);
        return $router->generate($this->route, $params, $referenceType);
    }

    /**
     * @param RouterInterface $router
     * @param $entity
     * @param array $params
     * @param int $referenceType
     * @return string
     */
    public function generateEntityUrl(RouterInterface $router, $entity, array $params = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $params = array_merge($this->params, $params);
        if ($entity !== null) {
            $params = array_merge($params, ['id' => $this->accessor->getValue($entity, 'id')] );
        }

        return $router->generate($this->route, $params, $referenceType);
    }
}