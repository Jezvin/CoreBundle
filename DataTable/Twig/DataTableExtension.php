<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46
 */

namespace Umbrella\CoreBundle\DataTable\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrella\CoreBundle\DataTable\DataTable;

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
        $options['id'] = $dataTable->getId();
        $options['columns'] = $dataTable->getColumns();
        $options['class'] = $dataTable->getClass();

        return $twig->render($dataTable->getTemplate(), $options);
    }

    /**
     * @param \Twig_Environment $twig
     * @param DataTable $dataTable
     * @return string
     */
    public function renderJs(\Twig_Environment $twig, DataTable $dataTable)
    {
        $js_options = array();
        $js_options['serverSide'] = true;
        $js_options['ajax'] = array(
            'url' => $dataTable->getAjaxUrl(),
            'type' => $dataTable->getAjaxType()
        );

        $options = array();
        $options['datatable'] = $dataTable;
        $options['id'] = $dataTable->getId();
        $options['js'] = $js_options;

        return $twig->render("UmbrellaCoreBundle:DataTable:datatable_js.html.twig", $options);
    }
    
}