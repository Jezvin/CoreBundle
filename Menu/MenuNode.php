<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:05
 */
namespace Umbrella\CoreBundle\Menu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class MenuNode
 * @package Umbrella\Corebundle\Menu
 */
class MenuNode
{
    const TYPE_ROOT = 'ROOT';
    const TYPE_HEADER = 'HEADER';
    const TYPE_PAGE = 'PAGE';

    const DFT_ICON_CLASS = 'fa fa-circle-o';
    const DFT_URL = '#';
    const DFT_TARGET = '_self';

    /**
     * @var string
     */
    public $type;

    /**
     * @var MenuNode
     */
    public $parent;

    /**
     * @var array
     */
    public $children = array();

    /**
     * @var string
     */
    public $iconClass = self::DFT_ICON_CLASS;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $url = self::DFT_URL;

    /**
     * @var string
     */
    public $target = self::DFT_TARGET;

    /**
     * @var array
     */
    public $roles = array();

    /* Keep route and routeParams options for url matcher */
    
    /**
     * @var string
     */
    public $route;

    /**
     * @var array
     */
    public $routeParams = array();

    /* Cache */

    /**
     * @var null
     */
    private $isCurrent = null;

    /**
     * @var null
     */
    private $isGranted = null;

    /**
     * @return int|mixed
     */
    public function getLevel()
    {
        return $this->parent ? $this->parent->getLevel() + 1 : 0;
    }

    /**
     * @param MenuNode $child
     * @return $this
     */
    public function addChild(MenuNode $child)
    {
        $child->parent = $this;
        $this->children[] = $child;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->children);
    }

    /* Helper : Is granted */

    /**
     * @param AuthorizationCheckerInterface $securityChecker
     * @return bool
     */
    public function isGranted(AuthorizationCheckerInterface $securityChecker)
    {
        if ($this->isGranted == null) {
            $this->isGranted = false;

            if (!empty($this->roles)) { // roles assigned : display if granted
                foreach ($this->roles as $role) {
                    if ($securityChecker->isGranted($role)) {
                        $this->isGranted = true;
                        break;
                    }
                }
            } elseif ($this->hasChildren()) { // no roles assigned and has children : granted if one children granted
                foreach ($this->children as $child) {
                    if ($child->isGranted($securityChecker)) {
                        $this->isGranted = true;
                        break;
                    }
                }
            } else { // no roles assigned and no children : always granted
                $this->isGranted = true;
            }
        }
        return $this->isGranted;

    }

    /* Helper : Current node */

    /**
     * @param Request $request
     * @return bool
     */
    public function isCurrent(Request $request)
    {
        if ($this->isCurrent == null) {
            $this->isCurrent = true;

            $route = $request->get('_route');
            if (empty($route) || $route !== $this->route) {
                $this->isCurrent = false;
            } else {
                foreach ($this->routeParams as $key => $value) {
                    if ($request->get($key) != $value) {
                        $this->isCurrent = false;
                        break;
                    }
                }
            }
        }
        return $this->isCurrent;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function hasCurrentChild(Request $request)
    {
        if ($this->hasChildren()) {
            foreach ($this->children as $child) {
                $childActive = $child->hasCurrentChild($request);
                if ($childActive) {
                    return true;
                }
            }
        } else {
            return $this->isCurrent($request);
        }
    }

    /**
     * @param Request $request
     * @return null|MenuNode
     */
    public function findCurrent(Request $request)
    {
        if ($this->hasChildren()) {
            foreach ($this->children as $child) {
                $current = $child->findCurrent($request);
                if ($current !== null) {
                    return $current;
                }
            }
        } else {
            return $this->isCurrent($request) ? $this : null;
        }
    }
}