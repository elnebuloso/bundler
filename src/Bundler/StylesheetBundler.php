<?php
namespace Bundler;

use Bundler\Config\StylesheetConfig;
use Bundler\Package\StylesheetPackage;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Class StylesheetBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetBundler extends AbstractBundler {

    /**
     * @param string $root
     * @param string $yaml
     */
    public function __construct($root, $yaml) {
        parent::__construct($root, $yaml);

        $config = Yaml::parse($yaml);
        $processor = new Processor();
        $configuration = new StylesheetConfig();
        $processedConfiguration = $processor->processConfiguration($configuration, array($config));

        foreach($processedConfiguration['packages'] as $name => $package) {
            $this->addPackage(StylesheetPackage::createFromArray($this->getRoot(), $name, $package));
        }
    }
}