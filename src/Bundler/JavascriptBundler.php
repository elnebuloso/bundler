<?php
namespace Bundler;

/**
 * Class JavascriptBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptBundler extends AbstractBundler implements Bundler {

    /**
     * @var string
     */
    protected $title = 'bundling javascript ...';

    /**
     * @var string
     */
    protected $type = self::TYPE_JAVASCRIPT;
}