<?php
namespace Bundler\Package;

use Bundler\Tools\Benchmark;
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
    protected function bundlePackage() {
        $this->getBundlerLogger()->logDebug("compressing files to single file");

        $benchmark = new Benchmark();
        $benchmark->start();

        $fs = new Filesystem();
        $content = array();

        foreach($this->getSelectedFiles() as $sourceFile) {
            $path = $fs->makePathRelative(dirname($sourceFile), dirname($this->getDestinationMax()));
            $path = trim($path, '/');

            $css = file_get_contents($sourceFile);
            $css = $this->changeCssUrls($path, $css);

            $content[] = $css;
            $this->getBundlerLogger()->logDebug('- ' . $sourceFile);
        }

        $this->compressContent($content);

        $benchmark->stop();

        $this->getBundlerLogger()->logDebug("compressing files to single file in {$benchmark->getTime()} seconds");
    }

    /**
     * @param $baseUrl
     * @param $content
     * @return string
     */
    private function changeCssUrls($baseUrl, $content) {
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