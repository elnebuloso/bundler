<?php
namespace Bundler;

use Bundler\Compiler\Compiler;
use Bundler\Config\JavascriptConfig;
use Bundler\Package\JavascriptPackage;
use Bundler\Package\PackageInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class JavascriptBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptBundler extends AbstractBundler {

    /**
     * @return string
     */
    public function getName() {
        return 'Javascript Bundler';
    }

    /**
     * @return ConfigurationInterface
     */
    protected function getConfiguration() {
        return new JavascriptConfig();
    }

    /**
     * @param string $name
     * @param string $root
     * @param array $configuration
     * @return PackageInterface
     */
    protected function createPackage($name, $root, array $configuration) {
        $package = new JavascriptPackage();
        $package->setName($name);
        $package->setRoot($root . DIRECTORY_SEPARATOR . $configuration['public']);
        $package->setTarget($configuration['target']);
        $package->setPublic($configuration['public']);
        $package->setIncludes($configuration['include']);
        $package->setLogger($this->getLogger());
        $package->setConsoleOutput($this->getConsoleOutput());

        foreach($configuration['compilers'] as $command) {
            $package->addCompiler(new Compiler($command));
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
        $cache = array();
        $cacheFilename = dirname($this->getYaml()) . '/javascript.php';

        $this->logDebug("creating cache file: " . $cacheFilename);

        foreach($this->getPackages() as $package) {
            /** @var JavascriptPackage $package */
            $cache[$package->getName()] = array(
                'md5' => md5(file_get_contents($package->getDestinationMin())),
                'max' => $package->getTarget() . '/' . $package->getFilenameMaxFile(),
                'min' => $package->getTarget() . '/' . $package->getFilenameMinFile()
            );
        }

        file_put_contents($cacheFilename, '<?php return ' . var_export($cache, true) . ";" . PHP_EOL);
    }
}