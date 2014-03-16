<?php
namespace Bundler\Command;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class AbstractCommand extends Command {

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
     * @var string
     */
    protected $_target;

    /**
     * @var array
     */
    protected $_filesSelected;

    /**
     * @param string $root
     */
    public function setRoot($root) {
        $this->_root = $root;
    }

    /**
     * @return void
     */
    protected function configure() {
        $this->addArgument('manifest', InputArgument::OPTIONAL, 'path to the manifest file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->_manifest = !is_null($input->getArgument('manifest')) ? $input->getArgument('manifest') : "$this->_root/.bundler/{$this->_manifest}";

        if(realpath($this->_root) === false) {
            throw new Exception("root folder: {$this->_root} not found.");
        }

        if(realpath($this->_manifest) === false) {
            throw new Exception("manifest file: {$this->_manifest} not found.");
        }

        $this->_output = $output;
        $this->_root = realpath($this->_root);
        $this->_manifest = realpath($this->_manifest);
        $this->_manifestDefinition = require_once $this->_manifest;

        $folder = "{$this->_root}/{$this->_manifestDefinition['folder']}";
        $target = "{$this->_root}/{$this->_manifestDefinition['target']}";

        if(realpath($folder) === false) {
            throw new Exception("folder: {$folder} not found.");
        }

        if(realpath($target) === false) {
            if(!file_exists($target)) {
                if(!mkdir($target, 0755, true)) {
                    throw new Exception("unable to create target: {$target}");
                }
            }
        }

        $this->_folder = realpath($folder);
        $this->_target = realpath($target);

        $this->_output->writeln("<comment>configuration</comment>");
        $this->_output->writeln("  <info>manifest: {$this->_manifest}</info>");
        $this->_output->writeln("  <info>root:     {$this->_root}</info>");
        $this->_output->writeln("  <info>folder:   {$this->_folder}</info>");
        $this->_output->writeln("  <info>target:   {$this->_target}</info>");
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        foreach($this->_manifestDefinition['bundle'] as $package => $definition) {
            $this->_output->writeln("");
            $this->_output->writeln("<comment>selecting: {$package}</comment>");

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
                $files = $this->selectFiles($this->_folder, '`' . $pattern . '`');
                $files = $this->updateFiles($this->_folder, $files);
                $includeFiles = array_merge($includeFiles, $files);
            }

            foreach($excludes as $pattern) {
                $files = $this->selectFiles($this->_folder, '`' . $pattern . '`');
                $files = $this->updateFiles($this->_folder, $files);
                $excludeFiles = array_merge($excludeFiles, $files);
            }

            if($this->_output->isVerbose()) {
                $this->_output->writeln("");

                foreach($includeFiles as $file) {
                    $this->_output->writeln("  <info>include: {$file}</info>");
                }

                foreach($excludeFiles as $file) {
                    $this->_output->writeln("  <info>exclude: {$file}</info>");
                }
            }

            $this->_filesSelected[$package] = array(
                'files' => $this->getFilesToBundle($includeFiles, $excludeFiles),
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
    private function selectFiles($folder, $pattern) {
        $this->_output->writeln("");
        $this->_output->writeln("  <info>folder:   {$folder}</info>");
        $this->_output->writeln("  <info>pattern:  {$pattern}</info>");

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
    private function updateFiles($folder, array $fileList) {
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
    private function getFilesToBundle(array $includeFiles, array $excludeFiles) {
        return array_diff($includeFiles, $excludeFiles);
    }
}