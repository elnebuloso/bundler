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
     * @var string
     */
    private $targetDirectory;

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
        if(!is_null($this->targetDirectory)) {
            return $this->targetDirectory;
        }

        if(realpath($this->getTarget()) === false) {
            $this->targetDirectory = null;
            throw new Exception('wrong target directory: ' . $this->getTarget());
        }

        $this->targetDirectory[] = rtrim(realpath($this->getTarget()), '/');

        switch($this->getVersion()) {
            case self::VERSION_TYPE_DATETIME:
                $this->targetDirectory[] = date('YmdHis');
                break;

            case self::VERSION_TYPE_FILE:
                if(($version = file_get_contents($this->getRoot() . '/VERSION'))) {
                    $this->targetDirectory[] = trim($version);
                }
        }

        $this->targetDirectory[] = $this->getName();
        $this->targetDirectory = implode('/', $this->targetDirectory);

        return $this->targetDirectory;
    }

    /**
     * @param string $sourceFilePath
     * @return string
     */
    public function getDestinationFilePath($sourceFilePath) {
        return $this->getTargetDirectory() . '/' . str_replace($this->getRoot() . '/', '', $sourceFilePath);
    }
}