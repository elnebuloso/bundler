<?php
namespace Bundler;

use Bundler\Package\PackageInterface;

/**
 * Class Bundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface BundlerInterface {

    /**
     * @param string $file
     * @param string $root
     */
    public function __construct($file, $root = null);

    /**
     * @return BundlerLogger
     */
    public function getBundlerLogger();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getFile();

    /**
     * @return string
     */
    public function getRoot();

    /**
     * @param PackageInterface $package
     */
    public function addPackage(PackageInterface $package);

    /**
     * @return PackageInterface[]
     */
    public function getPackages();

    /**
     * @param string $name
     * @return PackageInterface|null
     */
    public function getPackageByName($name);

    /**
     * @return void
     */
    public function bundle();
}