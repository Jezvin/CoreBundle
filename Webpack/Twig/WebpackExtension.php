<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 31/05/17
 * Time: 20:15
 */

namespace Umbrella\CoreBundle\Webpack\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WebpackExtension
 * @package Umbrella\CoreBundle\Webpacke\Twig
 */
class WebpackExtension  extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * WebpackExtension ContainerInterface constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('webpack_assets', array($this, 'assets')),
        );
    }

    /**
     * @param $resources
     * @return string
     */
    public function assets($resources)
    {
        return '/build/' . $resources;
    }
}