<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:55.
 */

namespace Umbrella\CoreBundle\Menu\Renderer;

use Umbrella\CoreBundle\Menu\Model\Menu;

/**
 * Interface MenuRendererInterface.
 */
interface MenuRendererInterface
{
    /**
     * @param Menu $menu
     *
     * @return string
     */
    public function render(Menu $menu);
}
