<?php
namespace Bundler\Command;

use Exception;
use Flex\FileSelector\FileSelector;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FileCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileCommand extends AbstractCommand {

    /**
     * @var string
     */
    private $outputDirectory;

    /**
     * @return void
     */
    protected function configure() {
        $this->manifest = "files.yaml";

        parent::configure();

        $this->setName('bundle:files');
        $this->setDescription('bundling files');

        $this->addOption('directory', 'd', InputOption::VALUE_OPTIONAL, 'string containing directory under bundling folder', 'src');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->output->writeln("<comment>bundling files</comment>");
        $this->output->writeln("");

        $this->outputDirectory = $this->target;

        if(array_key_exists('directory', $this->manifestDefinition)) {
            switch($this->manifestDefinition['directory']) {
                case '$DATETIME':
                    $this->outputDirectory = $this->target . '/' . date('YmdHis');
                    break;

                case '$VERSION':
                    if(($version = file_get_contents($this->root . '/VERSION'))) {
                        $version = trim($version);

                        if(!empty($version)) {
                            $this->outputDirectory = $this->target . '/' . $version;
                        }
                    }
                    break;

                case '$OPTION':
                    $this->outputDirectory = $this->target . '/' . $input->getOption('directory');
                    break;
            }
        }

        $this->output->writeln("  <info>output directory: {$this->outputDirectory}</info>");
        $this->output->writeln("");

        if(file_exists($this->outputDirectory)) {
            if(!$this->removeDirectory($this->outputDirectory)) {
                throw new Exception("unable to delete output directory: {$this->outputDirectory}");
            }
        }

        if(!mkdir($this->outputDirectory, 0777, true)) {
            throw new Exception("unable to create output directory: {$this->outputDirectory}");
        }

        if(array_key_exists('bundle', $this->manifestDefinition)) {
            $this->selectFilesByPackages();

            foreach($this->fileSelectors as $this->currentPackage => $this->fileSelector) {
                $this->output->writeln("<comment>bundling files by package: {$this->currentPackage}</comment>");
                $this->output->writeln("");

                $this->bundle($this->fileSelector, $this->currentPackage);
            }
        }

        if(!array_key_exists('bundle', $this->manifestDefinition)) {
            $this->selectFiles();

            $this->output->writeln("<comment>bundling files</comment>");
            $this->output->writeln("");

            $this->bundle($this->fileSelector);
        }
    }

    /**
     * @param FileSelector $fileSelector
     * @param string $package
     * @throws Exception
     */
    private function bundle(FileSelector $fileSelector, $package = null) {
        $progress = $this->getHelperSet()->get('progress');
        $progress->start($this->output, $this->fileSelector->getFilesCount());

        foreach($this->fileSelector->getFiles() as $file) {
            $destination = array();
            $destination[] = $this->outputDirectory;

            if(!empty($package)) {
                $destination[] = $package;
            }

            $destination[] = str_replace($this->folder . '/', '', $file);
            $destination = implode('/', $destination);

            $directory = dirname($destination);

            if(!file_exists($directory)) {
                if(!mkdir($directory, 0777, true)) {
                    throw new Exception("unable to create directory for file copy: {$directory}");
                }
            }

            if(!copy($file, $destination)) {
                throw new Exception("unable to copy file to destination: {$destination}");
            }

            $progress->advance();
        }

        $progress->finish();
        $this->output->writeln("");

        $this->outputFileSelector();
    }
}