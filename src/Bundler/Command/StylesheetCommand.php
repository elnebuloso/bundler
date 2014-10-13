<?php
namespace Bundler\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StylesheetCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetCommand extends AbstractPublicCommand {

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
        return 'bundle:stylesheet';
    }

    /**
     * @return string
     */
    public function getCommandDescription() {
        return 'bundling stylesheet';
    }

    /**
     * @return void
     */
    public function initCommand() {
        // intentionally left blank
    }

    /**
     * @return void
     */
    public function bundleCurrentPackage() {
        // TODO: Implement bundlePackage() method.
    }
}