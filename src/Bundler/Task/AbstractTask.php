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
    protected $_folder;

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
     * @param string $folder
     */
    public function setFolder($folder) {
        $this->_folder = $folder;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function bundle() {
        $this->_manifest = realpath($this->_manifest);
        $this->_folder = realpath($this->_folder);

        if($this->_manifest === false) {
            throw new Exception("Manifest file {$this->_manifest} not found.");
        }

        if($this->_folder === false) {
            throw new Exception("Directory {$this->_folder} not found.");
        }
    }
}