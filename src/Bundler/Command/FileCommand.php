<?php
namespace Bundler\Command;

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
    }

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
}