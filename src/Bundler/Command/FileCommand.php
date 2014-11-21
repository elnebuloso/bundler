<?php
namespace Bundler\Command;

use Bundler\BundlerInterface;
use Bundler\FileBundler;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class FileCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileCommand extends AbstractCommand {

    /**
     * @return string
     */
    protected function getCommandName() {
        return 'files';
    }

    /**
     * @return string
     */
    protected function getCommandDescription() {
        return 'bundling files';
    }

    /**
     * @return BundlerInterface
     */
    protected function getBundler() {
        $fileBundler = new FileBundler($this->getYaml());
        $fileBundler->setVersion($this->input->getOption('folder'));

        return $fileBundler;
    }

    /**
     * @return void
     */
    protected function configure() {
        $this->addOption('folder', 'folder', InputOption::VALUE_REQUIRED, 'name for version directory under target, overrides the version settings in files.yaml', null);

        parent::configure();
    }
}