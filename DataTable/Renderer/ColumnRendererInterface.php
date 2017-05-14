<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:53
 */

namespace Umbrella\CoreBundle\DataTable\Renderer;

use Umbrella\CoreBundle\DataTable\Column;

/**
 * Interface ColumnRendererInterface
 * @package Umbrella\AdminBundle\Renderer
 */
interface ColumnRendererInterface
{
    /**
     * @param Column $column
     * @param array $options
     * @return string
     */
    public function render(Column $column, array $options = array());

}