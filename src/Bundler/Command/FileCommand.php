<?php
namespace Bundler\Command;

/**
 * Class FileCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileCommand extends AbstractCommand {

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
}