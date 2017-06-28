<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 04/06/17
 * Time: 21:48
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\HttpFoundation\RequestStack;
use Umbrellac\CoreBundle\Component\Menu\Model\MenuNode;

/**
 * Class MenuMatcher
 */
class MenuRouteMatcher
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var \SplObjectStorage
     */
    private $cache;

    /**
     * MenuRouteMatcher constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->cache = new \SplObjectStorage();
    }

    /**
     * @param MenuNode $node
     * @return bool
     */
    public function isCurrent(MenuNode $node)
    {
        if ($this->cache->contains($node)) {
            return $this->cache[$node];
        }

        $match = $this->isMatchingRoute($node->route, $node->routeParams);
        $this->cache[$node] = $match;
        return $match;
    }

    /**
     * @param MenuNode $node
     * @return bool
     */
    public function isCurrentOrHasChildCurrent(MenuNode $node)
    {
        if ($this->isCurrent($node)) {
            return true;
        }

        /** @var MenuNode $child */
        foreach($node as $child) {
            if ($this->isCurrentOrHasChildCurrent($child)) {
                return true;
            }
        }
        return false;
    }

    /**
     *
     * @param $testRoute
     * @param array $testRouteParams
     * @return bool
     */
    private function isMatchingRoute($testRoute, array $testRouteParams = array())
    {
        $request = $this->requestStack->getMasterRequest();
        $route = $request->attributes->get('_route');
        if ($testRoute !== $route) {
            return false;
        }

        foreach ($testRouteParams as $key => $value) {
            if ($request->get($key) != $value) {
                return false;
            }
        }

        return true;
    }
}