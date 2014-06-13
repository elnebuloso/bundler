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
        parent::configure();

        $this->setName('bundle:stylesheet');
        $this->setDescription('bundling stylesheet');

        $this->manifest = "stylesheet.yaml";
        $this->compiler = "yuicompressor";
        $this->compilers = array(
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

        $this->output->writeln("<comment>bundling stylesheet</comment>");
        $this->output->writeln("");

        $this->selectFilesByPackages();
        $this->bundle();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function bundle() {
        foreach($this->fileSelectors as $this->currentPackage => $this->fileSelector) {
            if(!empty($this->manifestDefinition['bundle'][$this->currentPackage]['compiler'])) {
                $compiler = $this->manifestDefinition['bundle'][$this->currentPackage]['compiler'];
                $this->compiler = in_array($compiler, $this->compilers) ? $compiler : $this->compiler;
            }

            $this->outputFileSelector();
        }
    }
}