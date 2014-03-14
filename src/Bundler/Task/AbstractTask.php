<?php
namespace Bundler\Task;

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
    private $_output;

    /**
     * @param OutputInterface $output
     * @return AbstractTask
     */
    public function __construct(OutputInterface $output) {
        $this->setOutput($output);
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output) {
        $this->_output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput() {
        return $this->_output;
    }
}