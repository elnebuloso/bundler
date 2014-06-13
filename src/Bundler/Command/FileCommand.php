<?php
namespace Bundler\Command;

use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FileCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileCommand extends AbstractCommand {

    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $outputDirectory;

    /**
     * @return void
     */
    protected function configure() {
        parent::configure();

        $this->setName('bundle:files');
        $this->setDescription('bundling files');

        $this->manifest = "files.yaml";
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws Exception
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->output->writeln("<comment>bundling files</comment>");
        $this->output->writeln("");

        $this->outputDirectory = $this->target;

        if(array_key_exists('directory', $this->manifestDefinition)) {
            switch($this->manifestDefinition['directory']) {
                case '$DATETIME':
                    $this->outputDirectory = $this->target . '/' . date('YmdHis');
                    break;

                case '$VERSION':
                    if(($version = file_get_contents($this->root . '/VERSION'))) {
                        $version = trim($version);

                        if(!empty($version)) {
                            $this->outputDirectory = $this->target . '/' . $version;
                        }
                    }
                    break;
            }
        }

        $this->output->writeln("  <info>output directory: {$this->outputDirectory}</info>");
        $this->output->writeln("");

        if(file_exists($this->outputDirectory)) {
            if(!$this->removeDirectory($this->outputDirectory)) {
                throw new Exception("unable to delete output directory: {$this->outputDirectory}");
            }
        }
    }
}