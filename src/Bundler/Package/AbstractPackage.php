<?php
namespace Bundler\Package;

use Bundler\FileSystem\FileSelector;
use Exception;

/**
 * Class AbstractPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractPackage implements Package {

    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $target;

    /**
     * @var array
     */
    private $includes;

    /**
     * @var array
     */
    private $excludes;

    /**
     * @var
     */
    private $fileSelector;

    /**
     * @param string $root
     * @param string $name
     * @throws Exception
     */
    public function __construct($root, $name) {
        $this->root = realpath($root);
        $this->name = trim($name);

        if($this->root === false) {
            throw new Exception('invalid root path: ' . $this->root);
        }

        if(empty($this->name)) {
            throw new Exception('the package name cannot be empty');
        }

        $this->fileSelector = new FileSelector();
    }

    /**
     * @return string
     */
    public function getRoot() {
        return $this->root;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $target
     */
    public function setTarget($target) {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     * @param array $includes
     */
    public function setIncludes($includes) {
        $this->includes = $includes;
    }

    /**
     * @return array
     */
    public function getIncludes() {
        return $this->includes;
    }

    /**
     * @param array $excludes
     */
    public function setExcludes($excludes) {
        $this->excludes = $excludes;
    }

    /**
     * @return array
     */
    public function getExcludes() {
        return $this->excludes;
    }

    /**
     * @return FileSelector
     */
    public function getFileSelector() {
        return $this->fileSelector;
    }

    /**
     * @return void
     */
    public function selectFiles() {
        $this->fileSelector = new FileSelector();
        $this->fileSelector->setDir($this->root);
        $this->fileSelector->setIncludes($this->getIncludes());
        $this->fileSelector->setExcludes($this->getExcludes());
        $this->fileSelector->select();
    }
}