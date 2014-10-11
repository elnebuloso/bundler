<?php
namespace Bundler\Command;

use Bundler\FileSelector;
use Bundler\Package\StylesheetPackage;
use Bundler\Package\StylesheetPackagist;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StylesheetCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetCommand extends AbstractCommand {

    /**
     * @var StylesheetPackagist
     */
    private $bundler;

    /**
     * @var StylesheetPackage
     */
    private $package;

    /**
     * @return void
     */
    protected function configure() {
        $this->setName('bundle:stylesheet');
        $this->setDescription('bundling stylesheet');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->writeComment("bundling stylesheet", true, true);

        $timeStart = microtime(true);

        $this->readConfiguration();
        $this->bundlePackages();

        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;

        $this->writeComment("bundling stylesheet in {$time} seconds", true, true);
    }

    /**
     * @throws Exception
     */
    private function readConfiguration() {
        $this->writeInfo('read configuration');

        $yaml = $this->dir . '/.bundler/stylesheet.yaml';

        if(!realpath($yaml)) {
            throw new Exception("missing configuration yaml file: {$yaml}");
        }

        $this->bundler = StylesheetPackagist::createFromYaml($this->dir, $yaml);
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
        $this->destinationMax = "{$this->dir}/{$this->package->getTo()}/{$this->package->getName()}.bundler.max.css";
        $this->destinationMin = "{$this->dir}/{$this->package->getTo()}/{$this->package->getName()}.bundler.min.css";

        foreach($this->fileSelector->getFiles() as $file) {
            $this->content[] = file_get_contents($file);

            $this->writeInfo($file);
        }

        $this->writeInfo("compressing files", true, false);
        $this->writeInfo("destinationMax: {$this->destinationMax}");
        $this->writeInfo("destinationMin: {$this->destinationMin}");
    }
}