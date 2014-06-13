<?php
namespace Flex\FileSelector;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Class FileSelector
 *
 * @package Flex\FileSelector
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileSelector {

    /**
     * @var string
     */
    private $folder;

    /**
     * @var array
     */
    private $includes;

    /**
     * @var array
     */
    private $excludes;

    /**
     * @var array
     */
    private $files;

    /**
     * @var array
     */
    private $includedFiles;

    /**
     * @var array
     */
    private $excludedFiles;

    /**
     * @param string $folder
     */
    public function setFolder($folder) {
        $this->folder = $folder;
    }

    /**
     * @return string
     */
    public function getFolder() {
        return $this->folder;
    }

    /**
     * @param array $includes
     */
    public function setIncludes(array $includes) {
        $this->includes = $includes;
    }

    /**
     * @return array
     */
    public function getIncludes() {
        return $this->includes;
    }

    /**
     * @param string $include
     */
    public function addInclude($include) {
        $this->includes[] = $include;
    }

    /**
     * @param array $excludes
     */
    public function setExcludes(array $excludes) {
        $this->excludes = $excludes;
    }

    /**
     * @return array
     */
    public function getExcludes() {
        return $this->excludes;
    }

    /**
     * @param string $exclude
     */
    public function addExclude($exclude) {
        $this->excludes[] = $exclude;
    }

    /**
     * @return array
     */
    public function getFiles() {
        return $this->files;
    }

    /**
     * @return int
     */
    public function getFilesCount() {
        return count($this->getFiles());
    }

    /**
     * @return array
     */
    public function getIncludedFiles() {
        return $this->includedFiles;
    }

    /**
     * @return int
     */
    public function getIncludedFilesCount() {
        return count($this->getIncludedFiles());
    }

    /**
     * @return array
     */
    public function getExcludedFiles() {
        return $this->excludedFiles;
    }

    /**
     * @return int
     */
    public function getExcludedFilesCount() {
        return count($this->getExcludedFiles());
    }

    /**
     * @throws Exception
     */
    public function select() {
        if(empty($this->folder)) {
            throw new Exception('missing folder for selecting files');
        }

        if(realpath($this->folder) === false) {
            throw new Exception("folder: {$this->folder} not found.");
        }

        if(empty($this->includes)) {
            throw new Exception('missing pattern(s) for files to include');
        }

        $this->includedFiles = array();
        $this->excludedFiles = array();

        foreach($this->includes as $pattern) {
            $files = $this->selectFiles($this->folder, '`' . $pattern . '`');
            $files = $this->updateFiles($files);
            $this->includedFiles = array_merge($this->includedFiles, $files);
        }

        foreach($this->excludes as $pattern) {
            $files = $this->selectFiles($this->folder, '`' . $pattern . '`');
            $files = $this->updateFiles($files);
            $this->excludedFiles = array_merge($this->excludedFiles, $files);
        }

        $this->files = $this->selectFilesToUse($this->includedFiles, $this->excludedFiles);
    }

    /**
     * @param string $folder
     * @param string $pattern
     * @return array
     */
    private function selectFiles($folder, $pattern) {
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
     * @param array $fileList
     * @return array
     */
    private function updateFiles(array $fileList) {
        $returnFiles = array();

        foreach($fileList as $currentFile) {
            $currentFile = realpath($currentFile);

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
    private function selectFilesToUse(array $includeFiles, array $excludeFiles) {
        return array_diff($includeFiles, $excludeFiles);
    }
}