<?php
namespace Bundler\Markup;

use Bundler\JavascriptBundler;
use Bundler\StylesheetBundler;
use Exception;

/**
 * Class AbstractMarkup
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractMarkup implements Markup {

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
     * @return AbstractMarkup
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
     * @return Markup
     */
    public function setMinified($minified) {
        $this->minified = $minified;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getMinified() {
        return $this->minified;
    }

    /**
     * @param boolean $development
     * @return Markup
     */
    public function setDevelopment($development) {
        $this->development = $development;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getDevelopment() {
        return $this->development;
    }

    /**
     * @param boolean $versionized
     * @return Markup
     */
    public function setVersionized($versionized) {
        $this->versionized = $versionized;

        return $this;
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
     * @throws Exception
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
            throw new Exception('missing cache file');
        }

        $cache = include $cacheFilename;

        if(!array_key_exists($package, $cache)) {
            throw new Exception('missing package in cache file');
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
     * @throws Exception
     */
    private function getFilesDevelopment($package) {
        $bundler = null;

        if($this instanceof JavascriptMarkup) {
            $yaml = $this->getBundlerDirectory() . '/javascript.yaml';
            $bundler = new JavascriptBundler(dirname($this->getBundlerDirectory()), $yaml);
        }

        if($this instanceof StylesheetMarkup) {
            $yaml = $this->getBundlerDirectory() . '/stylesheet.yaml';
            $bundler = new StylesheetBundler(dirname($this->getBundlerDirectory()), $yaml);
        }

        if(is_null($bundler)) {
            throw new Exception('missing bundler definition');
        }

        $package = $bundler->getPackageByName($package);

        if(is_null($package)) {
            throw new Exception('missing package definition');
        }

        $files = array();

        foreach($package->getIncludes() as $file) {
            $filename = rtrim($this->getHost() . $file, '$');
            $files[] = $filename;
        }

        return $files;
    }
}