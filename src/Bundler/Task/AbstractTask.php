<?php
namespace Bundler\Task;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
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
     * @var string
     */
    protected $_folder;

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

        $this->_manifestDefinition = require_once($this->_manifest);
        $this->_folder = realpath("{$this->_root}/{$this->_manifestDefinition['folder']}");

        if($this->_root === false) {
            throw new Exception("Folder {$this->_folder} not found.");
        }

        $this->_output->writeln("<comment>configuration</comment>");
        $this->_output->writeln("  <info>manifest: {$this->_manifest}</info>");
        $this->_output->writeln("  <info>root:     {$this->_root}</info>");
        $this->_output->writeln("  <info>folder:   {$this->_folder}</info>");

        $this->_readManifest();
    }

    /**
     * @return void
     */
    private function _readManifest() {
        foreach($this->_manifestDefinition['bundle'] as $package => $definition) {
            $this->_output->writeln("");
            $this->_output->writeln("<comment>package: {$package}</comment>");

            $includes = array();
            $excludes = array();

            if(array_key_exists('include', $definition) && is_array($definition['include'])) {
                $includes = $definition['include'];
            }

            if(array_key_exists('exclude', $definition) && is_array($definition['exclude'])) {
                $excludes = $definition['exclude'];
            }

            $includeFiles = array();
            $excludeFiles = array();

            foreach($includes as $pattern) {
                $files = $this->_selectFiles($this->_folder, '`' . $pattern . '`');
                $files = $this->_updateFiles($this->_folder, $files);
                $includeFiles = array_merge($includeFiles, $files);
            }

            foreach($excludes as $pattern) {
                $files = $this->_selectFiles($this->_folder, '`' . $pattern . '`');
                $files = $this->_updateFiles($this->_folder, $files);
                $excludeFiles = array_merge($excludeFiles, $files);
            }

            foreach($includeFiles as $file) {
                $this->_output->writeln("  <info>include: {$file}</info>");
            }

            foreach($excludeFiles as $file) {
                $this->_output->writeln("  <info>exclude: {$file}</info>");
            }

            $this->_filesSelected[$package] = array(
                'files' => $this->_getFilesToUse($includeFiles, $excludeFiles),
                'includes' => $includeFiles,
                'excludes' => $excludeFiles
            );
        }
    }

    /**
     * @param string $folder
     * @param string $pattern
     * @return array
     */
    private function _selectFiles($folder, $pattern) {
        $dir = new RecursiveDirectoryIterator($folder);
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
        $fileList = array();

        foreach($files as $file) {
            $fileList = array_merge($fileList, $file);
        }

        return $fileList;
    }

    /**
     * @param string $folder
     * @param array $fileList
     * @return array
     */
    private function _updateFiles($folder, array $fileList) {
        $returnFiles = array();

        foreach($fileList as $currentFile) {
            $currentFile = realpath($folder . '/' . $currentFile);

            if(empty($currentFile) || is_dir($currentFile)) {
                continue;
            }

            $returnFiles[md5($currentFile)] = $currentFile;
        }

        return $returnFiles;
    }

    /**
     * @param array $includeFiles
     * @param array $excludeFiles
     * @return array
     */
    private function _getFilesToUse(array $includeFiles, array $excludeFiles) {
        return array_diff($includeFiles, $excludeFiles);
    }
}