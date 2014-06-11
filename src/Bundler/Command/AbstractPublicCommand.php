<?php
namespace Bundler\Command;

use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
    protected $java;

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
     * @var string
     */
    protected $thirdParty;

//    /**
//     * @return void
//     */
//    protected function configure() {
//        parent::configure();
//
//        $this->addArgument('java', InputArgument::OPTIONAL, 'java binary call');
//        $this->addArgument('compiler', InputArgument::OPTIONAL, implode(', ', $this->compilers));
//    }

//    /**
//     * @param InputInterface $input
//     * @param OutputInterface $output
//     * @return void
//     * @throws Exception
//     */
//    protected function execute(InputInterface $input, OutputInterface $output) {
//        parent::execute($input, $output);
//
//        $this->java = !is_null($input->getArgument('java')) ? $input->getArgument('java') : 'java';
//        $this->compiler = !is_null($input->getArgument('compiler')) ? $input->getArgument('compiler') : $this->compiler;
//
//        if(!in_array($this->compiler, $this->compilers)) {
//            throw new Exception("invalid compiler: {$this->compiler}");
//        }
//
//        $output->writeln("  <info>java:     {$this->java}</info>");
//        $output->writeln("  <info>compiler: {$this->compiler}</info>");
//    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        parent::bundle();

        $this->thirdParty = realpath(__DIR__ . '/../../../third-party');

        if($this->thirdParty === false) {
            throw new Exception("bundler missing it third party tools at {$this->thirdParty}");
        }

        if(realpath($this->target) == false) {
            if(!mkdir($this->target, 0755, true)) {
                throw new Exception("unable to create target: {$this->target}");
            }
        }
    }

    /**
     * @return void
     */
    protected function outputBundlingFilesCompression() {
        $org = strlen(file_get_contents($this->destinationMax));
        $new = strlen(file_get_contents($this->destinationMin));
        $ratio = !empty($org) ? $new / $org : 0;

        $this->output->writeln("");
        $this->output->writeln("  <info>org:     {$org} bytes</info>");
        $this->output->writeln("  <info>new:     {$new} bytes</info>");
        $this->output->writeln("  <info>ratio:   {$ratio}</info>");

        $this->output->writeln("");
        $this->output->writeln("  <info>created: {$this->destinationMax}</info>");
        $this->output->writeln("  <info>created: {$this->destinationMin}</info>");
    }
}