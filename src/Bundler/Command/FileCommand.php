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
    protected $_manifest;

    /**
     * @return void
     */
    protected function configure() {
        $this->_manifest = "files.php";

        parent::configure();

        $this->setName('bundle:files');
        $this->setDescription('bundling files');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<comment>bundling files</comment>");

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
        }
    }
}