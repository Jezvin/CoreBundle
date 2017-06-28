<?php

namespace Umbrellac\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Umbrellac\CoreBundle\Component\Webpack\Twig\WebpackTwigExtension;
use Umbrellac\CoreBundle\Services\UmbrellaFileUploader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UmbrellacCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // load form extension only if enable
        if ($config['form']['enable_extension'] === true) {
            $loader->load('form_extension.yml');
        }

        $def = $container->getDefinition(WebpackTwigExtension::class);
        $def->addMethodCall('loadConfig', [$config['webpack']]);

        $def = $container->getDefinition(UmbrellaFileUploader::class);
        $def->addMethodCall('loadConfig', [$config['file']]);
    }
}
