<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 12:45.
 */

namespace Umbrella\CoreBundle\Core;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Umbrella\CoreBundle\AppProxy\AppMessageBuilder;
use Umbrella\CoreBundle\DataTable\Builder\DataTableBuilder;
use Umbrella\CoreBundle\DataTable\DataTableType;
use Umbrella\CoreBundle\DataTable\Factory\DataTableFactory;
use Umbrella\CoreBundle\DataTable\Model\DataTable;

/**
 * Class BaseController.
 */
class BaseController extends Controller
{
    use ContainerHelperTrait;

    /**
     * @return AppMessageBuilder
     */
    protected function appMessageBuilder()
    {
        return $this->get(AppMessageBuilder::class);
    }

    /**
     * @param $type
     * @param array $options
     *
     * @return DataTable
     */
    public function createTable($type, array $options = array())
    {
        return $this->get(DataTableFactory::class)->create($type, $options);
    }

    /**
     * @param array  $options
     * @param string $type
     *
     * @return DataTableBuilder
     */
    public function createTableBuilder(array $options = array(), $type = DataTableType::class)
    {
        return $this->get(DataTableFactory::class)->createBuilder($type, $options);
    }
}
