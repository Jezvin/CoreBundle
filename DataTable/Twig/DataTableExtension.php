<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46
 */

namespace Umbrella\CoreBundle\DataTable\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\DataTable\Model\Column\Column;
use Umbrella\CoreBundle\DataTable\Model\DataTable;

/**
 * Class DataTableExtension
 * @package Umbrella\CoreBundle\DataTable\Twig
 */
class DataTableExtension extends \Twig_Extension
{
    
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DataTableExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction("render_datatable", array($this, "render"), array(
                'is_safe' => array("html"),
                'needs_environment' => true
            )),
            new \Twig_SimpleFunction("datatable_js", array($this, "renderJs"), array(
                'is_safe' => array("html"),
                'needs_environment' => true
            )),
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param DataTable $dataTable
     * @return string
     */
    public function render(\Twig_Environment $twig, DataTable $dataTable)
    {
        $options = array();
        $options['datatable'] = $dataTable;
        $options['id'] = $dataTable->id;
        $options['columns'] = $dataTable->columns;
        $options['class'] = $dataTable->class;

        return $twig->render($dataTable->template, $options);
    }

    /**
     * @param \Twig_Environment $twig
     * @param DataTable $dataTable
     * @return string
     */
    public function renderJs(\Twig_Environment $twig, DataTable $dataTable)
    {
        $options = array();
        $options['datatable'] = $dataTable;
        $options['id'] = $dataTable->id;
        $options['js'] = $this->buildJsOptions($dataTable);

        return $twig->render("UmbrellaCoreBundle:DataTable:datatable_js.html.twig", $options);
    }

    /**
     * @param DataTable $dataTable
     * @return array
     */
    protected function buildJsOptions(DataTable $dataTable)
    {
        $options = array();
        $options['serverSide'] = true;
        $options['bFilter'] = false;
        $options['ajax'] = array(
            'url' => $dataTable->ajaxUrl,
            'type' => $dataTable->ajaxType
        );
        $options['lengthChange'] = $dataTable->lengthChange;
        $options['pageLength'] = $dataTable->pageLength;
        $options['lengthMenu'] = $dataTable->lengthMenu;
        $options['fixedHeader'] = $dataTable->fixedHeader;
        $options['rowReorder'] = $dataTable->sortable;

        $columnDefs = array();

        $order = array();
        $noSort = array();

        /** @var Column $column */
        foreach ($dataTable->columns as $idx => $column) {
            if ($column->order) {
                $order[] = array($idx, strtolower($column->order));
            }

            if (!$column->orderable) {
                $noSort[] = $idx;
            }
        }

        // order option
        if (!empty($order)) {
            $options['order'] = $order;
        }

        // no order option
        if (!empty($noSort)) {
            $columnDefs[] = array('targets' => $noSort, 'orderable' => false);
        }
        $options['columnDefs'] = $columnDefs;

        return $options;
    }
    
}