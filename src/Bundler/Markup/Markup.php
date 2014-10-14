<?php
namespace Bundler\Markup;

/**
 * Class Markup
 *
 * @package Bundler\Markup
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface Markup {

    /**
     * @param string $package
     * @return string
     */
    public function getMarkup($package);
}