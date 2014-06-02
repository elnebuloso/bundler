<?php
namespace Bundler;

/**
 * Class JavascriptMarkup
 *
 * @package Bundler
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptMarkup extends AbstractMarkup {

    /**
     * @return JavascriptMarkup
     */
    public function __construct() {
        $this->setYaml('.bundler/javascript.yaml');
        $this->setHost('');
        $this->setPublic('public/js');
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
            $markup[] = '<script src="' . $this->getHost() . '/' . $file . '"></script>';
        }

        return $markup;
    }
}