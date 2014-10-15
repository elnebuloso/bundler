<?php
namespace Bundler;

use Bundler\Package\PackageInterface;
use Bundler\Tools\Benchmark;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Zend\Log\LoggerInterface;

/**
 * Class AbstractBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractBundler implements BundlerInterface {

    /**
     * @var string
     */
    private $yaml;

    /**
     * @var string
     */
    private $root;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OutputInterface
     */
    private $consoleOutput;

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
     * @param LoggerInterface $logger
     */
    public function setLogger($logger = null) {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger() {
        return $this->logger;
    }

    /**
     * @param OutputInterface $consoleOutput
     */
    public function setConsoleOutput($consoleOutput = null) {
        $this->consoleOutput = $consoleOutput;
    }

    /**
     * @return OutputInterface
     */
    public function getConsoleOutput() {
        return $this->consoleOutput;
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
     * @param string $message
     */
    public function logInfo($message) {
        if(!is_null($this->getLogger())) {
            $this->logger->info($message);
        }
        elseif(!is_null($this->getConsoleOutput())) {
            $this->consoleOutput->writeln("<comment>" . $message . "</comment>");
        }
    }

    /**
     * @param string $message
     */
    public function logDebug($message) {
        if(!is_null($this->getLogger())) {
            $this->logger->debug($message);
        }
        elseif(!is_null($this->getConsoleOutput())) {
            $this->consoleOutput->writeln("  <info>" . $message . "</info>");
        }
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

        $this->logInfo($this->getName());

        $benchmark = new Benchmark();
        $benchmark->start();

        $this->preBundle();

        foreach($this->getPackages() as $package) {
            $package->bundle();
        }

        $this->postBundle();

        $benchmark->stop();

        $this->logInfo("{$this->getName()} in {$benchmark->getTime()} seconds");
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