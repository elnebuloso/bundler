<?php
namespace Bundler\Command;

use Bundler\AbstractBundler;
use Bundler\FileBundler;
use Bundler\JavascriptBundler;
use Bundler\Package\Package;
use Bundler\StylesheetBundler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractCommand extends Command {

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var string
     */
    protected $root;

    /**
     * @var string
     */
    protected $yaml;

    /**
     * @var Benchmark
     */
    protected $benchmark;

    /**
     * @var AbstractBundler
     */
    protected $bundler;

    /**
     * @var Package
     */
    protected $currentPackage;

    /**
     * @param string $root
     */
    public function setRoot($root) {
        $this->root = $root;
    }

    /**
     * @param string $yaml
     */
    public function setYaml($yaml) {
        $this->yaml = $yaml;
    }

    /**
     * @return void
     */
    protected function configure() {
        $this->benchmark = new Benchmark();

        $this->setName($this->getCommandName());
        $this->setDescription($this->getCommandName());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->input = $input;
        $this->output = $output;

        $this->output->writeln('');
        $this->benchmark->start();
        $this->writeComment($this->getCommandDescription() . " ...");
        $this->setupBundler();
        $this->initCommand();

        // bundle each package
        foreach($this->bundler->getPackages() as $this->currentPackage) {
            $benchmark = new Benchmark();
            $benchmark->start();

            $this->writeComment("bundling package: {$this->currentPackage->getName()}");

            $this->writeInfo("selecting files");
            $benchmarkFiles = new Benchmark();
            $benchmarkFiles->start();
            $this->currentPackage->selectFiles();
            $benchmarkFiles->stop();
            $this->writeInfo("selecting files: {$this->currentPackage->getFilesCount()} files in {$benchmarkFiles->getTime()} seconds");

            $this->bundleCurrentPackage();

            $benchmark->stop();
            $this->writeComment("bundling package: {$this->currentPackage->getName()} in {$benchmark->getTime()} seconds");
        }

        $this->benchmark->stop();
        $this->writeComment($this->getCommandDescription() . " in {$this->benchmark->getTime()} seconds");
        $this->output->writeln('');
    }

    /**
     * @param string $message
     */
    public function writeComment($message) {
        $this->output->writeln("<comment>" . $message . "</comment>");
    }

    /**
     * @param string $message
     */
    public function writeInfo($message) {
        $this->output->writeln("  <info>" . $message . "</info>");
    }

    /**
     * @return void
     */
    private function setupBundler() {
        if($this instanceof FileCommand) {
            $this->writeInfo('setting up file bundler');
            $this->bundler = new FileBundler($this->root, $this->yaml);
        }

        if($this instanceof JavascriptCommand) {
            $this->writeInfo('setting up javascript bundler');
            $this->bundler = new JavascriptBundler($this->root, $this->yaml);
        }

        if($this instanceof StylesheetCommand) {
            $this->writeInfo('setting up stylesheet bundler');
            $this->bundler = new StylesheetBundler($this->root, $this->yaml);
        }
    }

    /**
     * @return string
     */
    abstract public function getCommandName();

    /**
     * @return string
     */
    abstract public function getCommandDescription();

    /**
     * @return void
     */
    abstract public function initCommand();

    /**
     * @return void
     */
    abstract public function bundleCurrentPackage();
}