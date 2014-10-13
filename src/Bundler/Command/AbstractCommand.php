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
    private $root;

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
        $this->input = $input;
        $this->output = $output;

        $this->writeComment('running bundler: ' . $this->getCommandDescription(), true, true);
        $this->runBundler();
    }

    /**
     * @param string $message
     * @param bool $newLineBefore
     * @param bool $newLineAfter
     */
    public function writeComment($message, $newLineBefore = false, $newLineAfter = false) {
        $this->writeNewLine($newLineBefore);
        $this->output->writeln("<comment>" . $message . "</comment>");
        $this->writeNewLine($newLineAfter);
    }

    /**
     * @param string $message
     * @param bool $newLineBefore
     * @param bool $newLineAfter
     */
    public function writeInfo($message, $newLineBefore = false, $newLineAfter = false) {
        $this->writeNewLine($newLineBefore);
        $this->output->writeln("  <info>" . $message . "</info>");
        $this->writeNewLine($newLineAfter);
    }

    /**
     * @param bool $newLine
     */
    public function writeNewLine($newLine = false) {
        if($newLine) {
            $this->output->writeln("");
        }
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