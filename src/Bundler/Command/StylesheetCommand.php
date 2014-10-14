<?php
namespace Bundler\Command;

use Bundler\Package\StylesheetPackage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class StylesheetCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetCommand extends AbstractPublicCommand {

    /**
     * @var StylesheetPackage;
     */
    protected $currentPackage;

    /**
     * @return void
     */
    protected function configure() {
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);
    }

    /**
     * @return string
     */
    public function getCommandName() {
        return 'bundle:stylesheet';
    }

    /**
     * @return string
     */
    public function getCommandDescription() {
        return 'bundling stylesheet';
    }

    /**
     * @return void
     */
    public function preCommand() {
        $this->cache = array();
        $this->cacheFilename = dirname($this->yaml) . '/stylesheet.php';
    }

    /**
     * @return void
     */
    public function compress() {
        $this->writeInfo("compressing files");

        $benchmark = new Benchmark();
        $benchmark->start();

        $fs = new Filesystem();

        foreach($this->currentPackage->getSelectedFiles() as $sourceFilePath) {
            $path = $fs->makePathRelative(dirname($sourceFilePath), dirname($this->destinationMax));
            $path = trim($path, '/');

            $css = file_get_contents($sourceFilePath);
            $this->content[] = $this->changeUrlPath($path, $css);

            $this->writeInfo('- ' . $sourceFilePath);
        }

        $this->createFiles();

        $benchmark->stop();
        $this->writeInfo("compressing files in {$benchmark->getTime()} seconds");
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
}