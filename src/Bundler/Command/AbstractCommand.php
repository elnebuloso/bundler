<?php
namespace Bundler\Command;

use Bundler\Bundler;
use Bundler\FileBundler;
use Bundler\JavascriptBundler;
use Bundler\StylesheetBundler;
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
    protected $root;

    /**
     * @var string
     */
    protected $yaml;

    /**
     * @var Bundler
     */
    protected $bundler;

    /**
     * @param string $root
     */
    public function setRoot($root) {
        $this->root = $root;
    }

    /**
     * @param string $yaml
     */
    public function setYaml($yaml) {
        $this->yaml = $yaml;
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

        if($this instanceof FileCommand) {
            $this->bundler = new FileBundler($this->yaml);
        }

        if($this instanceof JavascriptCommand) {
            $this->bundler = new JavascriptBundler($this->yaml);
        }

        if($this instanceof StylesheetCommand) {
            $this->bundler = new StylesheetBundler($this->yaml);
        }

        $this->bundler->setOutput($output);
        $this->bundler->configure();
        $this->bundler->bundle();
    }

    /**
     * @return string
     */
    abstract public function getCommandName();

    /**
     * @return string
     */
    abstract public function getCommandDescription();
}