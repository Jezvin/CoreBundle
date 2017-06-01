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
     * @var string
     */
    protected $devServerHost;

    /**
     * @var int
     */
    protected $devServerPort;

    /**
     * @var string
     */
    protected $assetPath;

    /**
     * @var string
     */
    protected $assetPatternDev;

    /**
     * @var string
     */
    protected $assetPatternProd;

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
        $this->devServerHost = $config['dev_server_host'];
        $this->devServerPort = $config['dev_server_port'];

        $this->assetPath = '/' . trim($config['asset_path'], '/') . '/';
        $this->assetPatternDev = $config['asset_pattern_dev'];
        $this->assetPatternProd = $config['asset_pattern_prod'];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('webpack_asset', array($this, 'asset')),
            new \Twig_SimpleFunction('webpack_asset_css', array($this, 'assetCss')),
            new \Twig_SimpleFunction('webpack_asset_js', array($this, 'assetJs')),
        );
    }

    /**
     * @param $assetName
     * @param $assetExtension
     * @return string
     */
    public function asset($assetName, $assetExtension)
    {
        $env = $this->container->getParameter('kernel.environment');
        return $env == 'dev'
            ? $this->assetDev($assetName, $assetExtension)
            : $this->assetProd($assetName,$assetExtension);
    }

    /**
     * @param $assetName
     * @return string
     */
    public function assetCss($assetName)
    {
        return $this->asset($assetName, 'css');
    }

    /**
     * @param $assetName
     * @return string
     */
    public function assetJs($assetName)
    {
        return $this->asset($assetName, 'js');
    }

    /**
     * @param $assetName
     * @param $assetExtension
     * @return string
     */
    private function assetDev($assetName, $assetExtension)
    {
        $path = $this->devServerEnabled ? $this->devServerHost . ':' . $this->devServerPort . $this->assetPath : $this->assetPath;
        return $this->findAsset($path, $assetName, $assetExtension, $this->assetPatternDev);
    }

    /**
     * @param $assetName
     * @param $assetExtension
     * @return string
     */
    private function assetProd($assetName, $assetExtension)
    {
        return $this->findAsset($this->assetPath, $assetName, $assetExtension, $this->assetPatternProd);
    }

    /**
     * @param $path
     * @param $assetName
     * @param $assetExtension
     * @param $assetPattern
     * @return string
     */
    private function findAsset($path, $assetName, $assetExtension, $assetPattern)
    {
        $assetPattern = str_replace('[name]', $assetName, $assetPattern) . '.' . $assetExtension;

        // no [hash] on filename => no search to do
        if (strpos($assetPattern, '[hash]') === false) {
            return $path . $assetPattern;
        }

        $absolutePath = $this->devServerEnabled
            ? $path
            : $this->container->get('kernel')->getRootDir().'/../web' . $path;


        $assetPattern = preg_quote($assetPattern);
        $assetPattern = str_replace('\[hash\]', '[[:alnum:]]+', $assetPattern);

        // else scan assets directory and find most recent file matching
        foreach (scandir($absolutePath, SCANDIR_SORT_DESCENDING) as $filename) {

            // ignore directory
            if (is_dir($absolutePath . $filename)) {
                continue;
            }

            if (preg_match("/$assetPattern/", $filename) === 1) {
                return $path . $filename;
            }

        }
        throw new \InvalidArgumentException("Enable to find asset [$assetName, $assetExtension] on path '$path', pattern = $assetPattern");
    }
}