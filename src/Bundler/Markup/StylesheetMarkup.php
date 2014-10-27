<?php
namespace Bundler\Markup;

use Bundler\BundlerInterface;
use Bundler\StylesheetBundler;

/**
 * Class StylesheetMarkup
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetMarkup extends AbstractMarkup {

    /**
     * @return BundlerInterface
     */
    protected function getBundler() {
        return new StylesheetBundler($this->getBundlerDirectory() . '/stylesheet.yaml');
    }

    /**
     * @return string
     */
    protected function getCacheFilename() {
        return $this->getBundlerDirectory() . '/stylesheet.php';
    }

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