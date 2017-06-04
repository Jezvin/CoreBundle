<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 04/06/17
 * Time: 21:52
 */

namespace Umbrella\CoreBundle\Component\Menu;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;

/**
 * Class MenuAuthorizationChecker
 */
class MenuAuthorizationChecker
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $checker;

    /**
     * @var \SplObjectStorage
     */
    protected $cache;

    /**
     * MenuAuthorizationChecker constructor.
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(AuthorizationCheckerInterface $checker)
    {
        $this->checker = $checker;
        $this->cache = new \SplObjectStorage();
    }

    /**
     * @param MenuNode $node
     * @return bool
     */
    public function isGranted(MenuNode $node)
    {

        if ($this->cache->contains($node)) {
            return $this->cache[$node];
        }

        // no roles assigned and no children : granted
        if (empty($node->roles) && !$node->hasChildren()) {
            $this->cache[$node] = true;
            return true;
        }

        // if one role assigned is granted : granted
        foreach ($node->roles as $role) {
            if ($this->checker->isGranted($role)) {
                $this->cache[$node] = true;
                return true;
            }
        }

        // role assigned but none was granted : not granted
        if (!empty($node->roles)) {
            $this->cache[$node] = false;
            return false;
        }


        // if one child is granted : granted
        foreach ($node as $child) {
            if ($this->isGranted($child)) {
                $this->cache[$node] = true;
                return true;
            }
        }

        // not granted
        $this->cache[$node] = false;
        return false;
    }
}