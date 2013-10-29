<?php

namespace FDevs\ElfinderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('f_devs_elfinder');
        $supportedEditors = array('ckeditor', 'simple');
        $rootNode
            ->children()
                ->scalarNode('editor')
                    ->validate()
                        ->ifNotInArray($supportedEditors)
                        ->thenInvalid('The editor %s is not supported. Please choose one of '.json_encode($supportedEditors))
                    ->end()
                ->cannotBeOverwritten()
                ->isRequired()
                ->cannotBeEmpty()
                ->end()
                ->booleanNode('debug')->defaultValue(false)->end()
            ->end()
        ;
        $this->setConnectionSection($rootNode);

        return $treeBuilder;
    }

    private function setConnectionSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('connector')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('driver')->defaultValue('f_devs_elfinder.local')->end()
                            ->scalarNode('class')->defaultValue(null)->end()
                            ->arrayNode('disabled')
                                ->prototype('scalar')->end()
                                ->defaultValue(array())
                             ->end()
                            ->arrayNode('options')
                                ->prototype('scalar')->end()
                                ->defaultValue(array())
                             ->end()
                            ->arrayNode('driver_options')
                                ->prototype('scalar')->end()
                                ->defaultValue(array())
                             ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

    }
}
