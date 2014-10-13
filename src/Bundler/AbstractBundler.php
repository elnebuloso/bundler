<?php
namespace Bundler;

use Bundler\Package\Package;
use Exception;

/**
 * Class AbstractBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class AbstractBundler {

    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $yaml;

    /**
     * @var Package[]
     */
    private $packages;

    /**
     * @param string $root
     * @param string $yaml
     * @throws Exception
     */
    function __construct($root, $yaml) {
        $this->root = realpath($root);
        $this->yaml = realpath($yaml);

        if($this->root === false) {
            throw new Exception('invalid root path: ' . $this->root);
        }

        if($this->yaml === false) {
            throw new Exception('invalid yaml path: ' . $this->yaml);
        }
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
    public function getYaml() {
        return $this->yaml;
    }

    /**
     * @param Package[] $packages
     */
    public function setPackages($packages) {
        $this->packages = $packages;
    }

    /**
     * @return Package[]
     */
    public function getPackages() {
        return $this->packages;
    }

    /**
     * @param Package $package
     */
    public function addPackage(Package $package) {
        $this->packages[$package->getName()] = $package;
    }

    /**
     * @param string $name
     */
    public function removePackage($name) {
        unset($this->packages[$name]);
    }

    /**
     * @param string $name
     * @return Package|null
     */
    public function getPackageByName($name) {
        return array_key_exists($name, $this->packages) ? $this->packages[$name] : null;
    }
}