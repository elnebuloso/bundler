<?php
namespace Bundler\Command;

use Exception;
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

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        parent::bundle();

        foreach($this->_filesSelected as $package => $data) {
            $this->_output->writeln("");
            $this->_output->writeln("<comment>bundling: {$package}</comment>");

            $countFiles = count($data['files']);
            $countIncludes = count($data['includes']);
            $countExcludes = count($data['excludes']);

            $this->_content = array();
            $this->_destinationMax = "{$this->_target}/{$package}.bundler.css";
            $this->_destinationMin = "{$this->_target}/{$package}.bundler.min.css";
        }

        $this->_output->writeln("");
    }
}