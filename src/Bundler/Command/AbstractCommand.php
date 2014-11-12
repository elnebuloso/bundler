<?php
namespace Bundler\Command;

use Bundler\BundlerInterface;
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
     * @var string
     */
    private $yaml;

    /**
     * @var string
     */
    private $root;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param string $yaml
     */
    public function setYaml($yaml) {
        $this->yaml = $yaml;
    }

    /**
     * @return string
     */
    public function getYaml() {
        return $this->yaml;
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
        $this->input = $input;
        $this->output = $output;

        $bundler = $this->getBundler();
        $bundler->getBundlerLogger()->setConsoleOutput($output);
        $bundler->bundle();
    }

    /**
     * @return string
     */
    abstract protected function getCommandName();

    /**
     * @return string
     */
    abstract protected function getCommandDescription();

    /**
     * @return BundlerInterface
     */
    abstract protected function getBundler();
}