<?php
namespace Bundler\FileSystem;

use Exception;

/**
 * Class FileSelector
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileSelector {

    /**
     * @var string
     */
    private $dir;

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
     * @var array
     */
    private $excludedFoldersFromScanning = array(
        '.git',
        '.svn',
        '.idea'
    );

    /**
     * @param string $dir
     */
    public function setDir($dir) {
        $this->dir = $dir;
    }

    /**
     * @return string
     */
    public function getDir() {
        return $this->dir;
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
     * @param array $excludedFoldersFromScanning
     */
    public function setExcludedFoldersFromScanning(array $excludedFoldersFromScanning) {
        $this->excludedFoldersFromScanning = $excludedFoldersFromScanning;
    }

    /**
     * @return array
     */
    public function getExcludedFoldersFromScanning() {
        return $this->excludedFoldersFromScanning;
    }

    /**
     * @throws Exception
     */
    public function select() {
        if(empty($this->dir)) {
            throw new Exception('missing folder for selecting files');
        }

        if(realpath($this->dir) === false) {
            throw new Exception("folder: {$this->dir} not found.");
        }

        if(empty($this->includes)) {
            throw new Exception('missing pattern(s) for files to include');
        }

        $this->includedFiles = array();
        $this->excludedFiles = array();

        foreach($this->includes as $pattern) {
            $files = $this->selectFiles($this->dir, $pattern);
            $this->includedFiles = array_merge($this->includedFiles, $files);
        }

        if(!empty($this->excludes)) {
            foreach($this->excludes as $pattern) {
                foreach($this->includedFiles as $key => $file) {
                    if(preg_match('`' . $pattern . '`', $file)) {
                        $this->excludedFiles[$key] = $file;
                    }
                }
            }
        }

        $this->files = $this->selectFilesToUse($this->includedFiles, $this->excludedFiles);
    }

    /**
     * @param string $dir
     * @param string $pattern
     * @return array
     */
    private function selectFiles($dir, $pattern) {
        $dir = $this->optimizeFolder($dir, $pattern);
        $files = $this->scanFolder($dir);
        $fileList = array();

        foreach($files as $file) {
            if(preg_match('`' . $pattern . '`', $file)) {
                $fileList[md5($file)] = $file;
            }
        }

        return $fileList;
    }

    /**
     * @param string $dir
     * @param string $pattern
     * @return string
     */
    private function optimizeFolder($dir, $pattern) {
        $subFolders = explode('/', $pattern);

        foreach($subFolders as $subFolder) {
            if(preg_match('`^[a-zA-Z._0-9]`', $subFolder)) {
                $optimizedFolder = $dir . DIRECTORY_SEPARATOR . $subFolder;

                if(realpath($optimizedFolder) === false) {
                    break;
                }

                $dir = $optimizedFolder;
            }
        }

        return $dir;
    }

    /**
     * @param string $dir
     * @return array
     */
    private function scanFolder($dir) {
        $files = array();

        $this->excludedFoldersFromScanning = array_merge($this->excludedFoldersFromScanning, array(
            '.',
            '..'
        ));

        foreach(scandir($dir) as $file) {
            if(in_array($file, $this->excludedFoldersFromScanning)) {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $file;

            if(is_dir($path)) {
                $files = array_merge($files, $this->scanFolder($path));
            }
            else {
                $files[] = $path;
            }
        }

        return $files;
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