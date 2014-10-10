<?php
namespace Bundler\Command;

use Bundler\FileBundler;
use Bundler\FileSelector;
use Bundler\Model\Package\FilePackage;
use Exception;
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
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var FileBundler
     */
    private $fileBundler;

    /**
     * @var FilePackage
     */
    private $package;

    /**
     * @var FileSelector
     */
    private $fileSelector;

    /**
     * @var string
     */
    private $copyMethod;

    /**
     * @return void
     */
    protected function configure() {
        $this->setName('bundle:files');
        $this->setDescription('bundling files');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->writeComment("bundling files", true, true);

        $timeStart = microtime(true);

        $this->readConfiguration();
        $this->bundlePackages();

        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;

        $this->writeComment("bundling files in {$time} seconds", true, true);
    }

    /**
     * @throws Exception
     */
    private function readConfiguration() {
        $this->writeInfo('read configuration');

        $yaml = $this->dir . '/.bundler/files.yaml';

        if(!realpath($yaml)) {
            throw new Exception("missing configuration yaml file: {$yaml}");
        }

        $this->fileBundler = FileBundler::createFromYaml($this->dir, $yaml);
        $this->copyMethod = (shell_exec('which cp')) ? 'native' : 'php';
    }

    /**
     * @return void
     */
    private function bundlePackages() {
        $this->writeInfo('bundle packages');

        foreach($this->fileBundler->getPackages() as $this->package) {
            $timeStart = microtime(true);

            $this->writeComment("bundling package: {$this->package->getName()}", true, true);
            $this->selectFiles();
            $this->copyFiles();

            $timeEnd = microtime(true);
            $time = $timeEnd - $timeStart;

            $this->writeComment("bundling package: {$this->package->getName()} in {$time} seconds", true);
        }
    }

    /**
     * @return void
     */
    private function selectFiles() {
        $this->writeInfo("selecting files");

        $timeStart = microtime(true);

        $this->fileSelector = new FileSelector();
        $this->fileSelector->setDir($this->dir);
        $this->fileSelector->setIncludes($this->package->getIncludes());
        $this->fileSelector->setExcludes($this->package->getExcludes());
        $this->fileSelector->select();

        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;

        $this->writeInfo("selecting {$this->fileSelector->getFilesCount()} files in {$time} seconds");
    }

    /**
     * @return void
     */
    private function copyFiles() {
        $this->writeInfo("copying files {$this->copyMethod}", true, false);

        $timeStart = microtime(true);

        $packageDir[] = $this->package->getTo();
        switch($this->package->getVersion()) {
            case 'datetime':
                $packageDir[] = date('YmdHis');
                break;

            case 'file':
                if(($version = file_get_contents($this->dir . '/VERSION'))) {
                    $packageDir[] = trim($version);
                }
                break;
        }

        $packageDir[] = $this->package->getName();
        $packageDir = implode('/', $packageDir);

        $this->writeInfo("removing package directory {$packageDir}", false, true);

        $fs = new Filesystem();

        if($fs->exists($packageDir)) {
            $fs->remove($packageDir);
        }

        foreach($this->fileSelector->getFiles() as $file) {
            $source = $file;
            $destination = $packageDir . '/' . str_replace($this->dir . '/', '', $source);

            $this->copy($source, $destination);

            $this->writeInfo($destination);
        }

        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;

        $this->writeInfo("copying {$this->fileSelector->getFilesCount()} files in {$time} seconds", true);
    }

    /**
     * @param string $source
     * @param string $destination
     */
    private function copy($source, $destination) {
        @mkdir(dirname($destination), 0777, true);

        if($this->copyMethod == 'native') {
            shell_exec("cp -r $source $destination");
        }

        if($this->copyMethod == 'php') {
            $sourceHandle = fopen($source, 'r');
            $destinationHandle = fopen($destination, 'w+');
            stream_copy_to_stream($sourceHandle, $destinationHandle);
            fclose($sourceHandle);
            fclose($destinationHandle);
        }
    }
}