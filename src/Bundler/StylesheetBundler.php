<?php
namespace Bundler;

use Bundler\Compiler\Compiler;
use Bundler\Package\PackageInterface;
use Bundler\Package\StylesheetPackage;

/**
 * Class StylesheetBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetBundler extends AbstractBundler
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'Stylesheet Bundler';
    }

    /**
     * @param string $name
     * @param string $root
     * @param array $configuration
     * @return PackageInterface
     */
    protected function createPackage($name, $root, array $configuration)
    {
        $package = new StylesheetPackage();
        $package->setName($name);
        $package->setRoot($root . DIRECTORY_SEPARATOR . $configuration['public']);
        $package->setTarget($configuration['target']);
        $package->setPublic($configuration['public']);
        $package->setIncludes($configuration['include']);
        $package->setBundlerLogger($this->getBundlerLogger());

        foreach ($configuration['compilers'] as $command) {
            $package->addCompiler(new Compiler($command));
        }

        return $package;
    }

    /**
     * @return void
     */
    protected function preBundle()
    {
        // intentionally left blank
    }

    /**
     * @return void
     */
    protected function postBundle()
    {
        $cache = array();
        $cacheFilename = dirname($this->getFile()) . '/stylesheet.cache.php';

        $this->getBundlerLogger()->logDebug("creating cache file: " . $cacheFilename);

        foreach ($this->getPackages() as $package) {
            /** @var StylesheetPackage $package */
            $cache[$package->getName()] = array(
                'md5' => md5(file_get_contents($package->getDestinationMin())),
                'max' => $package->getTarget() . '/' . $package->getFilenameMaxFile(),
                'min' => $package->getTarget() . '/' . $package->getFilenameMinFile()
            );
        }

        $content = array(
            '<?php',
            '# this file is auto-generated by bundler ' . date('Y-m-d H:i:s'),
            'return ' . var_export($cache, true) . ';'
        );

        file_put_contents($cacheFilename, implode(PHP_EOL, $content));
    }
}
