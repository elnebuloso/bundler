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
     * @return string
     */
    public function getName() {
        return 'File Bundler';
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