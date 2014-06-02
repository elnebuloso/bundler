<?php
namespace Bundler\Command;

use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FileCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileCommand extends AbstractCommand {

    /**
     * @return void
     */
    protected function configure() {
        $this->manifest = "files.yaml";

        parent::configure();

        $this->setName('bundle:files');
        $this->setDescription('bundling files');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<comment>bundling files</comment>");

        parent::execute($input, $output);

        $this->bundle();
        $this->output->writeln("");
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        parent::bundle();

        if(realpath($this->target) !== false) {
            if(!$this->cleanupTarget($this->target)) {
                throw new Exception("unable to cleanup target: {$this->target}");
            }
        }

        if(!mkdir($this->target, 0755, true)) {
            throw new Exception("unable to create target: {$this->target}");
        }

        foreach($this->filesSelected as $this->currentPackage => $this->filesSelectedByPackage) {
            $this->outputBundlingPackage();

            $progress = $this->getHelperSet()->get('progress');
            $progress->start($this->output, count($this->filesSelectedByPackage['files']));

            foreach($this->filesSelectedByPackage['files'] as $file) {
                $destination = $this->target . '/' . $this->currentPackage . '/' . str_replace($this->folder . '/', '', $file);
                $directory = dirname($destination);

                if(!file_exists($directory)) {
                    if(!mkdir($directory, 0755, true)) {
                        throw new Exception("unable to create {$directory}");
                    }
                }

                if(!copy($file, $destination)) {
                    throw new Exception("unable to create {$destination}");
                }

                $progress->advance();
            }

            $progress->finish();

            $this->outputBundlingFilesByPackage();
        }
    }

    /**
     * @param $directory
     * @return bool
     */
    protected function cleanupTarget($directory) {
        $files = array_diff(scandir($directory), array(
            '.',
            '..'
        ));

        foreach($files as $file) {
            (is_dir("$directory/$file")) ? $this->cleanupTarget("$directory/$file") : unlink("$directory/$file");
        }

        return rmdir($directory);
    }
}