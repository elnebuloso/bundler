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
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var string
     */
    private $root;

    /**
     * @param InputInterface $input
     */
    public function setInput($input) {
        $this->input = $input;
    }

    /**
     * @return InputInterface
     */
    public function getInput() {
        return $this->input;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput($output) {
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput() {
        return $this->output;
    }

    /**
     * @param string $root
     */
    public function setRoot($root) {
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getRoot() {
        return $this->root;
    }

    /**
     * @return void
     */
    protected function configure() {
        $this->setName($this->getCommandName());
        $this->setDescription($this->getCommandName());
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->setInput($input);
        $this->setOutput($output);

        $this->runBundler();
    }

    /**
     * @param string $message
     */
    public function writeComment($message) {
        $this->getOutput()->writeln("<comment>" . $message . "</comment>");
    }

    /**
     * @param string $message
     */
    public function writeInfo($message) {
        $this->getOutput()->writeln("  <info>" . $message . "</info>");
    }

    /**
     * @return string
     */
    abstract public function getCommandName();

    /**
     * @return string
     */
    abstract public function getCommandDescription();

    /**
     * @return void
     */
    abstract public function runBundler();
}