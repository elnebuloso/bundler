<?php
namespace Bundler\Command;

/**
 * Class AbstractPublicCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class AbstractPublicCommand extends AbstractCommand {

    /**
     * @var string
     */
    protected $compiler;

    /**
     * @var array
     */
    protected $compilers;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $destinationMax;

    /**
     * @var string
     */
    protected $destinationMin;

    /**
     * @return void
     */
    protected function outputBundlingFilesCompression() {
        $org = strlen(file_get_contents($this->destinationMax));
        $new = strlen(file_get_contents($this->destinationMin));
        $ratio = !empty($org) ? $new / $org : 0;

        $this->output->writeln("  <info>org:   {$org} bytes</info>");
        $this->output->writeln("  <info>new:   {$new} bytes</info>");
        $this->output->writeln("  <info>ratio: {$ratio}</info>");
        $this->output->writeln("");

        $this->output->writeln("  <info>created: {$this->destinationMax}</info>");
        $this->output->writeln("  <info>created: {$this->destinationMin}</info>");
        $this->output->writeln("");
    }
}