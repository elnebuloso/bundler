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
    protected $content;

    /**
     * @var string
     */
    protected $destinationMax;

    /**
     * @var string
     */
    protected $destinationMin;

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
     * @return void
     */
    protected function bundlePackage() {
        $this->content = array();
        $this->destinationMax = $this->getDestinationMax();
        $this->destinationMin = $this->getDestinationMin();

        $this->compress();
    }

    /**
     * @return void
     */
    protected function createCompressedFiles() {
        // create max file
        $this->content = implode(PHP_EOL . PHP_EOL, $this->content);
        file_put_contents($this->destinationMax, $this->content);
        file_put_contents($this->destinationMin, $this->content);

        foreach($this->getCompilers() as $compiler) {
            copy($this->destinationMin, $this->destinationMin . '.tmp');
            $compiler->compile($this->destinationMin . '.tmp', $this->destinationMin);
        }

        unlink($this->destinationMin . '.tmp');

        $this->logDebug("created file: {$this->destinationMax}");
        $this->logDebug("created file: {$this->destinationMin}");

        $org = strlen(file_get_contents($this->destinationMax));
        $new = strlen(file_get_contents($this->destinationMin));
        $ratio = !empty($org) ? $new / $org : 0;
        $this->logDebug("org:   {$org} bytes");
        $this->logDebug("new:   {$new} bytes");
        $this->logDebug("ratio: {$ratio}");
    }

    /**
     * @return string
     */
    abstract protected function getFilenameMaxFile();

    /**
     * @return string
     */
    abstract protected function getFilenameMinFile();

    /**
     * @return void
     */
    abstract protected function compress();
}