<?php
namespace Bundler;

use Bundler\Package\PackageInterface;
use Bundler\Tools\Benchmark;

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
    private $file;

    /**
     * @var string
     */
    private $root;

    /**
     * @var PackageInterface[]
     */
    private $packages;

    /**
     * @param string $file
     * @param string $root
     * @throws BundlerException
     */
    public function __construct($file, $root = null) {
        $this->setBundlerLogger(new BundlerLogger());
        $this->setFile($file);
        $this->setRoot($root);
        $this->configure();
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
     * @param $file
     * @throws BundlerException
     */
    public function setFile($file) {
        $this->file = realpath($file);

        if($this->file === false) {
            throw new BundlerException('unable to find configuration: ' . $file, 1000);
        }
    }

    /**
     * @return string
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * @param $root
     * @throws BundlerException
     */
    public function setRoot($root) {
        // no root given from which the files are collected, we take the parent folder from ./.bundler
        if(empty($root)) {
            $root = dirname(dirname($this->file));
        }

        $this->root = realpath($root);

        if($this->root === false) {
            throw new BundlerException('invalid root path: ' . $root, 1001);
        }
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
     * @param string $name
     * @return PackageInterface|null
     */
    public function getPackageByName($name) {
        return array_key_exists($name, $this->packages) ? $this->packages[$name] : null;
    }

    /**
     * @return PackageInterface[]
     */
    public function getPackages() {
        return $this->packages;
    }

    /**
     * @return void
     */
    public function configure() {
        $this->packages = array();

        /** @noinspection PhpIncludeInspection */
        $packages = include $this->getFile();

        foreach($packages as $packageName => $configuration) {
            $this->addPackage($this->createPackage($packageName, $this->getRoot(), $configuration));
        }
    }

    /**
     * @return void
     */
    public function bundle() {
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