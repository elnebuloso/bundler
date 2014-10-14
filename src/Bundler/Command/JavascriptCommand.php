<?php
namespace Bundler\Command;

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
        return 'bundle:javascript';
    }

    /**
     * @return string
     */
    public function getCommandDescription() {
        return 'bundling javascript';
    }
}