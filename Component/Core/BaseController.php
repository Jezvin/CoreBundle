<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 12:45.
 */

namespace Umbrella\CoreBundle\Component\Core;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Umbrella\CoreBundle\Component\AppProxy\AppMessageBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableType;
use Umbrella\CoreBundle\Component\DataTable\DataTableFactory;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;

/**
 * Class BaseController.
 */
class BaseController extends Controller
{
    use ContainerHelperTrait;

    const TOAST_KEY = 'TOAST';
    const TOAST_INFO = 'info';
    const TOAST_SUCCESS = 'success';
    const TOAST_WARNING = 'warning';
    const TOAST_ERROR = 'error';

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
     * @param array $options
     * @param string $type
     *
     * @return DataTableBuilder
     */
    protected function createTableBuilder(array $options = array(), $type = DataTableType::class)
    {
        return $this->get(DataTableFactory::class)->createBuilder($type, $options);
    }

    /**
     * @param $id
     * @param array $params
     */
    protected function toastInfo($id, array $params = array())
    {
        $this->toast(self::TOAST_INFO, $id, $params);
    }

    /**
     * @param $id
     * @param array $params
     */
    protected function toastSuccess($id, array $params = array())
    {
        $this->toast(self::TOAST_SUCCESS, $id, $params);
    }

    /**
     * @param $id
     * @param array $params
     */
    protected function toastWarning($id, array $params = array())
    {
        $this->toast(self::TOAST_WARNING, $id, $params);
    }

    /**
     * @param $id
     * @param array $params
     */
    protected function toastError($id, array $params = array())
    {
        $this->toast(self::TOAST_ERROR, $id, $params);
    }

    /**
     * @param $type
     * @param $id
     * @param $params
     */
    protected function toast($type, $id, array $params = array())
    {
        $toasts = $this->get('session')->getFlashBag()->get(self::TOAST_KEY);
        $toasts[] = array(
            'type' => $type,
            'message' => $this->trans($id, $params)
        );
        $this->get('session')->getFlashBag()->set(self::TOAST_KEY, $toasts);
    }
}
