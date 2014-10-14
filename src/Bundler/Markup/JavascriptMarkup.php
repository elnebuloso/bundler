<?php
namespace Bundler\Markup;

/**
 * Class JavascriptMarkup
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptMarkup extends AbstractMarkup {

    /**
     * @param string $package
     * @return string
     */
    public function getMarkup($package) {
        $markup = array();

        foreach($this->getFiles($package) as $file) {
            $markup[] = '<script src="' . $file . '"></script>';
        }

        return implode(PHP_EOL, $markup);
    }
}