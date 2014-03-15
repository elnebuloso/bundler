<?php
namespace Bundler\Task;

use Exception;

/**
 * Class StylesheetTask
 *
 * @package Bundler\Task
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetTask extends AbstractPublicTask {

    /**
     * @return void
     * @throws Exception
     */
    public function bundle() {
        parent::bundle();
    }

    /**
     * @param string $path
     * @param string $from
     * @return string
     */
    private function _getRelativePath($path, $from) {
        $path = explode(DIRECTORY_SEPARATOR, $path);
        $from = rtrim($from, '/') . '/';
        $from = explode(DIRECTORY_SEPARATOR, dirname($from . '.'));
        $common = array_intersect_assoc($path, $from);

        $base = array('.');

        if($pre_fill = count(array_diff_assoc($from, $common))) {
            $base = array_fill(0, $pre_fill, '..');
        }

        $path = array_merge($base, array_diff_assoc($path, $common));

        return implode(DIRECTORY_SEPARATOR, $path);
    }
}