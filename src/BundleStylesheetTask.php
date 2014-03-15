<?php
require_once 'AbstractBundlePublicTask.php';

/**
 * Class BundleStylesheetTask
 *
 * @package PhingCommons\Bundle
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class BundleStylesheetTask extends AbstractBundlePublicTask {

    /**
     * @throws BuildException
     */
    public function main() {
        parent::main();

        foreach($this->_filesSelected as $package => $data) {
            if($this->_verbose) {
                $this->log("");
                $this->log("package: {$package}");

                $this->_content = array();
                $this->_destinationMax = "{$this->_target}/{$package}.bundle.css";
                $this->_destinationMin = "{$this->_target}/{$package}.bundle.min.css";

                foreach($data['files'] as $file) {
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
                if($this->_compiler == 'yuicompressor') {
                    $this->_compileWithYuiCompressor();
                }

                if($this->_compiler == 'cssmin') {
                    $this->_compileWithCSSMin();
                }

                $this->log("created: {$this->_destinationMax}");
                $this->log("created: {$this->_destinationMin}");

                $org = strlen(file_get_contents($this->_destinationMax));
                $new = strlen(file_get_contents($this->_destinationMin));
                $ratio = !empty($org) ? $new / $org : 0;

                $this->log("");
                $this->log("org: {$org} bytes");
                $this->log("new: {$new} bytes");
                $this->log("compression ratio: {$ratio}");
            }

            $this->log("");
            $this->log("package: {$package}");
            $this->log("include: " . count($data['includes']));
            $this->log("exclude: " . count($data['excludes']));
            $this->log("bundled: " . count($data['files']));
        }
    }




}