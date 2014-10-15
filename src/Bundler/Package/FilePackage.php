<?php
namespace Bundler\Package;

use Bundler\Benchmark;
use Bundler\FileSystem\FileCopy;
use Exception;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FilePackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FilePackage extends AbstractPackage {

    /**
     * @var string
     */
    const VERSION_TYPE_DATETIME = 'datetime';

    /**
     * @var string
     */
    const VERSION_TYPE_FILE = 'file';

    /**
     * @var string
     */
    protected $copyMethod = 'native';

    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     */
    public function setVersion($version) {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getTargetDirectory() {
        if(realpath($this->getTarget()) === false) {
            throw new Exception('wrong target directory: ' . $this->getTarget());
        }

        $targetDirectory[] = rtrim(realpath($this->getTarget()), '/');

        switch($this->getVersion()) {
            case self::VERSION_TYPE_DATETIME:
                $targetDirectory[] = date('YmdHis');
                break;

            case self::VERSION_TYPE_FILE:
                if(($version = file_get_contents($this->getRoot() . '/VERSION'))) {
                    $targetDirectory[] = trim($version);
                }
        }

        $targetDirectory[] = $this->getName();
        $targetDirectory = implode('/', $targetDirectory);

        return $targetDirectory;
    }

    /**
     * @param string $sourceFilePath
     * @return string
     */
    public function getTargetFile($sourceFilePath) {
        return $this->getTargetDirectory() . '/' . str_replace($this->getRoot() . '/', '', $sourceFilePath);
    }

    /**
     * @return void
     */
    protected function bundlePackage() {
        $this->copyMethod = (shell_exec('which cp')) ? 'native' : 'php';

        $this->cleanupTargetDirectory();
        $this->copyFiles();
    }

    /**
     * @return void
     */
    protected function cleanupTargetDirectory() {
        $targetDirectory = $this->getTargetDirectory();
        $this->logDebug("cleaning up target directory: {$targetDirectory}");

        $benchmark = new Benchmark();
        $benchmark->start();

        $fs = new Filesystem();

        if($fs->exists($targetDirectory)) {
            $fs->remove($targetDirectory);
        }

        $benchmark->stop();
        $this->logDebug("cleaning up target directory: {$targetDirectory} in {$benchmark->getTime()} seconds");
    }

    /**
     * @return void
     */
    protected function copyFiles() {
        $fileCopy = new FileCopy();

        $benchmark = new Benchmark();
        $benchmark->start();

        $this->logDebug("copying files");

        $i = 1;
        $total = $this->getSelectedFilesCount();

        foreach($this->getSelectedFiles() as $sourceFile) {
            $targetFile = $this->getTargetFile($sourceFile);
            $fileCopy->copyFile($sourceFile, $targetFile);

            $number = str_pad($i, strlen($total), '0', STR_PAD_LEFT);
            $this->logDebug("{$number} / {$total} {$targetFile}");
            $i++;
        }

        $benchmark->stop();
        $this->logDebug("copying files {$total} in {$benchmark->getTime()} seconds");
    }
}