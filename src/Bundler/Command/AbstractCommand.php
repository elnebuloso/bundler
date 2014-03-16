<?php
namespace Bundler\Command;

use Exception;
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
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        $this->_manifestDefinition = require_once $this->_manifest;

        $folder = "{$this->_root}/{$this->_manifestDefinition['folder']}";
        $target = "{$this->_root}/{$this->_manifestDefinition['target']}";

        if(realpath($folder) === false) {
            throw new Exception("folder: {$folder} not found.");
        }

        if(realpath($target) === false) {
            throw new Exception("target: {$target} not found.");
        }

        $this->_folder = realpath($folder);
        $this->_target = realpath($target);

        $this->_output->writeln("<comment>configuration</comment>");
        $this->_output->writeln("  <info>manifest: {$this->_manifest}</info>");
        $this->_output->writeln("  <info>root:     {$this->_root}</info>");
        $this->_output->writeln("  <info>folder:   {$this->_folder}</info>");
        $this->_output->writeln("  <info>target:   {$this->_target}</info>");
    }
}