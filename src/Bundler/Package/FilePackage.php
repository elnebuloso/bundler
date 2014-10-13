<?php
namespace Bundler\Package;

use Exception;

/**
 * Class FilePackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FilePackage extends AbstractPackage {

    /**
     * @var string
     */
    const VERSION_TYPE_DATETIME = 'datetime';

    /**
     * @var string
     */
    const VERSION_TYPE_FILE = 'file';

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

    /**
     * @return string
     * @throws Exception
     */
    public function getTargetDirectory() {
        if(realpath($this->getTarget()) === false) {
            throw new Exception('wrong target directory: ' . $this->getTarget());
        }

        $targetDirectory[] = rtrim(realpath($this->getTarget()), '/');

        switch($this->getVersion()) {
            case self::VERSION_TYPE_DATETIME:
                $targetDirectory[] = date('YmdHis');
                break;

            case self::VERSION_TYPE_FILE:
                if(($version = file_get_contents($this->getRoot() . '/VERSION'))) {
                    $targetDirectory[] = trim($version);
                }
        }

        $targetDirectory[] = $this->getName();

        return implode('/', $targetDirectory);
    }
}