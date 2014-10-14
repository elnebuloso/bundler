<?php
namespace Bundler\Markup;

/**
 * Class StylesheetMarkup
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetMarkup extends AbstractMarkup {

    /**
     * @param string $package
     * @return string
     */
    public function getMarkup($package) {
        $markup = array();

        foreach($this->getFiles($package) as $file) {
            $markup[] = '<link rel="stylesheet" href="' . $file . '">';
        }

        return implode(PHP_EOL, $markup);
    }
}