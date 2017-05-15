<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:10
 */

namespace Umbrella\CoreBundle\DataTable;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class ActionsColumn
 * @package Umbrella\CoreBundle\DataTable
 */
class ActionsColumn extends EntityColumn
{
    /**
     * @var array
     */
    protected $actions = array();

    /**
     * ActionsColumn constructor.
     * @param $id
     * @param array $options
     */
    public function __construct($id, array $options)
    {
        parent::__construct($id, $options);
        $this->sortable = ArrayUtils::get($options, 'sortable', false);
    }
}