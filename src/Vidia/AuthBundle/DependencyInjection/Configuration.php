<?php

namespace Vidia\AuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vidia_auth');

        $rootNode
            ->children()
            ->arrayNode('token')
            ->addDefaultsIfNotSet()
            ->children()
            ->integerNode('expiration_date')
            ->defaultValue(15)
            ->end()
            ->integerNode('increase_time')
            ->defaultValue(15)
            ->end()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
