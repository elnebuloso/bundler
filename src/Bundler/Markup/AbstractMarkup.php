<?php
namespace Bundler\Markup;

use Bundler\JavascriptBundler;
use Bundler\StylesheetBundler;

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
     * @var boolean
     */
    private $minified;

    /**
     * @var boolean
     */
    private $development;

    /**
     * @var boolean
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
     * @param boolean $minified
     */
    public function setMinified($minified) {
        $this->minified = $minified;
    }

    /**
     * @return boolean
     */
    public function getMinified() {
        return $this->minified;
    }

    /**
     * @param boolean $development
     */
    public function setDevelopment($development) {
        $this->development = $development;
    }

    /**
     * @return boolean
     */
    public function getDevelopment() {
        return $this->development;
    }

    /**
     * @param boolean $versionized
     */
    public function setVersionized($versionized) {
        $this->versionized = $versionized;
    }

    /**
     * @return boolean
     */
    public function getVersionized() {
        return $this->versionized;
    }

    /**
     * @param $package
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
     * @param $package
     * @return array
     * @throws MarkupException
     */
    private function getFilesCached($package) {
        $cacheFilename = null;

        if($this instanceof JavascriptMarkup) {
            $cacheFilename = $this->getBundlerDirectory() . '/javascript.php';
        }

        if($this instanceof StylesheetMarkup) {
            $cacheFilename = $this->getBundlerDirectory() . '/stylesheet.php';
        }

        if(is_null($cacheFilename)) {
            throw new MarkupException('missing cache file', 4000);
        }

        $cache = include $cacheFilename;

        if(!array_key_exists($package, $cache)) {
            throw new MarkupException('missing package in cache file', 4001);
        }

        $type = $this->getMinified() ? "min" : "max";
        $filename = $this->getHost() . $cache[$package][$type];

        if($this->getVersionized()) {
            $filename .= 'v=' . $cache[$package]['md5'];
        }

        return array($filename);
    }

    /**
     * @param $package
     * @return array
     * @throws MarkupException
     */
    private function getFilesDevelopment($package) {
        $bundler = null;

        if($this instanceof JavascriptMarkup) {
            $yaml = $this->getBundlerDirectory() . '/javascript.yaml';
            $bundler = new JavascriptBundler($yaml);
        }

        if($this instanceof StylesheetMarkup) {
            $yaml = $this->getBundlerDirectory() . '/stylesheet.yaml';
            $bundler = new StylesheetBundler($yaml);
        }

        if(is_null($bundler)) {
            throw new MarkupException('missing bundler definition');
        }

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
     * @param $packageName
     * @return string
     */
    abstract public function getMarkup($packageName);
}