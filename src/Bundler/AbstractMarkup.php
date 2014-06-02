<?php
namespace Bundler;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AbstractMarkup
 *
 * @package Bundler
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractMarkup {

    /**
     * @var string
     */
    private $yaml;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $public;

    /**
     * @var string
     */
    private $minified;

    /**
     * @var boolean
     */
    private $development;

    /**
     * @param string $yaml
     */
    public function setYaml($yaml) {
        $this->yaml = $yaml;
    }

    /**
     * @return string
     */
    public function getYaml() {
        return $this->yaml;
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
     * @param string $public
     */
    public function setPublic($public) {
        $this->public = $public;
    }

    /**
     * @return string
     */
    public function getPublic() {
        return $this->public;
    }

    /**
     * @param string $minified
     */
    public function setMinified($minified) {
        $this->minified = $minified;
    }

    /**
     * @return string
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
     * @param string $package
     * @return string
     */
    public function get($package) {
        $files = ($this->getDevelopment()) ? $this->getFilesDevelopment($package) : $this->getFiles($package);
        $markup = $this->getMarkup($files);

        return implode(PHP_EOL, $markup) . PHP_EOL;
    }

    /**
     * @param $package
     * @return array
     * @throws Exception
     */
    private function getFiles($package) {
        $public = realpath($this->getPublic());

        if($public === false) {
            throw new Exception("public folder: {$this->getPublic()} not found.");
        }

        $file = require_once $public . '/' . $package . '.bundler.php';
        $file = $this->getMinified() ? $file['min'] : $file['max'];

        return array($file);
    }

    /**
     * @param string $package
     * @return array
     * @throws Exception
     */
    private function getFilesDevelopment($package) {
        $files = array();
        $manifest = realpath($this->getYaml());

        if($manifest === false) {
            throw new Exception("manifest file: {$this->getYaml()} not found.");
        }

        $manifest = Yaml::parse($manifest);

        if(!array_key_exists($package, $manifest['bundle'])) {
            throw new Exception("unknown package: {$package}");
        }

        foreach($manifest['bundle'][$package]['include'] as $file) {
            $files[] = trim(str_replace($manifest['folder'], null, $file), '/');
        }

        return $files;
    }

    /**
     * @param array $files
     * @return array
     */
    abstract protected function getMarkup(array $files);
}