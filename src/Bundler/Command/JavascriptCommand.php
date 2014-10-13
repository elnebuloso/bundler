<?php
namespace Bundler\Command;

use Bundler\Package\JavascriptPackage;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JavascriptCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptCommand extends AbstractPublicCommand {

    /**
     * @var JavascriptPackage;
     */
    protected $currentPackage;

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
        return 'bundle:javascript';
    }

    /**
     * @return string
     */
    public function getCommandDescription() {
        return 'bundling javascript';
    }

    /**
     * @return void
     */
    public function preCommand() {
        $this->cache = array();
        $this->cacheFilename = dirname($this->yaml) . '/javascript.php';
    }

    /**
     * @return void
     */
    public function compress() {
        $this->writeInfo("compressing files");

        $benchmark = new Benchmark();
        $benchmark->start();

        foreach($this->currentPackage->getSelectedFiles() as $sourceFilePath) {
            $this->content[] = file_get_contents($sourceFilePath);
            $this->writeInfo('- ' . $sourceFilePath);
        }

        $this->createFiles();

        $benchmark->stop();
        $this->writeInfo("compressing files in {$benchmark->getTime()} seconds");
    }
}