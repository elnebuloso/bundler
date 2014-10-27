<?php
namespace Bundler\Markup;

use Bundler\BundlerInterface;
use Bundler\JavascriptBundler;

/**
 * Class JavascriptMarkup
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptMarkup extends AbstractMarkup {

    /**
     * @return BundlerInterface
     */
    protected function getBundler() {
        return new JavascriptBundler($this->getBundlerDirectory() . '/javascript.yaml');
    }

    /**
     * @return string
     */
    protected function getCacheFilename() {
        return $this->getBundlerDirectory() . '/javascript.php';
    }

    /**
     * @param string $packageName
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