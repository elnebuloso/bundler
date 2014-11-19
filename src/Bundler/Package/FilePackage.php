<?php
namespace Bundler\Package;

use Bundler\FileSystem\FileCopy;
use Bundler\Tools\Benchmark;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FilePackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FilePackage extends AbstractPackage {

    const VERSION_TYPE_DATETIME = 'datetime';
    const VERSION_TYPE_FILE = 'file';

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $copyMethod;

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
     * @return void
     */
    protected function bundlePackage() {
        $this->copyMethod = (shell_exec('which cp')) ? 'native' : 'php';

        $this->cleanupTargetDirectory();
        $this->bundleFiles();
    }

    /**
     * @param string $sourceFile
     * @return string
     */
    protected function getTargetFile($sourceFile) {
        return $this->getTargetDirectory() . DIRECTORY_SEPARATOR . str_replace($this->getRoot() . DIRECTORY_SEPARATOR, '', $sourceFile);
    }

    /**
     * @return string
     * @throws PackageException
     */
    protected function getTargetDirectory() {
        if(realpath($this->getTarget()) === false) {
            throw new PackageException('wrong target directory: ' . $this->getTarget(), 5002);
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
                break;
        }

        $targetDirectory[] = $this->getName();
        $targetDirectory = implode('/', $targetDirectory);

        return $targetDirectory;
    }

    /**
     * @return void
     */
    protected function cleanupTargetDirectory() {
        $targetDirectory = $this->getTargetDirectory();

        $this->getBundlerLogger()->logDebug("cleanup target directory: {$targetDirectory}");

        $benchmark = new Benchmark();
        $benchmark->start();

        $fileSystem = new Filesystem();

        if($fileSystem->exists($targetDirectory)) {
            $fileSystem->remove($targetDirectory);
        }

        $benchmark->stop();

        $this->getBundlerLogger()->logDebug("cleanup target directory: {$targetDirectory} in {$benchmark->getTime()} seconds");
    }

    /**
     * @return void
     */
    protected function bundleFiles() {
        $this->getBundlerLogger()->logDebug("bundle files");

        $benchmark = new Benchmark();
        $benchmark->start();

        $fileCopy = new FileCopy();

        $i = 1;
        $total = $this->getSelectedFilesCount();

        foreach($this->getSelectedFiles() as $sourceFile) {
            $targetFile = $this->getTargetFile($sourceFile);
            $fileCopy->copyFile($sourceFile, $targetFile);

            $number = str_pad($i, strlen($total), '0', STR_PAD_LEFT);
            $this->getBundlerLogger()->logDebug("{$number} / {$total} {$targetFile}");
            $i++;
        }

        $benchmark->stop();

        $this->getBundlerLogger()->logDebug("bundle files {$total} in {$benchmark->getTime()} seconds");
    }
}