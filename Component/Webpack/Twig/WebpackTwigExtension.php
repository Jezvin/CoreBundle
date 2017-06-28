<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 31/05/17
 * Time: 20:15
 */

namespace Umbrella\CoreBundle\Component\Webpack\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WebpackTwigExtension
 */
class WebpackTwigExtension  extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $env;

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
     * WebpackTwigExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->env = $container->getParameter('kernel.environment');
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
    public function asset($assetName, $assetExtension)
    {
        $webPath = $this->getWebPath();
        $assetPattern = $this->getAssetPattern();


        $assetPattern = str_replace('[name]', $assetName, $assetPattern) . '.' . $assetExtension;

        // no [hash] on filename => no search to do
        if (strpos($assetPattern, '[hash]') === false) {
            return $webPath . $assetPattern;
        }

        // [hash] on filename => do search with regexp
        $absolutePath = $this->getAbsolutePath();

        $assetPattern = preg_quote($assetPattern);
        $assetPattern = str_replace('\[hash\]', '[[:alnum:]]+', $assetPattern);

        $filenameList = scandir($this->getAbsolutePath(), SCANDIR_SORT_DESCENDING);

        foreach ($filenameList as $filename) {

            // ignore directory
            if (is_dir($absolutePath . $filename)) {
                continue;
            }

            if (preg_match("/$assetPattern/", $filename) === 1) {
                return $webPath . $filename;
            }

        }

        $err = "[webpack component] Enable to find asset [$assetName, $assetExtension] on path '$absolutePath', candidates = " . implode(', ', $filenameList);
        throw new \InvalidArgumentException($err);
    }


    /* Helper */

    /**
     * @return string
     */
    private function getWebPath()
    {
        return $this->devServerEnabled && $this->env == 'dev'
            ? $this->devServerHost . ':' . $this->devServerPort . $this->assetPath
            : $this->assetPath;
    }

    /**
     * @return string
     */
    private function getAbsolutePath()
    {
        return $this->container->get('kernel')->getRootDir().'/../web' . $this->assetPath;
    }

    /**
     * @return string
     */
    private function getAssetPattern()
    {
        return $this->env == 'dev' ? $this->assetPatternDev : $this->assetPatternProd;
    }

}