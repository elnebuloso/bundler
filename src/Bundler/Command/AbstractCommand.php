<?php
namespace Bundler\Command;

use Exception;
use Flex\FileSelector\FileSelector;
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
     * @var FileSelector
     */
    protected $fileSelector;

    /**
     * @var array
     */
    protected $fileSelectors;

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
        $this->addArgument('manifest', InputArgument::OPTIONAL, 'name of the manifest yaml');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws Exception
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->manifest = !is_null($input->getArgument('manifest')) ? "$this->root/.bundler/{$input->getArgument('manifest')}" : "$this->root/.bundler/{$this->manifest}";

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

        if(!array_key_exists('folder', $this->manifestDefinition)) {
            throw new Exception("missing folder definition in: {$this->manifest}");
        }

        if(!array_key_exists('target', $this->manifestDefinition)) {
            throw new Exception("missing target definition in: {$this->manifest}");
        }

        $folder = "{$this->root}/{$this->manifestDefinition['folder']}";
        $target = "{$this->root}/{$this->manifestDefinition['target']}";

        if(realpath($folder) === false) {
            throw new Exception("folder: {$folder} not found.");
        }

        if(!file_exists($target)) {
            if(!mkdir($target, 0777, true)) {
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
        $this->output->writeln("");
    }

    /**
     * @return void
     */
    protected function selectFiles() {
        $this->output->writeln("<comment>selecting files</comment>");
        $this->output->writeln("");

        $includes = array();
        $excludes = array();

        if(array_key_exists('include', $this->manifestDefinition) && is_array($this->manifestDefinition['include'])) {
            $includes = $this->manifestDefinition['include'];
        }

        if(array_key_exists('exclude', $this->manifestDefinition) && is_array($this->manifestDefinition['exclude'])) {
            $excludes = $this->manifestDefinition['exclude'];
        }

        foreach($includes as $pattern) {
            $this->output->writeln("  <info>include:  {$pattern}</info>");
        }

        foreach($excludes as $pattern) {
            $this->output->writeln("  <info>exclude:  {$pattern}</info>");
        }

        $this->output->writeln("");

        $fileSelector = new FileSelector();
        $fileSelector->setFolder($this->folder);
        $fileSelector->setIncludes($includes);
        $fileSelector->setExcludes($excludes);
        $fileSelector->select();

        $this->fileSelector = $fileSelector;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function selectFilesByPackages() {
        if(!array_key_exists('bundle', $this->manifestDefinition)) {
            throw new Exception("missing bundle definition in: {$this->manifest}");
        }

        if(empty($this->manifestDefinition['bundle'])) {
            throw new Exception("missing bundle package definitions in: {$this->manifest}");
        }

        foreach($this->manifestDefinition['bundle'] as $package => $definition) {
            $this->output->writeln("<comment>selecting files by package: {$package}</comment>");
            $this->output->writeln("");

            $includes = array();
            $excludes = array();

            if(array_key_exists('include', $definition) && is_array($definition['include'])) {
                $includes = $definition['include'];
            }

            if(array_key_exists('exclude', $definition) && is_array($definition['exclude'])) {
                $excludes = $definition['exclude'];
            }

            foreach($includes as $pattern) {
                $this->output->writeln("  <info>include:  {$pattern}</info>");
            }

            foreach($excludes as $pattern) {
                $this->output->writeln("  <info>exclude:  {$pattern}</info>");
            }

            $this->output->writeln("");

            $fileSelector = new FileSelector();
            $fileSelector->setFolder($this->folder);
            $fileSelector->setIncludes($includes);
            $fileSelector->setExcludes($excludes);
            $fileSelector->select();

            $this->fileSelectors[] = $fileSelector;
        }
    }
}