<?php

namespace Umbrella\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('umbrella_core');
        $rootNode->children()
            ->arrayNode('webpack')
                ->children()
                    ->booleanNode('dev_server_enable')->end()
                    ->scalarNode('dev_server_host')->end()
                    ->integerNode('dev_server_port')->end()
                    ->scalarNode('asset_path')->end()
                    ->scalarNode('asset_pattern_dev')->end()
                    ->scalarNode('asset_pattern_prod')->end()
                ->end()
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
