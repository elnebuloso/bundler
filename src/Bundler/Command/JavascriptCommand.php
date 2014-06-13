<?php
namespace Bundler\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JavascriptCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptCommand extends AbstractPublicCommand {

    /**
     * @return void
     */
    protected function configure() {
        parent::configure();

        $this->setName('bundle:javascript');
        $this->setDescription('bundling javascript');

        $this->manifest = "javascript.yaml";
        $this->compiler = "google-closure-compiler";
        $this->compilers = array(
            "google-closure-compiler",
            "yuicompressor"
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->output->writeln("");
        $this->output->writeln("<comment>bundling javascript</comment>");
        $this->output->writeln("");
    }
}