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
     * @param boolean $minified
     * @return Markup
     */
    public function setMinified($minified);

    /**
     * @param boolean $development
     * @return Markup
     */
    public function setDevelopment($development);

    /**
     * @param string $package
     * @return string
     */
    public function getMarkup($package);
}