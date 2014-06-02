<?php
namespace Bundler;

/**
 * Class StylesheetMarkup
 *
 * @package Bundler
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetMarkup extends AbstractMarkup {

    /**
     * @return StylesheetMarkup
     */
    public function __construct() {
        $this->setYaml('.bundler/stylesheet.yaml');
        $this->setHost('');
        $this->setPublic('public/css');
        $this->setMinified(true);
        $this->setDevelopment(true);
    }

    /**
     * @param array $files
     * @return array
     */
    protected function getMarkup(array $files) {
        $markup = array();

        foreach($files as $file) {
            $markup[] = '<link rel="stylesheet" href="' . $this->getHost() . '/' . $file . '">';
        }

        return $markup;
    }
}