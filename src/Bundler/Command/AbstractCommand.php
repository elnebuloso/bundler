<?php
namespace Bundler\Command;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AbstractCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class AbstractCommand extends Command {

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var string
     */
    protected $root;

    /**
     * @var string
     */
    protected $manifest;

    /**
     * @var array
     */
    protected $manifestDefinition;

    /**
     * @var string
     */
    protected $folder;

    /**
     * @var string
     */
    protected $target;

    /**
     * @var array
     */
    protected $filesSelected;

    /**
     * @var array
     */
    protected $filesSelectedByPackage;

    /**
     * @var string
     */
    protected $currentPackage;

    /**
     * @param string $root
     */
    public function setRoot($root) {
        $this->root = $root;
    }

    /**
     * @return void
     */
    protected function configure() {
        $this->addArgument('manifest', InputArgument::OPTIONAL, 'path to the manifest file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->manifest = !is_null($input->getArgument('manifest')) ? $input->getArgument('manifest') : "$this->root/.bundler/{$this->manifest}";

        if(realpath($this->root) === false) {
            throw new Exception("root folder: {$this->root} not found.");
        }

        if(realpath($this->manifest) === false) {
            throw new Exception("manifest file: {$this->manifest} not found.");
        }

        $this->output = $output;
        $this->root = realpath($this->root);
        $this->manifest = realpath($this->manifest);
        $this->manifestDefinition = Yaml::parse($this->manifest);

        $folder = "{$this->root}/{$this->manifestDefinition['folder']}";
        $target = "{$this->root}/{$this->manifestDefinition['target']}";

        if(!file_exists($folder)) {
            throw new Exception("folder: {$folder} not found.");
        }

        if(!file_exists($target)) {
            if(!mkdir($target, 0755, true)) {
                throw new Exception("unable to create target: {$target}");
            }
        }

        $this->folder = realpath($folder);
        $this->target = realpath($target);

        $this->output->writeln("<comment>configuration</comment>");
        $this->output->writeln("  <info>manifest: {$this->manifest}</info>");
        $this->output->writeln("  <info>root:     {$this->root}</info>");
        $this->output->writeln("  <info>folder:   {$this->folder}</info>");
        $this->output->writeln("  <info>target:   {$this->target}</info>");
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        foreach($this->manifestDefinition['bundle'] as $package => $definition) {
            $this->output->writeln("");
            $this->output->writeln("<comment>selecting: {$package}</comment>");

            $includes = array();
            $excludes = array();

            if(array_key_exists('include', $definition) && is_array($definition['include'])) {
                $includes = $definition['include'];
            }

            if(array_key_exists('exclude', $definition) && is_array($definition['exclude'])) {
                $excludes = $definition['exclude'];
            }

            $includeFiles = array();
            $excludeFiles = array();

            foreach($includes as $pattern) {
                $this->output->writeln("");
                $this->output->writeln("  <info>folder:   {$this->folder}</info>");
                $this->output->writeln("  <info>include:  {$pattern}</info>");

                $files = $this->selectFiles($this->folder, '`' . $pattern . '`');
                $files = $this->updateFiles($files);
                $includeFiles = array_merge($includeFiles, $files);
            }

            foreach($excludes as $pattern) {
                $this->output->writeln("");
                $this->output->writeln("  <info>folder:   {$this->folder}</info>");
                $this->output->writeln("  <info>exclude:  {$pattern}</info>");

                $files = $this->selectFiles($this->folder, '`' . $pattern . '`');
                $files = $this->updateFiles($files);
                $excludeFiles = array_merge($excludeFiles, $files);
            }

            if($this->output->getVerbosity() > 1) {
                $this->output->writeln("");

                foreach($includeFiles as $file) {
                    $this->output->writeln("  <info>include: {$file}</info>");
                }

                foreach($excludeFiles as $file) {
                    $this->output->writeln("  <info>exclude: {$file}</info>");
                }
            }

            $this->filesSelected[$package] = array(
                'files' => $this->getFilesToBundle($includeFiles, $excludeFiles),
                'includes' => $includeFiles,
                'excludes' => $excludeFiles
            );
        }
    }

    /**
     * @param string $folder
     * @param string $pattern
     * @return array
     */
    private function selectFiles($folder, $pattern) {
        $dir = new RecursiveDirectoryIterator($folder);
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
        $fileList = array();

        foreach($files as $file) {
            $fileList = array_merge($fileList, $file);
        }

        return $fileList;
    }

    /**
     * @param array $fileList
     * @return array
     */
    private function updateFiles(array $fileList) {
        $returnFiles = array();

        foreach($fileList as $currentFile) {
            $currentFile = realpath($currentFile);

            if(empty($currentFile) || is_dir($currentFile)) {
                continue;
            }

            $returnFiles[md5($currentFile)] = $currentFile;
        }

        return $returnFiles;
    }

    /**
     * @param array $includeFiles
     * @param array $excludeFiles
     * @return array
     */
    private function getFilesToBundle(array $includeFiles, array $excludeFiles) {
        return array_diff($includeFiles, $excludeFiles);
    }

    /**
     * @return void
     */
    protected function outputBundlingPackage() {
        $this->output->writeln("");
        $this->output->writeln("<comment>bundling: {$this->currentPackage}</comment>");
    }

    /**
     * @return void
     */
    protected function outputBundlingFilesByPackage() {
        $countFiles = count($this->filesSelectedByPackage['files']);
        $countIncludes = count($this->filesSelectedByPackage['includes']);
        $countExcludes = count($this->filesSelectedByPackage['excludes']);

        $this->output->writeln("");
        $this->output->writeln("  <info>bundled: {$countFiles}</info>");
        $this->output->writeln("  <info>include: {$countIncludes}</info>");
        $this->output->writeln("  <info>exclude: {$countExcludes}</info>");
    }
}