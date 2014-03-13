<?php
namespace Bundler;

/**
 * Class Version
 *
 * @package Bundler
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
final class Version {

    /**
     * @return string
     */
    public static function getVersion() {
        return trim(file_get_contents(dirname(dirname(dirname(__FILE__))) . '/VERSION'));
    }
}