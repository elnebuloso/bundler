<?php
namespace Bundler\Command;

use Bundler\Task\BuildTask;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BuildCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class BuildCommand extends Command {

    /**
     * @var string
     */
    private $_root;

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
        $this->setName('bundle:build')->setDescription('bundling builds');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<comment>bundling builds</comment>");

        try {
            $task = new BuildTask($output);
            $task->setRoot($this->_root);
            $task->setManifest("$this->_root/.bundler/build.php");
            $task->bundle();
        }
        catch(Exception $e) {
            $output->writeln("  <error>{$e->getMessage()}</error>");
            $output->writeln("");
        }
    }
}