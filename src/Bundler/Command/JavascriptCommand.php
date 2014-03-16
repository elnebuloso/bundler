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
        $this->_manifest = "javascript.php";
        $this->_compiler = "google";
        $this->_compilers = array(
            "google",
            "yuicompressor"
        );

        parent::configure();

        $this->setName('bundle:javascript');
        $this->setDescription('bundling javascript');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<comment>bundling javascript</comment>");

        parent::execute($input, $output);
    }
}