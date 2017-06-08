<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Twig;

use Symfony\Component\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\DataTable\Model\Column\Column;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;

/**
 * Class DataTableTwigExtension.
 */
class DataTableTwigExtension extends \Twig_Extension
{

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * DataTableTwigExtension constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_datatable', array($this, 'render'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
            new \Twig_SimpleFunction('datatable_js', array($this, 'renderJs'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param DataTable         $dataTable
     *
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
     * @param DataTable         $dataTable
     *
     * @return string
     */
    public function renderJs(\Twig_Environment $twig, DataTable $dataTable)
    {
        $options = array();
        $options['datatable'] = $dataTable;
        $options['id'] = $dataTable->id;
        $options['js'] = $this->buildJsOptions($dataTable);

        return $twig->render('UmbrellaCoreBundle:DataTable:datatable_js.html.twig', $options);
    }

    /**
     * @param DataTable $dataTable
     *
     * @return array
     */
    protected function buildJsOptions(DataTable $dataTable)
    {
        $options = array();
        $options['serverSide'] = true;
        $options['bFilter'] = false;
        $options['ajax'] = array(
            'url' => $dataTable->loadUrl,
            'type' => $dataTable->loadType,
        );
        $options['lengthChange'] = $dataTable->lengthChange;
        $options['pageLength'] = $dataTable->pageLength;
        $options['lengthMenu'] = $dataTable->lengthMenu;
        $options['fixedHeader'] = $dataTable->fixedHeader;

        if ($dataTable->sortable) {
            $options['rowReorder'] = array(
                'update' => false,
                'url' => $dataTable->sequenceUrl,
                'type' => $dataTable->sequenceType,
            );
        }

        $order = array();

        // columns options
        $columnsOptions = array();

        /** @var Column $column */
        foreach ($dataTable->columns as $idx => $column) {
            if ($column->order) {
                $order[] = array($idx, strtolower($column->order));
            }

            $columnsOption = array(
                'orderable' => $column->orderable,
                'className' => $column->class
            );
            $columnsOptions[] = $columnsOption;
        }

        $options['columns'] = $columnsOptions;

        // default column order
        $options['order'] = $order;

        // translations
        $options['language'] = $this->buildTranslationOptions();

        return $options;
    }

    /**
     * @return array
     */
    protected function buildTranslationOptions()
    {
        return array(
            'processing' => $this->transDt('processing'),
            'search' => $this->transDt('search'),
            'lengthMenu' => $this->transDt('lengthMenu'),
            'info' => $this->transDt('info'),
            'infoEmpty' => $this->transDt('infoEmpty'),
            'infoFiltered' => $this->transDt('infoFiltered'),
            'infoPostFix' => $this->transDt('infoPostFix'),
            'loadingRecords' => $this->transDt('loadingRecords'),
            'zeroRecords' => $this->transDt('zeroRecords'),
            'emptyTable' => $this->transDt('emptyTable'),
            'searchPlaceholder' => $this->transDt('searchPlaceholder'),
            'paginate' => array(
                'first' => $this->transDt('paginate.first'),
                'previous' => $this->transDt('paginate.previous'),
                'next' => $this->transDt('paginate.next'),
                'last' => $this->transDt('paginate.last'),
            ),
            'aria' => array(
                'sortAscending' => $this->transDt('aria.sortAscending'),
                'sortDescending' => $this->transDt('aria.sortDescending'),
            ), );
    }

    /**
     * @param $key
     *
     * @return string
     */
    protected function transDt($key)
    {
        return $this->translator->trans('datatable.'.$key, [], 'datatable');
    }
}
