<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 12:45.
 */

namespace Umbrella\CoreBundle\Component\Core;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Umbrellac\CoreBundle\Component\AppProxy\AppMessageBuilder;
use Umbrellac\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrellac\CoreBundle\Component\DataTable\DataTableType;
use Umbrellac\CoreBundle\Component\DataTable\DataTableFactory;
use Umbrellac\CoreBundle\Component\DataTable\Model\DataTable;

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
    protected function createTable($type, array $options = array())
    {
        return $this->get(DataTableFactory::class)->create($type, $options);
    }

    /**
     * @param array  $options
     * @param string $type
     *
     * @return DataTableBuilder
     */
    protected function createTableBuilder(array $options = array(), $type = DataTableType::class)
    {
        return $this->get(DataTableFactory::class)->createBuilder($type, $options);
    }
}
