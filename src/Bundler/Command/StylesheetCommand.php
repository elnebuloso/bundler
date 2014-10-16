<?php
namespace Bundler\Command;

use Bundler\BundlerInterface;
use Bundler\StylesheetBundler;

/**
 * Class StylesheetCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetCommand extends AbstractCommand {

    /**
     * @return string
     */
    public function getCommandName() {
        return 'stylesheet';
    }

    /**
     * @return string
     */
    public function getCommandDescription() {
        return 'bundling stylesheet';
    }

    /**
     * @return BundlerInterface
     */
    protected function getBundler() {
        return new StylesheetBundler($this->getYaml());
    }
}