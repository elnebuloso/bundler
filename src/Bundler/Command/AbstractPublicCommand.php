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
    protected $_java;

    /**
     * @var string
     */
    protected $_compiler;

    /**
     * @var array
     */
    protected $_compilers;

    /**
     * @var string
     */
    protected $_content;

    /**
     * @var string
     */
    protected $_destinationMax;

    /**
     * @var string
     */
    protected $_destinationMin;

    /**
     * @var string
     */
    protected $_thirdParty;

    /**
     * @return void
     */
    protected function configure() {
        parent::configure();

        $this->addArgument('java', InputArgument::OPTIONAL, 'java binary call');
        $this->addArgument('compiler', InputArgument::OPTIONAL, implode(', ', $this->_compilers));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->_java = !is_null($input->getArgument('java')) ? $input->getArgument('java') : 'java';
        $this->_compiler = !is_null($input->getArgument('compiler')) ? $input->getArgument('compiler') : $this->_compiler;

        exec('java -version 2>&1', $exec, $return);

        if(empty($exec) || strpos($exec[0], "java version") === false) {
            throw new Exception("missing java binary on path: {$this->_java}");
        }

        if(!in_array($this->_compiler, $this->_compilers)) {
            throw new Exception("invalid compiler: {$this->_compiler}");
        }

        $output->writeln("  <info>java:     {$this->_java}</info>");
        $output->writeln("  <info>compiler: {$this->_compiler}</info>");
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        parent::bundle();

        $this->_thirdParty = realpath(__DIR__ . '/../../../third-party');

        if($this->_thirdParty === false) {
            throw new Exception("bundler missing it third party tools at {$this->_thirdParty}");
        }
    }
}