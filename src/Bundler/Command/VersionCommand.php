<?php
namespace Bundler\Command;

use Bundler\Version;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class VersionCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class VersionCommand extends Command {

    /**
     * @return void
     */
    protected function configure() {
        $this->setName('version');
        $this->setDescription('show the bundler version');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $version = Version::getVersion();

        $output->writeln("<comment>bundler</comment>");
        $output->writeln("  <info>version: {$version}</info>");
        $output->writeln("");
    }
}