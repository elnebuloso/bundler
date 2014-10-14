<?php
namespace Bundler\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class JavascriptConfig
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptConfig implements ConfigurationInterface {

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('files');

        $rootNode->children()
                 ->arrayNode('packages')
                 ->isRequired()
                 ->requiresAtLeastOneElement()
                 ->prototype('array')
                 ->children()
                 ->scalarNode('public')
                 ->isRequired()
                 ->cannotBeEmpty()
                 ->end()
                 ->scalarNode('target')
                 ->isRequired()
                 ->cannotBeEmpty()
                 ->end()
                 ->arrayNode('compilers')
                 ->isRequired()
                 ->children()
                 ->arrayNode('googleClosureCompiler')
                 ->children()
                 ->scalarNode('compilationLevel')
                 ->isRequired()
                 ->cannotBeEmpty()
                 ->end()
                 ->scalarNode('warningLevel')
                 ->isRequired()
                 ->cannotBeEmpty()
                 ->end()
                 ->end()
                 ->end()
                 ->end()
                 ->end()
                 ->arrayNode('include')
                 ->isRequired()
                 ->requiresAtLeastOneElement()
                 ->prototype('scalar')
                 ->cannotBeEmpty()
                 ->end()
                 ->end()
                 ->end()
                 ->end()
                 ->end()
                 ->end();

        return $treeBuilder;
    }
}