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
     * @var Compiler
     */
    private $compiler;

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
    public function setCompiler($compiler) {
        $this->compiler = $compiler;
    }

    /**
     * @return Compiler
     */
    public function getCompiler() {
        return $this->compiler;
    }

    /**
     * @return string
     */
    public function getDestinationMax() {
        return "{$this->getRoot()}/{$this->getPublic()}/{$this->getTarget()}/{$this->getFilenameMaxFile()}";
    }

    /**
     * @return string
     */
    public function getDestinationMin() {
        return "{$this->getRoot()}/{$this->getPublic()}/{$this->getTarget()}/{$this->getFilenameMinFile()}";
    }

    /**
     * @return string
     */
    abstract public function getFilenameMaxFile();

    /**
     * @return string
     */
    abstract public function getFilenameMinFile();
}