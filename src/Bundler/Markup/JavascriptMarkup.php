<?php
namespace Bundler\Markup;

/**
 * Class JavascriptMarkup
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptMarkup extends AbstractMarkup {

    /**
     * @param $packageName
     * @return string
     */
    public function getMarkup($packageName) {
        $markup = array();

        foreach($this->getFiles($packageName) as $file) {
            $markup[] = '<script src="' . $file . '"></script>';
        }

        return implode(PHP_EOL, $markup);
    }
}