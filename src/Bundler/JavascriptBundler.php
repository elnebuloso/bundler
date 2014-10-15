<?php
namespace Bundler;

use Bundler\Package\JavascriptPackage;

/**
 * Class JavascriptBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptBundler extends AbstractBundler implements Bundler {

    /**
     * @var string
     */
    protected $title = 'bundling javascript ...';

    /**
     * @var string
     */
    protected $type = self::TYPE_JAVASCRIPT;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @var string
     */
    protected $cacheFilename;

    /**
     * @return void
     */
    protected function postBundle() {
        $this->cache = array();
        $this->cacheFilename = dirname($this->getYaml()) . '/javascript.php';

        $this->logDebug("creating cache manifest: " . $this->cacheFilename);

        foreach($this->getPackages() as $package) {
            /** @var JavascriptPackage $package */
            $this->cache[$package->getName()] = array(
                'md5' => md5(file_get_contents($package->getDestinationMin())),
                'max' => $package->getTarget() . '/' . $package->getFilenameMaxFile(),
                'min' => $package->getTarget() . '/' . $package->getFilenameMinFile()
            );
        }

        file_put_contents($this->cacheFilename, '<?php return ' . var_export($this->cache, true) . ";" . PHP_EOL);
    }
}