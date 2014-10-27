<?php
namespace Bundler\Package;

use Bundler\BundlerLogger;

/**
 * Class PackageInterface
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface PackageInterface {

    /**
     * @return BundlerLogger
     */
    public function getBundlerLogger();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $root
     */
    public function setRoot($root);

    /**
     * @return string
     */
    public function getRoot();

    /**
     * @param string $target
     */
    public function setTarget($target);

    /**
     * @return string
     */
    public function getTarget();

    /**
     * @param array $includes
     */
    public function setIncludes(array $includes);

    /**
     * @return array
     */
    public function getIncludes();

    /**
     * @param array $excludes
     */
    public function setExcludes(array $excludes);

    /**
     * @return array
     */
    public function getExcludes();

    /**
     * @return void
     */
    public function selectFiles();

    /**
     * @return array
     */
    public function getSelectedFiles();

    /**
     * @return int
     */
    public function getSelectedFilesCount();

    /**
     * @return void
     */
    public function bundle();
}