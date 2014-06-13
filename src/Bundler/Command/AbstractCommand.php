<?php
namespace Bundler\Command;

use Exception;
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
    }
}