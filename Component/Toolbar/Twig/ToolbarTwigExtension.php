<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 14/05/17
 * Time: 18:46.
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Twig;

use Umbrella\CoreBundle\Component\Toolbar\Toolbar;

/**
 * Class DataTableTwigExtension.
 */
class ToolbarTwigExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_toolbar', array($this, 'render'), array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param Toolbar $toolbar
     * @return string
     */
    public function render(\Twig_Environment $twig, Toolbar $toolbar)
    {

        $options = array();
        $options['toolbar'] = $toolbar;
        $options['form'] = $toolbar->form ? $toolbar->form ->createView() : null;
        $options['actions'] = $toolbar->actions;
        $options['class'] = $toolbar->class;

        return $twig->render($toolbar->template, $options);
    }
}
