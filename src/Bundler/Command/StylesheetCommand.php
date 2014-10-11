<?php
namespace Bundler\Command;

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

            $timeEnd = microtime(true);
            $time = $timeEnd - $timeStart;

            $this->writeComment("bundling package: {$this->package->getName()} in {$time} seconds", true);
        }
    }
}