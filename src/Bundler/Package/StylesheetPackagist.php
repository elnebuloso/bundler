<?php
namespace Bundler\Package;

use Bundler\Config\StylesheetConfig;
use Exception;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Class StylesheetPackagist
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetPackagist {

    /**
     * @var StylesheetPackage[]
     */
    private $packages;

    /**
     * @param string $dir
     * @param array $config
     * @throws Exception
     */
    public function __construct($dir, array $config) {
        foreach($config['packages'] as $name => $package) {
            $this->packages[] = StylesheetPackage::createFromArray($name, $package);
        }

        if(realpath($dir) === false) {
            throw new Exception("the given dir is invalid: {$dir}");
        }
    }

    /**
     * @param string $dir
     * @param string $yaml
     * @return StylesheetPackagist
     */
    public static function createFromYaml($dir, $yaml) {
        $config = Yaml::parse($yaml);

        $processor = new Processor();
        $configuration = new StylesheetConfig();
        $processedConfiguration = $processor->processConfiguration($configuration, array($config));

        return new self($dir, $processedConfiguration);
    }

    /**
     * @return StylesheetPackage[]
     */
    public function getPackages() {
        return $this->packages;
    }
}