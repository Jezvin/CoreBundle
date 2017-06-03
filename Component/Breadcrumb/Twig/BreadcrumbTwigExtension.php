<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:59.
 */

namespace Umbrella\CoreBundle\Component\Breadcrumb\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Umbrella\CoreBundle\Component\Breadcrumb\Breadcrumb;
use Umbrella\CoreBundle\Component\Menu\MenuProvider;

/**
 * Class BreadcrumbTwigExtension.
 */
class BreadcrumbTwigExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * BreadcrumbTwigExtension constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->requestStack = $container->get('request_stack');
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_bc', array($this, 'get')),
            new \Twig_SimpleFunction('render_bc', array($this, 'render'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('render_bc_menu', array($this, 'renderFromMenu'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @return Breadcrumb
     */
    public function get()
    {
        return $this->container->get(Breadcrumb::class);
    }

    /**
     * @return string
     */
    public function render()
    {
        $bc = $this->get();

        return $this->container->get('twig')->render('UmbrellaCoreBundle:Breadcrumb:breadcrumb.html.twig', array(
            'breadcrumb' => $bc,
        ));
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function renderFromMenu($name)
    {
        $menu = $this->container->get(MenuProvider::class)->get($name);
        $bc = Breadcrumb::constructFromMenu($menu, $this->requestStack->getMasterRequest());

        return $this->container->get('twig')->render('UmbrellaCoreBundle:Breadcrumb:breadcrumb.html.twig', array(
            'breadcrumb' => $bc,
        ));
    }
}