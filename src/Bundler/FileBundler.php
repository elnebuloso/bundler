<?php
namespace Bundler;

use Bundler\Config\FileConfig;
use Bundler\Package\FilePackage;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FileBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileBundler extends AbstractBundler {

    /**
     * @param string $root
     * @param string $yaml
     */
    public function __construct($root, $yaml) {
        parent::__construct($root, $yaml);

        $config = Yaml::parse($yaml);
        $processor = new Processor();
        $configuration = new FileConfig();
        $processedConfiguration = $processor->processConfiguration($configuration, array($config));

        foreach($processedConfiguration['packages'] as $name => $package) {
            $this->addPackage(FilePackage::createFromArray($this->getRoot(), $name, $package));
        }
    }
}