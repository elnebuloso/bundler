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
    public function getFilenameMaxFile() {
        return $this->getName() . '.max.css';
    }

    /**
     * @return string
     */
    public function getFilenameMinFile() {
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

        foreach($this->getSelectedFiles() as $sourceFile) {
            $path = $fs->makePathRelative(dirname($sourceFile), dirname($this->destinationMax));
            $path = trim($path, '/');

            $css = file_get_contents($sourceFile);
            $this->content[] = $this->changeCssUrls($path, $css);

            $this->logDebug('- ' . $sourceFile);
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
    protected function changeCssUrls($baseUrl, $content) {
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