<?php
namespace Bundler;

use Bundler\Compiler\CompilerFactory;
use Bundler\Config\StylesheetConfig;
use Bundler\Package\PackageInterface;
use Bundler\Package\StylesheetPackage;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class StylesheetBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetBundler extends AbstractBundler {

    /**
     * @return string
     */
    public function getName() {
        return 'Stylesheet Bundler';
    }

    /**
     * @return ConfigurationInterface
     */
    protected function getConfiguration() {
        return new StylesheetConfig();
    }

    /**
     * @param string $name
     * @param string $root
     * @param array $configuration
     * @return PackageInterface
     */
    protected function createPackage($name, $root, array $configuration) {
        $package = new StylesheetPackage();
        $package->setName($name);
        $package->setRoot($root . DIRECTORY_SEPARATOR . $configuration['public']);
        $package->setTarget($configuration['target']);
        $package->setPublic($configuration['public']);
        $package->setIncludes($configuration['include']);
        $package->setLogger($this->getLogger());
        $package->setConsoleOutput($this->getConsoleOutput());

        foreach($configuration['compilers'] as $compilerType => $compilerConfig) {
            $package->addCompiler(CompilerFactory::create($compilerType, $compilerConfig));
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
        $cacheFilename = dirname($this->getYaml()) . '/stylesheet.php';

        $this->logDebug("creating cache file: " . $cacheFilename);

        foreach($this->getPackages() as $package) {
            /** @var StylesheetPackage $package */
            $cache[$package->getName()] = array(
                'md5' => md5(file_get_contents($package->getDestinationMin())),
                'max' => $package->getTarget() . '/' . $package->getFilenameMaxFile(),
                'min' => $package->getTarget() . '/' . $package->getFilenameMinFile()
            );
        }

        file_put_contents($cacheFilename, '<?php return ' . var_export($cache, true) . ";" . PHP_EOL);
    }
}