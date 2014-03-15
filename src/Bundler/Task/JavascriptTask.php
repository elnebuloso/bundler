<?php
namespace Bundler\Task;

use Exception;

/**
 * Class JavascriptTask
 *
 * @package Bundler\Task
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptTask extends AbstractPublicTask {

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
            $this->_destinationMax = "{$this->_target}/{$package}.bundler.js";
            $this->_destinationMin = "{$this->_target}/{$package}.bundler.min.js";

            foreach($data['files'] as $file) {
                $this->_content[] = file_get_contents($file);
            }

            // create max file
            $this->_content = implode(PHP_EOL . PHP_EOL, $this->_content);
            file_put_contents($this->_destinationMax, $this->_content);

            // create min file
            if($this->_compressor == 'yuicompressor') {
                $this->_compileWithYuiCompressor();
            }

            if($this->_compressor == 'google') {
                $this->_compileWithGoogle();
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
     * @return void
     * @throws Exception
     */
    private function _compileWithYuiCompressor() {
        exec('java -version 2>&1', $output, $return);

        if(empty($output) || strpos($output[0], "java version") === false) {
            throw new Exception("Missing Java Binary on Path: {$this->_java}");
        }

        $compiler = $this->_thirdParty . '/yuicompressor/2.4.8/yuicompressor.jar';
        $command = "{$this->_java} -jar {$compiler} --type js --line-break 5000 --nomunge --preserve-semi --disable-optimizations -o {$this->_destinationMin} {$this->_destinationMax}";
        exec($command);

        $this->_output->writeln("");
        $this->_output->writeln("  <info>compiled by yuicompressor</info>");
    }

    /**
     * @link http://dl.google.com/closure-compiler/compiler-latest.zip
     * @return void
     * @throws Exception
     */
    private function _compileWithGoogle() {
        exec('java -version 2>&1', $output, $return);

        if(empty($output) || strpos($output[0], "java version") === false) {
            throw new Exception("Missing Java Binary on Path: {$this->_java}");
        }

        $compiler = $this->_thirdParty . '/google/compiler.jar';
        $command = "{$this->_java} -jar {$compiler} --compilation_level=SIMPLE_OPTIMIZATIONS --warning_level=QUIET --js={$this->_destinationMax} --js_output_file={$this->_destinationMin}";
        exec($command);

        $this->_output->writeln("");
        $this->_output->writeln("  <info>compiled by google closure compiler</info>");
    }
}