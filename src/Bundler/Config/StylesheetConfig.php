<?php
namespace Bundler\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class StylesheetConfig
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetConfig implements ConfigurationInterface {

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('files');

        $rootNode
            ->children()
                ->arrayNode('packages')
                ->isRequired()
                ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('to')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('compiler')
                                ->validate()
                                    ->ifNotInArray(array('yuicompressor'))->thenInvalid('invalid compiler type "%s"')
                                ->end()
                            ->end()
                            ->arrayNode('include')->isRequired()->requiresAtLeastOneElement()
                                ->prototype('scalar')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}