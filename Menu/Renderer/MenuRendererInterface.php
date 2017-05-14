<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:55
 */

namespace Umbrella\CoreBundle\Menu\Renderer;

use Umbrella\CoreBundle\Menu\MenuNode;

/**
 * Interface MenuRendererInterface
 * @package Umbrella\CoreBundle\Menu\Renderer
 */
interface MenuRendererInterface
{
    /**
     * @param MenuNode $node
     * @return string
     */
    public function render(MenuNode $node);
}