<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/05/17
 * Time: 16:58.
 */

namespace Umbrella\CoreBundle\Component\Menu\Twig;

use Symfony\Component\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\Menu\Helper\MenuHelper;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Menu\Model\MenuNode;

/**
 * Class MenuTwigExtension.
 */
class MenuTwigExtension extends \Twig_Extension
{
    /**
     * @var MenuHelper
     */
    private $helper;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * MenuTwigExtension constructor.
     * @param MenuHelper $helper
     * @param TranslatorInterface $translator
     */
    public function __construct(MenuHelper $helper, TranslatorInterface $translator)
    {
        $this->helper = $helper;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('menu_get', array($this, 'get')),
            new \Twig_SimpleFunction('menu_render', array($this, 'render'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('menu_is_granted_node', array($this, 'isGranted')),
            new \Twig_SimpleFunction('menu_is_current_node', array($this, 'isCurrent')),
            new \Twig_SimpleFunction('menu_get_current_node', array($this, 'getCurrentNode')),
            new \Twig_SimpleFunction('menu_get_current_node_title', array($this, 'getCurrentNodeTitle')),
            new \Twig_SimpleFunction('menu_render_breadcrumb',
                array($this, 'renderBreadcrumb'),
                array('needs_environment' => true, 'is_safe' => array('html'))),
        );
    }

    /**
     * @param $name
     *
     * @return Menu
     */
    public function get($name)
    {
        return $this->helper->get($name);
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function render($name)
    {
        return $this->helper->getRenderer($name)->render($this->get($name));
    }

    /**
     * @param MenuNode $node
     *
     * @return bool
     */
    public function isGranted(MenuNode $node)
    {
        return $this->helper->isGranted($node);
    }

    /**
     * @param MenuNode $node
     * @return bool
     */
    public function isCurrent(MenuNode $node)
    {
        return $this->helper->isCurrent($node);
    }

    /**
     * @param $name
     * @return null|MenuNode
     */
    public function getCurrentNode($name)
    {
        $menu = $this->helper->get($name);
        return $this->helper->getCurrentNode($menu);
    }


    /**
     * @param $name
     * @param string $default
     *
     * @return string
     */
    public function getCurrentNodeTitle($name, $default = '')
    {
        $menu = $this->helper->get($name);
        $currentNode = $this->helper->getCurrentNode($menu);

        if ($currentNode) {
            return $currentNode->translateLabel
                ? $this->translator->trans($menu->translationPrefix .$currentNode->label)
                : $currentNode->label;
        } else {
            return $default;
        }
    }

    /**
     * @param \Twig_Environment $twig
     * @param $name
     * @return string
     */
    public function renderBreadcrumb(\Twig_Environment $twig, $name)
    {
        $menu = $this->helper->get($name);
        $bc = $this->helper->buildBreadcrumb($menu);
        return $twig->render($bc->template, [ 'breadcrumb' => $bc ]);
    }
}
