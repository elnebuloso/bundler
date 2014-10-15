<?php
namespace Bundler\Markup;

/**
 * Class StylesheetMarkup
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetMarkup extends AbstractMarkup {

    /**
     * @param string $packageName
     * @return string
     */
    public function getMarkup($packageName) {
        $markup = array();

        foreach($this->getFiles($packageName) as $file) {
            $markup[] = '<link rel="stylesheet" href="' . $file . '">';
        }

        return implode(PHP_EOL, $markup);
    }
}