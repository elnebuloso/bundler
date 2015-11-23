<?php
namespace Bundler;

use Bundler\Package\PackageInterface;

/**
 * Class Bundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface BundlerInterface
{
    /**
     * @param string $file
     * @param string $root
     */
    public function __construct($file, $root = null);

    /**
     * @param BundlerLogger $bundlerLogger
     */
    public function setBundlerLogger(BundlerLogger $bundlerLogger);

    /**
     * @return BundlerLogger
     */
    public function getBundlerLogger();

    /**
     * @param $file
     */
    public function setFile($file);

    /**
     * @return string
     */
    public function getFile();

    /**
     * @param $root
     */
    public function setRoot($root);

    /**
     * @return string
     */
    public function getRoot();

    /**
     * @param PackageInterface $package
     */
    public function addPackage(PackageInterface $package);

    /**
     * @param string $name
     * @return PackageInterface|null
     */
    public function getPackageByName($name);

    /**
     * @return PackageInterface[]
     */
    public function getPackages();

    /**
     * @return void
     */
    public function configure();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return void
     */
    public function bundle();
}
