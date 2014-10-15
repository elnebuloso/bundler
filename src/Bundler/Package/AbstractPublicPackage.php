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
}