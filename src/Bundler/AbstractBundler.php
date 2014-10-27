<?php
namespace Bundler;

use Bundler\Package\PackageInterface;
use Bundler\Tools\Benchmark;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AbstractBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractBundler implements BundlerInterface {

    /**
     * @var BundlerLogger
     */
    private $bundlerLogger;

    /**
     * @var string
     */
    private $yaml;

    /**
     * @var string
     */
    private $root;

    /**
     * @var PackageInterface[]
     */
    private $packages;

    /**
     * @var bool
     */
    private $isConfigured = false;

    /**
     * @param string $yaml
     * @param string $root
     * @throws BundlerException
     */
    public function __construct($yaml, $root = null) {
        $this->bundlerLogger = new BundlerLogger();
        $this->yaml = realpath($yaml);

        if($this->yaml === false) {
            throw new BundlerException('unable to find configuration: ' . $yaml, 1000);
        }

        // no root given from which the files are collected, we take the parent folder from ./.bundler
        if(empty($root)) {
            $this->root = dirname(dirname($this->yaml));
        }

        if(realpath($this->root) === false) {
            throw new BundlerException('invalid root path: ' . $this->root, 1001);
        }
    }

    /**
     * @param BundlerLogger $bundlerLogger
     */
    public function setBundlerLogger(BundlerLogger $bundlerLogger) {
        $this->bundlerLogger = $bundlerLogger;
    }

    /**
     * @return BundlerLogger
     */
    public function getBundlerLogger() {
        return $this->bundlerLogger;
    }

    /**
     * @return string
     */
    public function getYaml() {
        return $this->yaml;
    }

    /**
     * @return string
     */
    public function getRoot() {
        return $this->root;
    }

    /**
     * @param PackageInterface $package
     */
    public function addPackage(PackageInterface $package) {
        $this->packages[$package->getName()] = $package;
    }

    /**
     * @return PackageInterface[]
     */
    public function getPackages() {
        return $this->packages;
    }

    /**
     * @param string $name
     * @return PackageInterface|null
     */
    public function getPackageByName($name) {
        return array_key_exists($name, $this->packages) ? $this->packages[$name] : null;
    }

    /**
     * @return void
     */
    public function configure() {
        $this->isConfigured = true;

        $config = Yaml::parse($this->getYaml());
        $processor = new Processor();
        $configuration = $processor->processConfiguration($this->getConfiguration(), array($config));

        foreach($configuration['packages'] as $packageName => $packageConfiguration) {
            $this->addPackage($this->createPackage($packageName, $this->getRoot(), $packageConfiguration));
        }
    }

    /**
     * @return void
     */
    public function bundle() {
        if(!$this->isConfigured) {
            $this->configure();
        }

        $this->getBundlerLogger()->logInfo($this->getName());

        $benchmark = new Benchmark();
        $benchmark->start();

        $this->preBundle();

        foreach($this->getPackages() as $package) {
            $package->bundle();
        }

        $this->postBundle();

        $benchmark->stop();

        $this->getBundlerLogger()->logInfo("{$this->getName()} in {$benchmark->getTime()} seconds");
    }

    /**
     * @return ConfigurationInterface
     */
    abstract protected function getConfiguration();

    /**
     * @param string $name
     * @param string $root
     * @param array $configuration
     * @return PackageInterface
     */
    abstract protected function createPackage($name, $root, array $configuration);

    /**
     * @return void
     */
    abstract protected function preBundle();

    /**
     * @return void
     */
    abstract protected function postBundle();
}