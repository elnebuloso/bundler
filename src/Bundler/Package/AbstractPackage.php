<?php
namespace Bundler\Package;

use Bundler\FileSystem\FileSelector;
use Bundler\Tools\Benchmark;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Log\LoggerInterface;

/**
 * Class AbstractPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractPackage implements PackageInterface {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $target;

    /**
     * @var array
     */
    private $includes;

    /**
     * @var array
     */
    private $excludes;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OutputInterface
     */
    private $consoleOutput;

    /**
     * @var FileSelector
     */
    private $fileSelector;

    /**
     * @return self
     */
    public function __construct() {
        $this->includes = array();
        $this->excludes = array();
        $this->fileSelector = new FileSelector();
    }

    /**
     * @param string $name
     * @throws PackageException
     */
    public function setName($name) {
        $this->name = trim($name);

        if(empty($this->name)) {
            throw new PackageException('the package name cannot be empty', 5000);
        }
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $root
     * @throws PackageException
     */
    public function setRoot($root) {
        $this->root = realpath($root);

        if($this->root === false) {
            throw new PackageException('invalid root path: ' . $root, 5001);
        }
    }

    /**
     * @return string
     */
    public function getRoot() {
        return $this->root;
    }

    /**
     * @param string $target
     */
    public function setTarget($target) {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     * @param array $includes
     */
    public function setIncludes(array $includes) {
        $this->includes = $includes;
    }

    /**
     * @return array
     */
    public function getIncludes() {
        return $this->includes;
    }

    /**
     * @param array $excludes
     */
    public function setExcludes(array $excludes) {
        $this->excludes = $excludes;
    }

    /**
     * @return array
     */
    public function getExcludes() {
        return $this->excludes;
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
    public function selectFiles() {
        $this->logDebug("selecting files");

        $benchmark = new Benchmark();
        $benchmark->start();

        $this->fileSelector = new FileSelector();
        $this->fileSelector->setDir($this->root);
        $this->fileSelector->setIncludes($this->getIncludes());
        $this->fileSelector->setExcludes($this->getExcludes());
        $this->fileSelector->select();

        $benchmark->stop();

        $this->logDebug("selecting files: {$this->getSelectedFilesCount()} files in {$benchmark->getTime()} seconds");
    }

    /**
     * @return array
     */
    public function getSelectedFiles() {
        return $this->fileSelector->getFiles();
    }

    /**
     * @return int
     */
    public function getSelectedFilesCount() {
        return $this->fileSelector->getFilesCount();
    }

    /**
     * @return void
     */
    public function bundle() {
        $this->logInfo("bundling package: {$this->getName()}");

        $benchmark = new Benchmark();
        $benchmark->start();

        $this->selectFiles();
        $this->bundlePackage();

        $benchmark->stop();

        $this->logInfo("bundling package: {$this->getName()} in {$benchmark->getTime()} seconds");
    }

    /**
     * @return void
     */
    abstract protected function bundlePackage();
}