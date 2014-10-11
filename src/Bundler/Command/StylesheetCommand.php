<?php
namespace Bundler\Command;

use Bundler\FileSelector;
use Bundler\Package\StylesheetPackage;
use Bundler\Package\StylesheetPackagist;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

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
        $this->destinationMax = "{$this->dir}/{$this->package->getPublic()}/{$this->package->getTo()}/{$this->package->getName()}.bundler.max.css";
        $this->destinationMin = "{$this->dir}/{$this->package->getPublic()}/{$this->package->getTo()}/{$this->package->getName()}.bundler.min.css";

        $fs = new Filesystem();

        foreach($this->fileSelector->getFiles() as $file) {
            $path = $fs->makePathRelative(dirname($file), dirname($this->destinationMax));
            $path = trim($path, '/');

            $css = file_get_contents($file);
            $css = $this->changeUrlPath($path, $css);

            $this->content[] = $css;
            $this->writeInfo($file);
        }

        // create max file
        $this->content = implode(PHP_EOL . PHP_EOL, $this->content);
        file_put_contents($this->destinationMax, $this->content);

        switch($this->package->getCompiler()) {
            case "yuicompressor":
                $this->compileWithYuiCompressor();
                $this->writeInfo("compiled by yui compressor", true, false);
                break;
        }

        $this->writeInfo("destinationMax: {$this->destinationMax}");
        $this->writeInfo("destinationMin: {$this->destinationMin}", false, true);
        $this->outputBundlingFilesCompression();
    }

    /**
     * @param $baseUrl
     * @param $content
     * @return string
     */
    private function changeUrlPath($baseUrl, $content) {
        preg_match_all('/url\(\s*[\'"]?\/?(.+?)[\'"]?\s*\)/i', $content, $matches);

        $from = array();
        $with = array();

        foreach($matches[0] as $match) {
            if(strpos($match, 'http') === false) {
                $from[] = $match;
                $with[] = preg_replace('/url\(\s*[\'"]?\/?(.+?)[\'"]?\s*\)/i', 'url(' . $baseUrl . '/$1)', $match);
            }
        }

        return str_replace($from, $with, $content);
    }

    /**
     * @return void
     * @throws Exception
     */
    private function compileWithYuiCompressor() {
        $command = $this->resources . "/../bin/yuicompressor --type css --line-break 5000 -o {$this->destinationMin} {$this->destinationMax}";
        exec($command);
    }
}