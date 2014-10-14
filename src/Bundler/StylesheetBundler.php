<?php
namespace Bundler;

/**
 * Class StylesheetBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetBundler extends AbstractBundler implements Bundler {

    /**
     * @var string
     */
    protected $title = 'bundling stylesheet ...';

    /**
     * @var string
     */
    protected $type = self::TYPE_STYLESHEET;
}