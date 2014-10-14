<?php
namespace Bundler\Package;

use Bundler\Benchmark;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class StylesheetPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetPackage extends AbstractPublicPackage {

    /**
     * @return string
     */
    protected function getFilenameMaxFile() {
        return $this->getName() . '.max.css';
    }

    /**
     * @return string
     */
    protected function getFilenameMinFile() {
        return $this->getName() . '.min.css';
    }

    /**
     * @return void
     */
    protected function compress() {
        $this->logDebug("compressing files");

        $benchmark = new Benchmark();
        $benchmark->start();

        $fs = new Filesystem();

        foreach($this->getSelectedFiles() as $sourceFilePath) {
            $path = $fs->makePathRelative(dirname($sourceFilePath), dirname($this->destinationMax));
            $path = trim($path, '/');

            $css = file_get_contents($sourceFilePath);
            $this->content[] = $this->changeUrlPath($path, $css);

            $this->logDebug('- ' . $sourceFilePath);
        }

        $this->createCompressedFiles();

        $benchmark->stop();
        $this->logDebug("compressing files in {$benchmark->getTime()} seconds");
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