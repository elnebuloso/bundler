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
    public function getFilenameMaxFile() {
        return $this->getName() . '.max.js';
    }

    /**
     * @return string
     */
    public function getFilenameMinFile() {
        return $this->getName() . '.min.js';
    }

    /**
     * @return void
     */
    protected function compress() {
        $this->logDebug("compressing files");

        $benchmark = new Benchmark();
        $benchmark->start();

        foreach($this->getSelectedFiles() as $sourceFile) {
            $this->content[] = file_get_contents($sourceFile);
            $this->logDebug("- {$sourceFile}");
        }

        $this->createCompressedFiles();

        $benchmark->stop();
        $this->logDebug("compressing files in {$benchmark->getTime()} seconds");
    }
}