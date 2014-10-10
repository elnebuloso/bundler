<?php
namespace Bundler\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractCommand extends Command {

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var string
     */
    protected $dir;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param string $dir
     */
    public function setDir($dir) {
        $this->dir = $dir;
    }

    /**
     * @param string $message
     * @param bool $before
     * @param bool $after
     */
    public function writeComment($message, $before = false, $after = false) {
        if($before) {
            $this->output->writeln("");
        }

        $this->output->writeln("<comment>" . $message . "</comment>");

        if($after) {
            $this->output->writeln("");
        }
    }

    /**
     * @param string $message
     * @param bool $before
     * @param bool $after
     */
    public function writeInfo($message, $before = false, $after = false) {
        if($before) {
            $this->output->writeln("");
        }

        $this->output->writeln("  <info>" . $message . "</info>");

        if($after) {
            $this->output->writeln("");
        }
    }
}