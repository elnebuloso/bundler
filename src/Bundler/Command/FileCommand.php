<?php
namespace Bundler\Command;

use Bundler\BundlerInterface;
use Bundler\FileBundler;

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
        return 'bundle:files';
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
        return new FileBundler($this->getYaml());
    }
}