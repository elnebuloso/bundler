<?php
namespace Bundler\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FileCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileCommand extends AbstractCommand {

    /**
     * @return void
     */
    protected function configure() {
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);
    }

    /**
     * @return string
     */
    public function getCommandName() {
        return 'bundle:files';
    }

    /**
     * @return string
     */
    public function getCommandDescription() {
        return 'bundling files';
    }

    /**
     * @return void
     */
    public function runBundler() {
    }
}