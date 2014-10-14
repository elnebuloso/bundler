<?php
namespace Bundler\Command;

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
        return 'bundle:stylesheet';
    }

    /**
     * @return string
     */
    public function getCommandDescription() {
        return 'bundling stylesheet';
    }
}