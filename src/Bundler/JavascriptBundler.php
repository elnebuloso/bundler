<?php
namespace Bundler;

use Bundler\Config\JavascriptConfig;
use Bundler\Package\JavascriptPackage;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Class JavascriptBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptBundler extends AbstractBundler {

    /**
     * @param string $root
     * @param string $yaml
     */
    public function __construct($root, $yaml) {
        parent::__construct($root, $yaml);

        $config = Yaml::parse($yaml);
        $processor = new Processor();
        $configuration = new JavascriptConfig();
        $processedConfiguration = $processor->processConfiguration($configuration, array($config));

        foreach($processedConfiguration['packages'] as $name => $package) {
            $this->addPackage(JavascriptPackage::createFromArray($this->getRoot(), $name, $package));
        }
    }
}