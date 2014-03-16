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
     * @var string
     */
    protected $_root;

    /**
     * @var string
     */
    protected $_manifest;

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

        if(realpath($this->_manifest) === false) {
            throw new Exception("manifest file {$this->_manifest} not found.");
        }

        $this->_manifest = realpath($this->_manifest);

        $output->writeln("  <info>manifest: {$this->_manifest}</info>");
    }
}