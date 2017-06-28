<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/05/17
 * Time: 23:58.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Renderer;

use Umbrella\CoreBundle\Component\DataTable\Model\Column\Column;

/**
 * Interface ColumnRendererInterface.
 */
interface ColumnRendererInterface
{
    /**
     * @param Column $column
     * @param $entity
     *
     * @return string
     */
    public function render(Column $column, $entity);
}
