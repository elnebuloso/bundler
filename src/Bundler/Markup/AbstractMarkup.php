<?php
namespace Bundler\Markup;

use Bundler\BundlerInterface;

/**
 * Class AbstractMarkup
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractMarkup {

    /**
     * @var string
     */
    private $bundlerDirectory;

    /**
     * @var string
     */
    private $host;

    /**
     * @var bool
     */
    private $minified;

    /**
     * @var bool
     */
    private $development;

    /**
     * @var bool
     */
    private $versionized;

    /**
     * @return self
     */
    public function __construct() {
        $this->setBundlerDirectory('./.bundler');
        $this->setHost('/');
        $this->setMinified(false);
        $this->setDevelopment(false);
        $this->setVersionized(true);
    }

    /**
     * @param string $bundlerDirectory
     */
    public function setBundlerDirectory($bundlerDirectory) {
        $this->bundlerDirectory = $bundlerDirectory;
    }

    /**
     * @return string
     */
    public function getBundlerDirectory() {
        return $this->bundlerDirectory;
    }

    /**
     * @param string $host
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @param bool $minified
     */
    public function setMinified($minified) {
        $this->minified = $minified;
    }

    /**
     * @return bool
     */
    public function getMinified() {
        return $this->minified;
    }

    /**
     * @param bool $development
     */
    public function setDevelopment($development) {
        $this->development = $development;
    }

    /**
     * @return bool
     */
    public function getDevelopment() {
        return $this->development;
    }

    /**
     * @param bool $versionized
     */
    public function setVersionized($versionized) {
        $this->versionized = $versionized;
    }

    /**
     * @return bool
     */
    public function getVersionized() {
        return $this->versionized;
    }

    /**
     * @param string $package
     * @return array
     */
    protected function getFiles($package) {
        if($this->getDevelopment()) {
            return $this->getFilesDevelopment($package);
        }
        else {
            return $this->getFilesCached($package);
        }
    }

    /**
     * @param string $package
     * @return array
     * @throws MarkupException
     */
    protected function getFilesCached($package) {
        $cache = include $this->getCacheFilename();

        if(!array_key_exists($package, $cache)) {
            throw new MarkupException('missing package in cache file', 4001);
        }

        $type = $this->getMinified() ? "min" : "max";
        $filename = $this->getHost() . $cache[$package][$type];

        if($this->getVersionized()) {
            $filename .= '?v=' . $cache[$package]['md5'];
        }

        return array($filename);
    }

    /**
     * @param string $package
     * @return array
     * @throws MarkupException
     */
    protected function getFilesDevelopment($package) {
        $bundler = $this->getBundler();
        $bundler->configure();

        $package = $bundler->getPackageByName($package);

        if(is_null($package)) {
            throw new MarkupException('missing package definition');
        }

        $files = array();

        foreach($package->getIncludes() as $file) {
            $filename = rtrim($this->getHost() . $file, '$');
            $files[] = $filename;
        }

        return $files;
    }

    /**
     * @return BundlerInterface
     */
    abstract protected function getBundler();

    /**
     * @return string
     */
    abstract protected function getCacheFilename();

    /**
     * @param $packageName
     * @return string
     */
    abstract public function getMarkup($packageName);
}