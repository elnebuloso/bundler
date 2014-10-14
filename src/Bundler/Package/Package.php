<?php
namespace Bundler\Package;

/**
 * Class PackageInterface
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface Package {

    /**
     * @return string
     */
    public function getRoot();

    /**
     * @return string
     */
    public function getName();

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
     * @return array
     */
    public function getIncludes();
}