<?php
namespace Bundler\Package;

use Bundler\FileSystem\FileSelector;

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
     * @return FileSelector
     */
    public function getFileSelector();

    /**
     * @return void
     */
    public function selectFiles();
}