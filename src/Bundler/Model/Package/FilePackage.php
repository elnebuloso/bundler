<?php
namespace Bundler\Model\Package;

/**
 * Class FilePackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FilePackage implements Package {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $version;

    /**
     * @var array
     */
    private $includes;

    /**
     * @var array
     */
    private $excludes;

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
     * @param string $version
     */
    public function setVersion($version) {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion() {
        return $this->version;
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

    /**
     * @param array $excludes
     */
    public function setExcludes(array $excludes) {
        $this->excludes = $excludes;
    }

    /**
     * @return array
     */
    public function getExcludes() {
        return $this->excludes;
    }

    /**
     * @param string $name
     * @param array $array
     * @return FilePackage
     */
    public static function createFromArray($name, array $array) {
        $package = new self($name);
        $package->setTo($array['to']);
        $package->setVersion($array['version']);
        $package->setIncludes($array['include']);
        $package->setExcludes($array['exclude']);

        return $package;
    }
}