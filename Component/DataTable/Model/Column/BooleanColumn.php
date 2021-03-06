<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 20/05/17
 * Time: 09:45.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model\Column;

/**
 * Class BooleanColumn.
 */
class BooleanColumn extends PropertyColumn
{
    public function defaultRender($entity)
    {
        switch ($this->getPropertyValue($entity)) {
            case true:
                return '<i class="fa fa-check text-green"></i>';

            case false:
                return '<i class="fa fa-ban text-red"></i>';

            default:
                return '';
        }
    }
}
