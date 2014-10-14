<?php
namespace Bundler\Package;

/**
 * Class StylesheetPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetPackage extends AbstractPublicPackage {

    /**
     * @return string
     */
    protected function getFilenameMaxFile() {
        return $this->getName() . '.max.css';
    }

    /**
     * @return string
     */
    protected function getFilenameMinFile() {
        return $this->getName() . '.min.css';
    }

    /**
     * @return void
     */
    protected function compress() {
    }
}