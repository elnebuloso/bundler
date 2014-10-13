<?php
namespace Bundler\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class FileConfig
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileConfig implements ConfigurationInterface {

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
                 ->scalarNode('target')
                 ->isRequired()
                 ->cannotBeEmpty()
                 ->end()
                 ->scalarNode('version')
                 ->validate()
                 ->ifNotInArray(array(
                     'datetime',
                     'file'
                 ))
                 ->thenInvalid('invalid version type "%s"')
                 ->end()
                 ->end()
                 ->arrayNode('include')
                 ->isRequired()
                 ->requiresAtLeastOneElement()
                 ->prototype('scalar')
                 ->cannotBeEmpty()
                 ->end()
                 ->end()
                 ->arrayNode('exclude')
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