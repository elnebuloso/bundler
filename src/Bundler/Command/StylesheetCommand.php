<?php
namespace Bundler\Command;

use Bundler\Task\StylesheetTask;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StylesheetCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetCommand extends Command {

    /**
     * @var string
     */
    private $_root;

    /**
     * @var string
     */
    private $_compressor;

    /**
     * @var array
     */
    private $_compressors = array(
        'yuicompressor',
        'cssmin'
    );

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
        $this->setName('bundle:stylesheet')->setDescription('bundling stylesheets')->addArgument('compressor', InputArgument::OPTIONAL, implode('|', $this->_compressors));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<comment>bundling stylesheets</comment>");

        $this->_compressor = !is_null($input->getArgument('compressor')) ? $input->getArgument('compressor') : 'yuicompressor';

        if(!in_array($this->_compressor, $this->_compressors)) {
            $output->writeln("  <error>invalid compressor: {$this->_compressor}</error>");
            $output->writeln("");

            return;
        }

        $output->writeln("  <info>compressor: {$this->_compressor}</info>");
        $output->writeln("");

        try {
            $task = new StylesheetTask($output);
            $task->setRoot($this->_root);
            $task->setManifest("$this->_root/.bundler/stylesheet.php");
            $task->bundle();
        }
        catch(Exception $e) {
            $output->writeln("  <error>{$e->getMessage()}</error>");
            $output->writeln("");
        }
    }
}