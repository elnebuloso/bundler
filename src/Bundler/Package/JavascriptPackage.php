<?php
namespace Bundler\Package;

use Bundler\Benchmark;

/**
 * Class JavascriptPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptPackage extends AbstractPublicPackage {

    /**
     * @return string
     */
    protected function getFilenameMaxFile() {
        return $this->getName() . '.max.js';
    }

    /**
     * @return string
     */
    protected function getFilenameMinFile() {
        return $this->getName() . '.min.js';
    }

    /**
     * @return void
     */
    protected function compress() {
        $this->logDebug("compressing files");

        $benchmark = new Benchmark();
        $benchmark->start();

        foreach($this->getSelectedFiles() as $sourceFilePath) {
            $this->content[] = file_get_contents($sourceFilePath);
            $this->logDebug("- {$sourceFilePath}");
        }

        $benchmark->stop();
        $this->logDebug("compressing files in {$benchmark->getTime()} seconds");
    }
}