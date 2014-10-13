<?php
namespace Bundler\Package;

/**
 * Class FilePackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FilePackage extends AbstractPackage {

    /**
     * @var string
     */
    private $version;

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
     * @param string $root
     * @param string $name
     * @param array $array
     * @return FilePackage
     */
    public static function createFromArray($root, $name, array $array) {
        $package = new self($root, $name);
        $package->setTarget($array['target']);
        $package->setVersion($array['version']);
        $package->setIncludes($array['include']);
        $package->setExcludes($array['exclude']);

        return $package;
    }
}