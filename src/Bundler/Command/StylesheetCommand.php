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
    private $_compressor = 'yuicompressor';

    /**
     * @var array
     */
    private $_compressors = array(
        'yuicompressor',
        'cssmin'
    );

    /**
     * @var string
     */
    protected $_java = 'java';

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
        $this->setName('bundle:stylesheet');
        $this->setDescription('bundling stylesheets');
        $this->addArgument('compressor', InputArgument::OPTIONAL, implode('|', $this->_compressors));
        $this->addArgument('java', InputArgument::OPTIONAL, 'java binary call');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<comment>bundling stylesheets</comment>");

        $this->_compressor = !is_null($input->getArgument('compressor')) ? $input->getArgument('compressor') : $this->_compressor;
        $this->_java = !is_null($input->getArgument('java')) ? $input->getArgument('java') : $this->_java;

        if(!in_array($this->_compressor, $this->_compressors)) {
            $output->writeln("  <error>invalid compressor: {$this->_compressor}</error>");
            $output->writeln("");

            return;
        }

        $output->writeln("  <info>compressor: {$this->_compressor}</info>");
        $output->writeln("  <info>java:       {$this->_java}</info>");
        $output->writeln("");

        try {
            $task = new StylesheetTask($output);
            $task->setRoot($this->_root);
            $task->setManifest("$this->_root/.bundler/stylesheet.php");
            $task->setCompressor($this->_compressor);
            $task->setJava($this->_java);
            $task->bundle();
        }
        catch(Exception $e) {
            $output->writeln("  <error>{$e->getMessage()}</error>");
            $output->writeln("");
        }
    }
}