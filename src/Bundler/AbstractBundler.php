<?php
namespace Bundler;

use Bundler\Config\FileConfig;
use Bundler\Config\JavascriptConfig;
use Bundler\Config\StylesheetConfig;
use Bundler\Package\Package;
use Bundler\Package\PackageFactory;
use Exception;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Zend\Log\LoggerInterface;

/**
 * Class AbstractBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractBundler implements Bundler {

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
    private $output;

    /**
     * @var Package[]
     */
    private $packages;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param string $yaml
     * @param string $root
     * @throws Exception
     */
    public function __construct($yaml, $root = null) {
        $this->yaml = realpath($yaml);

        if($this->yaml === false) {
            throw new Exception('unable to find configuration: ' . $yaml);
        }

        // no root given from which the files are collected, we take the parent folder from ./.bundler
        if(empty($root)) {
            $this->root = dirname(dirname($this->yaml));
        }

        if(realpath($this->root) === false) {
            throw new Exception('invalid root path: ' . $this->root);
        }
    }

    /**
     * @throws Exception
     */
    public function configure() {
        $config = Yaml::parse($this->getYaml());
        $processor = new Processor();
        $configuration = null;

        switch($this->type) {
            case self::TYPE_FILES:
                $configuration = new FileConfig();
                break;

            case self::TYPE_JAVASCRIPT:
                $configuration = new JavascriptConfig();
                break;

            case self::TYPE_STYLESHEET:
                $configuration = new StylesheetConfig();
                break;
        }

        if(is_null($configuration)) {
            throw new Exception('missing configuration to process');
        }

        $processedConfiguration = $processor->processConfiguration($configuration, array($config));

        $packageFactory = new PackageFactory();
        $packageFactory->setLogger($this->getLogger());
        $packageFactory->setOutput($this->getOutput());

        foreach($processedConfiguration['packages'] as $name => $package) {
            $this->addPackage($packageFactory->create($this->type, $name, $this->getRoot(), $package));
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
     * @param OutputInterface $output
     */
    public function setOutput($output = null) {
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput() {
        return $this->output;
    }

    /**
     * @param Package $package
     */
    public function addPackage(Package $package) {
        $this->packages[$package->getName()] = $package;
    }

    /**
     * @return Package[]
     */
    public function getPackages() {
        return $this->packages;
    }

    /**
     * @param string $name
     * @return Package|null
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
        elseif(!is_null($this->getOutput())) {
            $this->output->writeln("<comment>" . $message . "</comment>");
        }
    }

    /**
     * @param string $message
     */
    public function logDebug($message) {
        if(!is_null($this->getLogger())) {
            $this->logger->debug($message);
        }
        elseif(!is_null($this->getOutput())) {
            $this->output->writeln("  <info>" . $message . "</info>");
        }
    }

    /**
     * @return void
     */
    public function bundle() {
        $this->logInfo($this->title);

        $benchmark = new Benchmark();
        $benchmark->start();

        foreach($this->getPackages() as $package) {
            $package->bundle();
        }

        $this->postBundle();

        $benchmark->stop();
        $this->logInfo("{$this->title} in {$benchmark->getTime()} seconds");
    }

    /**
     * @return void
     */
    protected function postBundle() {
        // intentionally left blank
    }
}