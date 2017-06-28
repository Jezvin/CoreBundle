<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:59.
 */

namespace Umbrella\CoreBundle\Component\Breadcrumb\Twig;

use Umbrellac\CoreBundle\Component\Breadcrumb\Breadcrumb;

/**
 * Class BreadcrumbTwigExtension.
 */
class BreadcrumbTwigExtension extends \Twig_Extension
{
    /**
     * @var Breadcrumb
     */
    protected $breadcrumb;

    /**
     * BreadcrumbTwigExtension constructor.
     * @param Breadcrumb $breadcrumb
     */
    public function __construct(Breadcrumb $breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('bc_get', [$this, 'get']),
            new \Twig_SimpleFunction('bc_render', [$this, 'render', ['needs_environment' => true]], ['is_safe' => ['html']]),
        );
    }

    /**
     * @return Breadcrumb
     */
    public function get()
    {
        return $this->breadcrumb;
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function render(\Twig_Environment $twig)
    {
        return $twig->render($this->breadcrumb->template, array(
            'breadcrumb' => $this->breadcrumb,
        ));
    }
}
