<?php
namespace Bundler\Package;

/**
 * Class AbstractPublicPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractPublicPackage {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $public;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $compiler;

    /**
     * @var array
     */
    private $includes;

    /**
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

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
     * @param string $to
     */
    public function setTo($to) {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getTo() {
        return $this->to;
    }

    /**
     * @param string $compiler
     */
    public function setCompiler($compiler) {
        $this->compiler = $compiler;
    }

    /**
     * @return string
     */
    public function getCompiler() {
        return $this->compiler;
    }

    /**
     * @param array $includes
     */
    public function setIncludes(array $includes) {
        $this->includes = $includes;
    }

    /**
     * @return array
     */
    public function getIncludes() {
        return $this->includes;
    }
}