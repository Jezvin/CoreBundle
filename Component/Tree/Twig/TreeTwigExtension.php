<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 03/06/17
 * Time: 14:09
 */
namespace Umbrella\CoreBundle\Component\Tree\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Umbrellac\CoreBundle\Component\Tree\Model\Tree;

/**
 * Class TreeTwigExtension
 */
class TreeTwigExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * TreeTwigExtension constructor.
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
            new \Twig_SimpleFunction('render_tree', array($this, 'render'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
            new \Twig_SimpleFunction('tree_js', array($this, 'renderJs'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            ))
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param Tree $tree
     * @return string
     */
    public function render(\Twig_Environment $twig, Tree $tree)
    {
        $options = array();
        $options['tree'] = $tree;
        $options['id'] = $tree->id;
        $options['class'] = $tree->class;

        return $twig->render($tree->template, $options);
    }

    /**
     * @param \Twig_Environment $twig
     * @param Tree $tree
     * @return string
     */
    public function renderJs(\Twig_Environment $twig, Tree $tree)
    {
        $options = array();
        $options['tree'] = $tree;
        $options['id'] = $tree->id;
        $options['js'] = $this->buildJsOptions($tree);

        return $twig->render("UmbrellaCoreBundle:Tree:tree_js.html.twig", $options);
    }

    /**
     * @param Tree $tree
     *
     * @return array
     */
    protected function buildJsOptions(Tree $tree)
    {
        $options = array();
        $options['collapsable'] = $tree->collapsable;
        $options['start_expanded'] = $tree->startExpanded;
        $options['ajax_relocate_url'] = $tree->relocateUrl;
        $options['ajax_relocate_type'] = $tree->relocateType;
        return $options;
    }

}