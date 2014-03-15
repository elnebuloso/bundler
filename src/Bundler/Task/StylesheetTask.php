<?php
namespace Bundler\Task;

use Exception;

/**
 * Class StylesheetTask
 *
 * @package Bundler\Task
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetTask extends AbstractPublicTask {

    /**
     * @return void
     * @throws Exception
     */
    public function bundle() {
        parent::bundle();
    }

    /**
     * @param string $path
     * @param string $from
     * @return string
     */
    private function _getRelativePath($path, $from) {
        $path = explode(DIRECTORY_SEPARATOR, $path);
        $from = rtrim($from, '/') . '/';
        $from = explode(DIRECTORY_SEPARATOR, dirname($from . '.'));
        $common = array_intersect_assoc($path, $from);

        $base = array('.');

        if($pre_fill = count(array_diff_assoc($from, $common))) {
            $base = array_fill(0, $pre_fill, '..');
        }

        $path = array_merge($base, array_diff_assoc($path, $common));

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * @return void
     * @throws Exception
     */
    private function _compileWithYuiCompressor() {
        exec('java -version 2>&1', $output, $return);

        if(empty($output) || strpos($output[0], "java version") === false) {
            throw new Exception("Missing Java Binary on Path: {$this->_java}");
        }

        $compiler = $this->_thirdParty . '/yuicompressor/2.4.8/yuicompressor.jar';
        $command = "{$this->_java} -jar {$compiler} --type css --line-break 5000 -o {$this->_destinationMin} {$this->_destinationMax}";
        exec($command);

        $this->_output->writeln("");
        $this->_output->writeln("  <info>compiled by yuicompressor</info>");
        $this->_output->writeln("");
    }

    /**
     * @return void
     */
    private function _compileWithCSSMin() {
        require_once $this->_thirdParty . '/cssmin/3.0.1/source/CssMin.php';

        $cssFilter = array(
            'ImportImports' => false,
            // default false
            'ConvertLevel3Properties' => true
            // default true
        );

        $cssPlugins = array(
            'Variables' => true,
            // default true
            'ConvertFontWeight' => true,
            // default false
            'ConvertNamedColors' => true,
            // default false
            'CompressColorValues' => true,
            // default false
            'CompressUnitValues' => false
            // default false
        );

        $minifier = new CssMinifier($this->_content, $cssFilter, $cssPlugins, false);
        file_put_contents($this->_destinationMin, $minifier->getMinified());

        $this->_output->writeln("");
        $this->_output->writeln("  <info>compiled by cssmin</info>");
        $this->_output->writeln("");
    }

    /**
     * @param $baseUrl
     * @param $content
     * @return string
     */
    private function _changeUrlPath($baseUrl, $content) {
        return preg_replace('/url\(\s*[\'"]?\/?(.+?)[\'"]?\s*\)/i', 'url(' . $baseUrl . '/$1)', $content);
    }
}