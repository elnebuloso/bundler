<?php
namespace Bundler;

use Bundler\Config\StylesheetConfig;
use Bundler\Model\Package\StylesheetPackage;
use Exception;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Class StylesheetBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetBundler {

    /**
     * @var string
     */
    private $dir;

    /**
     * @var array
     */
    private $config;

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
        $this->dir = $dir;
        $this->config = $config;

        foreach($this->config['packages'] as $name => $package) {
            $this->packages[] = StylesheetPackage::createFromArray($name, $package);
        }

        if(realpath($dir) === false) {
            throw new Exception("the given dir is invalid: {$this->dir}");
        }

        $this->dir = realpath($dir);
    }

    /**
     * @param string $dir
     * @param string $yaml
     * @return JavascriptBundler
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