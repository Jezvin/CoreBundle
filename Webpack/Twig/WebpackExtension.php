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
     * @var bool
     */
    protected $devServerEnabled;

    /**
     * @var int
     */
    protected $devServerPort;

    /**
     * @var string
     */
    protected $assetsPath;

    /**
     * WebpackExtension ContainerInterface constructor.
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /* Call by Bundle configurator */

    public function loadConfig(array $config)
    {
        $this->devServerEnabled = $config['dev_server_enable'];
        $this->devServerPort = $config['dev_server_port'];
        $this->assetsPath = rtrim($config['assets_path'], '/') . '/';
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
        return $this->assetsPath . $resources;
    }
}