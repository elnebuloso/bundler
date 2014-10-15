<?php
namespace Bundler\Package;

use Symfony\Component\Console\Output\OutputInterface;
use Zend\Log\LoggerInterface;

/**
 * Class PackageInterface
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface PackageInterface {

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