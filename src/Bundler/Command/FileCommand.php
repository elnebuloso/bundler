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
     * @var string
     */
    protected $_manifest;

    /**
     * @return void
     */
    protected function configure() {
        $this->_manifest = "files.php";

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
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        parent::bundle();

        if(!$this->cleanupTarget($this->_target)) {
            throw new Exception("unable to cleanup target: {$this->_target}");
        }

        if(!mkdir($this->_target, 0755, true)) {
            throw new Exception("unable to create target: {$this->_target}");
        }

        foreach($this->_filesSelected as $package => $data) {
            $this->_output->writeln("");
            $this->_output->writeln("<comment>bundling: {$package}</comment>");

            $countFiles = count($data['files']);
            $countIncludes = count($data['includes']);
            $countExcludes = count($data['excludes']);

            $progress = $this->getHelperSet()->get('progress');
            $progress->start($this->_output, $countFiles);

            foreach($data['files'] as $file) {
                $destination = $this->_target . '/' . $package . '/' . str_replace($this->_folder . '/', '', $file);
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

            $this->_output->writeln("");
            $this->_output->writeln("  <info>copied:  {$countFiles}</info>");
            $this->_output->writeln("  <info>include: {$countIncludes}</info>");
            $this->_output->writeln("  <info>exclude: {$countExcludes}</info>");
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