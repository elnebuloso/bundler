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
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->output->writeln("<comment>bundling files</comment>");
        $this->output->writeln("");

        try {
            $this->selectFiles();
            $this->selectFilesByPackages();
        }
        catch(Exception $e) {
        }

        var_dump(is_null($this->fileSelector));
        var_dump(is_null($this->fileSelectors));
    }
}