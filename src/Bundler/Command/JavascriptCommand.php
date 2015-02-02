<?php
namespace Bundler\Command;

use Bundler\BundlerInterface;
use Bundler\JavascriptBundler;

/**
 * Class JavascriptCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptCommand extends AbstractCommand {

    /**
     * @return string
     */
    public function getCommandName() {
        return 'javascript';
    }

    /**
     * @return string
     */
    public function getCommandDescription() {
        return 'bundling javascript';
    }

    /**
     * @return BundlerInterface
     */
    protected function getBundler() {
        return new JavascriptBundler($this->getFile());
    }
}