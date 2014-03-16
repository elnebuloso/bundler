<?php
namespace Bundler\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StylesheetCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetCommand extends AbstractPublicCommand {

    /**
     * @return void
     */
    protected function configure() {
        $this->_manifest = "stylesheet.php";
        $this->_compiler = "yuicompressor";
        $this->_compilers = array(
            "yuicompressor",
            "cssmin"
        );

        parent::configure();

        $this->setName('bundle:stylesheet');
        $this->setDescription('bundling stylesheet');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<comment>bundling stylesheet</comment>");

        parent::execute($input, $output);

        $this->bundle();
    }
}