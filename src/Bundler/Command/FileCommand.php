<?php
namespace Bundler\Command;

use Bundler\FileSystem\FileCopy;
use Bundler\Package\FilePackage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class FileCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileCommand extends AbstractCommand {

    /**
     * @var FilePackage;
     */
    protected $currentPackage;

    /**
     * @var string
     */
    protected $copyMethod = 'native';

    /**
     * @return void
     */
    protected function configure() {
        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);
    }

    /**
     * @return string
     */
    public function getCommandName() {
        return 'bundle:files';
    }

    /**
     * @return string
     */
    public function getCommandDescription() {
        return 'bundling files';
    }

    /**
     * @return void
     */
    public function initCommand() {
        $this->copyMethod = (shell_exec('which cp')) ? 'native' : 'php';
    }

    /**
     * @return void
     */
    public function bundleCurrentPackage() {
        $this->cleanupTargetDirectory();
        $this->copyFiles();
    }

    /**
     * @return void
     */
    private function cleanupTargetDirectory() {
        $benchmark = new Benchmark();
        $benchmark->start();

        $targetDirectory = $this->currentPackage->getTargetDirectory();
        $this->writeInfo("cleaning up target directory: {$targetDirectory}");

        $fs = new Filesystem();

        if($fs->exists($targetDirectory)) {
            $fs->remove($targetDirectory);
        }

        $benchmark->stop();
        $this->writeInfo("cleaning up target directory: {$targetDirectory} in {$benchmark->getTime()} seconds");
    }

    /**
     * @return void
     */
    private function copyFiles() {
        $fileCopy = new FileCopy();

        $benchmark = new Benchmark();
        $benchmark->start();

        $this->writeInfo("copying files");

        $i = 1;
        $total = $this->currentPackage->getSelectedFilesCount();

        foreach($this->currentPackage->getSelectedFiles() as $sourceFilePath) {
            $destinationFilePath = $this->currentPackage->getDestinationFilePath($sourceFilePath);
            $fileCopy->copyFile($sourceFilePath, $destinationFilePath);

            $number = str_pad($i, strlen($total), '0', STR_PAD_LEFT);
            $this->writeInfo("{$number} / {$total} {$destinationFilePath}");
            $i++;
        }

        $benchmark->stop();
        $this->writeInfo("copying files {$total} in {$benchmark->getTime()} seconds");
    }
}