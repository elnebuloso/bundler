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

        foreach($this->_filesSelected as $package => $data) {
            $this->_output->writeln("");
            $this->_output->writeln("<comment>package: {$package}</comment>");

            $this->_content = array();
            $this->_destinationMax = "{$this->_target}/{$package}.bundler.css";
            $this->_destinationMin = "{$this->_target}/{$package}.bundler.min.css";

            foreach($data['files'] as $file) {
                $this->_output->writeln("  <info>include: {$file}</info>");

                $path = pathinfo($file);
                $path = $this->_getRelativePath($path['dirname'], $this->_target);

                $css = file_get_contents($file);
                $css = $this->_changeUrlPath($path, $css);

                $this->_content[] = $css;
            }

            // create max file
            $this->_content = implode(PHP_EOL . PHP_EOL, $this->_content);
            file_put_contents($this->_destinationMax, $this->_content);

            // create min file
            if($this->_compressor == 'yuicompressor') {
                $this->_compileWithYuiCompressor();
            }

            if($this->_compressor == 'cssmin') {
                $this->_compileWithCSSMin();
            }

            $this->_output->writeln("  <info>created: {$this->_destinationMax}</info>");
            $this->_output->writeln("  <info>created: {$this->_destinationMin}</info>");

            $org = strlen(file_get_contents($this->_destinationMax));
            $new = strlen(file_get_contents($this->_destinationMin));
            $ratio = !empty($org) ? $new / $org : 0;

            $this->_output->writeln("");
            $this->_output->writeln("  <info>include:           " . count($data['includes']) . "</info>");
            $this->_output->writeln("  <info>exclude:           " . count($data['excludes']) . "</info>");
            $this->_output->writeln("  <info>copy:              " . count($data['files']) . "</info>");
            $this->_output->writeln("  <info>org:               {$org} bytes</info>");
            $this->_output->writeln("  <info>new:               {$new} bytes</info>");
            $this->_output->writeln("  <info>compression ratio: {$ratio}</info>");
        }
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