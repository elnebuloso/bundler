<?php
namespace Bundler;

use Bundler\Package\PackageInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Log\LoggerInterface;

/**
 * Class Bundler
 *
 * @package Bundler
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface BundlerInterface {

    /**
     * @param string $yaml
     * @param string $root
     */
    public function __construct($yaml, $root = null);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getYaml();

    /**
     * @return string
     */
    public function getRoot();

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger = null);

    /**
     * @return LoggerInterface
     */
    public function getLogger();

    /**
     * @param OutputInterface $output
     */
    public function setConsoleOutput($output = null);

    /**
     * @return OutputInterface
     */
    public function getConsoleOutput();

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
     * @param string $message
     */
    public function logInfo($message);

    /**
     * @param string $message
     */
    public function logDebug($message);

    /**
     * @return void
     */
    public function bundle();
}