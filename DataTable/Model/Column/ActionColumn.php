<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 19:10
 */

namespace Umbrella\CoreBundle\DataTable\Model\Column;

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
     */
    public function __construct($id)
    {
        parent::__construct($id);
    }

    /**
     * @param $result
     * @return string
     */
    public function render($result)
    {
        return 'TODO';
    }
}