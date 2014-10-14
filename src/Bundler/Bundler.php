<?php
namespace Bundler;

/**
 * Class Bundler
 *
 * @package Bundler
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface Bundler {

    /**
     * @var string
     */
    const TYPE_FILES = 'files';

    /**
     * @var string
     */
    const TYPE_JAVASCRIPT = 'javascript';

    /**
     * @var string
     */
    const TYPE_STYLESHEET = 'stylesheet';

    /**
     * @return void
     */
    public function bundle();
}