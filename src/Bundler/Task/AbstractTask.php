<?php
namespace Bundler\Task;

use Exception;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractTask
 *
 * @package Bundler\Task
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractTask {

    /**
     * @var OutputInterface
     */
    protected $_output;

    /**
     * @var string
     */
    protected $_root;

    /**
     * @var string
     */
    protected $_manifest;

    /**
     * @var array
     */
    protected $_manifestDefinition;

    /**
     * @var array
     */
    protected $_filesSelected;

    /**
     * @param OutputInterface $output
     * @return AbstractTask
     */
    public function __construct(OutputInterface $output) {
        $this->_output = $output;
    }

    /**
     * @param string $manifest
     */
    public function setManifest($manifest) {
        $this->_manifest = $manifest;
    }

    /**
     * @param string $root
     */
    public function setRoot($root) {
        $this->_root = $root;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function bundle() {
        $this->_manifest = realpath($this->_manifest);
        $this->_root = realpath($this->_root);

        if($this->_manifest === false) {
            throw new Exception("Manifest file {$this->_manifest} not found.");
        }

        if($this->_root === false) {
            throw new Exception("Root {$this->_root} not found.");
        }

        $this->_output->writeln("<comment>configuration</comment>");
        $this->_output->writeln("  <info>manifest: {$this->_manifest}</info>");
        $this->_output->writeln("  <info>root:     {$this->_root}</info>");
    }
}