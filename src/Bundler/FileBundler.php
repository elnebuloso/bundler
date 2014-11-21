<?php
namespace Bundler;

use Bundler\Config\FileConfig;
use Bundler\Package\FilePackage;
use Bundler\Package\PackageInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class FileBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileBundler extends AbstractBundler {

    /**
     * @var string
     */
    private $version;

    /**
     * @return string
     */
    public function getName() {
        return 'File Bundler';
    }

    /**
     * @param string $version
     * @throws BundlerException
     */
    public function setVersion($version) {
        $this->version = trim($version);

        if(empty($this->version)) {
            throw new BundlerException('empty version set');
        }
    }

    /**
     * @return ConfigurationInterface
     */
    protected function getConfiguration() {
        return new FileConfig();
    }

    /**
     * @param string $name
     * @param string $root
     * @param array $configuration
     * @return PackageInterface
     */
    protected function createPackage($name, $root, array $configuration) {
        $package = new FilePackage();
        $package->setName($name);
        $package->setRoot($root);
        $package->setTarget($configuration['target']);
        $package->setIncludes($configuration['include']);
        $package->setExcludes($configuration['exclude']);
        $package->setVersion($configuration['version']);
        $package->setBundlerLogger($this->getBundlerLogger());

        // do we have a fixed version set, override the version from the files.yaml
        if(!empty($this->version)) {
            $package->setVersion($this->version);
        }

        return $package;
    }

    /**
     * @return void
     */
    protected function preBundle() {
        // intentionally left blank
    }

    /**
     * @return void
     */
    protected function postBundle() {
        // intentionally left blank
    }
}