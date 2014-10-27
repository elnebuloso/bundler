<?php
namespace Bundler\Package;

use Bundler\Compiler\Compiler;

/**
 * Class AbstractPublicPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractPublicPackage extends AbstractPackage {

    /**
     * @var string
     */
    private $public;

    /**
     * @var Compiler[]
     */
    private $compilers;

    /**
     * @param string $public
     */
    public function setPublic($public) {
        $this->public = $public;
    }

    /**
     * @return string
     */
    public function getPublic() {
        return $this->public;
    }

    /**
     * @param Compiler $compiler
     */
    public function addCompiler(Compiler $compiler) {
        $this->compilers[] = $compiler;
    }

    /**
     * @return Compiler[]
     */
    public function getCompilers() {
        return $this->compilers;
    }

    /**
     * @return string
     */
    public function getDestinationMax() {
        return "{$this->getRoot()}/{$this->getTarget()}/{$this->getFilenameMaxFile()}";
    }

    /**
     * @return string
     */
    public function getDestinationMin() {
        return "{$this->getRoot()}/{$this->getTarget()}/{$this->getFilenameMinFile()}";
    }

    /**
     * @return string
     */
    abstract public function getFilenameMaxFile();

    /**
     * @return string
     */
    abstract public function getFilenameMinFile();

    /**
     * @param array $content
     * @return void
     */
    protected function compressContent(array $content) {
        $content = implode(PHP_EOL . PHP_EOL, $content);
        $tempFilename = $this->getDestinationMin() . '.tmp';

        file_put_contents($this->getDestinationMax(), $content);
        file_put_contents($this->getDestinationMin(), $content);

        if(!is_null($this->getCompilers())) {
            foreach($this->getCompilers() as $compiler) {
                copy($this->getDestinationMin(), $tempFilename);

                $this->getBundlerLogger()->logInfo($compiler->getCommand($tempFilename, $this->getDestinationMin()));
                $compiler->compile($tempFilename, $this->getDestinationMin());
            }
        }

        @unlink($tempFilename);

        $this->getBundlerLogger()->logDebug("created file: {$this->getDestinationMax()}");
        $this->getBundlerLogger()->logDebug("created file: {$this->getDestinationMin()}");

        $org = strlen(file_get_contents($this->getDestinationMax()));
        $new = strlen(file_get_contents($this->getDestinationMin()));
        $ratio = !empty($org) ? $new / $org : 0;

        $this->getBundlerLogger()->logDebug("org:   {$org} bytes");
        $this->getBundlerLogger()->logDebug("new:   {$new} bytes");
        $this->getBundlerLogger()->logDebug("ratio: {$ratio}");
    }
}