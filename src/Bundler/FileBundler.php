<?php
namespace Bundler;

/**
 * Class FileBundler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileBundler extends AbstractBundler implements Bundler {

    /**
     * @var string
     */
    protected $title = 'bundling files ...';

    /**
     * @var string
     */
    protected $type = self::TYPE_FILES;
}