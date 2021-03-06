<?php
namespace Bundler\Package;

use Bundler\Tools\Benchmark;

/**
 * Class JavascriptPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptPackage extends AbstractPublicPackage
{
    /**
     * @return string
     */
    public function getFilenameMaxFile()
    {
        return $this->getName() . '.max.js';
    }

    /**
     * @return string
     */
    public function getFilenameMinFile()
    {
        return $this->getName() . '.min.js';
    }

    /**
     * @return void
     */
    protected function bundlePackage()
    {
        $this->getBundlerLogger()->logDebug("compressing files to single file");

        $benchmark = new Benchmark();
        $benchmark->start();

        $content = [];

        foreach ($this->getIncludes() as $sourceFile) {
            $sourceFile = realpath($this->getRoot() . '/' . $sourceFile);
            $content[] = file_get_contents($sourceFile);
            $this->getBundlerLogger()->logDebug("- {$sourceFile}");
        }

        $this->compressContent($content);

        $benchmark->stop();

        $this->getBundlerLogger()->logDebug("compressing files to single file in {$benchmark->getTime()} seconds");
    }
}
