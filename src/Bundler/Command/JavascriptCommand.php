<?php
namespace Bundler\Command;

use Bundler\FileSelector;
use Bundler\Package\JavascriptPackage;
use Bundler\Package\JavascriptPackagist;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JavascriptCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptCommand extends AbstractCommand {

    /**
     * @var JavascriptPackagist
     */
    private $bundler;

    /**
     * @var JavascriptPackage
     */
    private $package;

    /**
     * @return void
     */
    protected function configure() {
        $this->setName('bundle:javascript');
        $this->setDescription('bundling javascript');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->writeComment("bundling javascript", true, true);

        $timeStart = microtime(true);

        $this->readConfiguration();
        $this->bundlePackages();

        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;

        $this->writeComment("bundling javascript in {$time} seconds", true, true);
    }

    /**
     * @throws Exception
     */
    private function readConfiguration() {
        $this->writeInfo('read configuration');

        $yaml = $this->dir . '/.bundler/javascript.yaml';

        if(!realpath($yaml)) {
            throw new Exception("missing configuration yaml file: {$yaml}");
        }

        $this->bundler = JavascriptPackagist::createFromYaml($this->dir, $yaml);
    }

    /**
     * @return void
     */
    private function bundlePackages() {
        $this->writeInfo('bundle packages');

        foreach($this->bundler->getPackages() as $this->package) {
            $timeStart = microtime(true);

            $this->writeComment("bundling package: {$this->package->getName()}", true, true);
            $this->selectFiles();
            $this->compress();

            $timeEnd = microtime(true);
            $time = $timeEnd - $timeStart;

            $this->writeComment("bundling package: {$this->package->getName()} in {$time} seconds", true);
        }
    }

    /**
     * @return void
     */
    private function selectFiles() {
        $this->writeInfo("selecting files");

        $timeStart = microtime(true);

        $this->fileSelector = new FileSelector();
        $this->fileSelector->setDir($this->dir);
        $this->fileSelector->setIncludes($this->package->getIncludes());
        $this->fileSelector->select();

        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;

        $this->writeInfo("selecting {$this->fileSelector->getFilesCount()} files in {$time} seconds");
    }

    /**
     * @return void
     */
    private function compress() {
        $this->writeInfo("compressing files", true, false);

        $this->content = array();
        $this->destinationMax = "{$this->dir}/{$this->package->getTo()}/{$this->package->getName()}.bundler.max.js";
        $this->destinationMin = "{$this->dir}/{$this->package->getTo()}/{$this->package->getName()}.bundler.min.js";

        foreach($this->fileSelector->getFiles() as $file) {
            $this->content[] = file_get_contents($file);

            $this->writeInfo($file);
        }

        // create max file
        $this->content = implode(PHP_EOL . PHP_EOL, $this->content);
        file_put_contents($this->destinationMax, $this->content);

        switch($this->package->getCompiler()) {
            case "google-closure-compiler":
                $this->compileWithGoogleClosureCompiler();
                $this->writeInfo("compiled by google closure compiler", true, false);
                break;
        }

        $this->writeInfo("destinationMax: {$this->destinationMax}");
        $this->writeInfo("destinationMin: {$this->destinationMin}", false, true);
        $this->outputBundlingFilesCompression();
    }

    /**
     * @link http://dl.google.com/closure-compiler/compiler-latest.zip
     * @throws Exception
     * @return void
     */
    protected function compileWithGoogleClosureCompiler() {
        $command = $this->resources . "/../bin/google-closure-compiler --compilation_level=WHITESPACE_ONLY --warning_level=QUIET --js={$this->destinationMax} --js_output_file={$this->destinationMin}";
        exec($command);
    }
}