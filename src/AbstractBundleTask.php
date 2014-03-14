<?php
/**
 * Class AbstractBundleTask
 *
 * @package PhingCommons\Bundle
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractBundleTask extends Task {

    /**
     * @var int
     */
    protected $_verbose;



    /**
     * @param $verbose
     */
    public function setVerbose($verbose) {
        $this->_verbose = (int) $verbose;
    }


    /**
     * @throws BuildException
     */
    public function main() {




        $this->log("");
        $this->log("manifest:  {$this->_manifest}");
        $this->log("folder:    {$this->_folder}");

        $this->_readManifest();
    }

    /**
     * @return void
     */
    protected function _readManifest() {
        $this->_manifestDefinition = require_once($this->_manifest);

        foreach($this->_manifestDefinition as $package => $definition) {
            $this->log("");
            $this->log("package: {$package}");

            $includes = array();
            $excludes = array();

            if(array_key_exists('include', $definition) && is_array($definition['include'])) {
                $includes = $definition['include'];
            }

            if(array_key_exists('exclude', $definition) && is_array($definition['exclude'])) {
                $excludes = $definition['exclude'];
            }

            $includeFiles = array();
            $excludeFiles = array();

            foreach($includes as $pattern) {
                $files = $this->_selectFiles($this->_folder, '`' . $pattern . '`');
                $files = $this->_updateFiles($this->_folder, $files);
                $includeFiles = array_merge($includeFiles, $files);
            }

            foreach($excludes as $pattern) {
                $files = $this->_selectFiles($this->_folder, '`' . $pattern . '`');
                $files = $this->_updateFiles($this->_folder, $files);
                $excludeFiles = array_merge($excludeFiles, $files);
            }

            if($this->_verbose) {
                foreach($includeFiles as $file) {
                    $this->log("include: {$file}");
                }
            }

            if($this->_verbose) {
                foreach($excludeFiles as $file) {
                    $this->log("exclude: {$file}");
                }
            }

            $this->_filesSelected[$package] = array(
                'files' => $this->_getFilesToUse($includeFiles, $excludeFiles),
                'includes' => $includeFiles,
                'excludes' => $excludeFiles
            );
        }
    }

    /**
     * @param string $folder
     * @param string $pattern
     * @return array
     */
    protected function _selectFiles($folder, $pattern) {
        $dir = new RecursiveDirectoryIterator($folder);
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
        $fileList = array();

        foreach($files as $file) {
            $fileList = array_merge($fileList, $file);
        }

        return $fileList;
    }

    /**
     * @param string $folder
     * @param array $fileList
     * @return array
     */
    protected function _updateFiles($folder, array $fileList) {
        $returnFiles = array();

        foreach($fileList as $currentFile) {
            $currentFile = realpath($folder . '/' . $currentFile);

            if(empty($currentFile) || is_dir($currentFile)) {
                continue;
            }

            $returnFiles[md5($currentFile)] = $currentFile;
        }

        return $returnFiles;
    }

    /**
     * @param array $includeFiles
     * @param array $excludeFiles
     * @return array
     */
    protected function _getFilesToUse(array $includeFiles, array $excludeFiles) {
        return array_diff($includeFiles, $excludeFiles);
    }
}