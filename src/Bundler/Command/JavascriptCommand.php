<?php
namespace Bundler\Command;

use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JavascriptCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptCommand extends AbstractPublicCommand {

    /**
     * @return void
     */
    protected function configure() {
        $this->manifest = "javascript.yaml";
        $this->compiler = "google";
        $this->compilers = array(
            "google",
            "yuicompressor"
        );

        parent::configure();

        $this->setName('bundle:javascript');
        $this->setDescription('bundling javascript');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<comment>bundling javascript</comment>");

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

        foreach($this->filesSelected as $this->currentPackage => $this->filesSelectedByPackage) {
            $this->outputBundlingPackage();

            $this->content = array();
            $this->destinationMax = "{$this->target}/{$this->currentPackage}.bundler.js";
            $this->destinationMin = "{$this->target}/{$this->currentPackage}.bundler.min.js";

            foreach($this->filesSelectedByPackage['files'] as $file) {
                $this->output->writeln("  <info>include: {$file}</info>");
                $this->content[] = file_get_contents($file);
            }

            // create max file
            $this->content = implode(PHP_EOL . PHP_EOL, $this->content);
            file_put_contents($this->destinationMax, $this->content);

            switch($this->compiler) {
                case "google":
                    $this->compileWithGoogle();
                    $this->output->writeln("");
                    $this->output->writeln("  <info>compiled by google closure compiler</info>");
                    break;

                case "yuicompressor":
                    $this->compileWithYuiCompressor();
                    $this->output->writeln("");
                    $this->output->writeln("  <info>compiled by yuicompressor</info>");
                    break;
            }

            $this->outputBundlingFilesByPackage();
            $this->outputBundlingFilesCompression();
        }
    }

    /**
     * @link http://dl.google.com/closure-compiler/compiler-latest.zip
     * @return void
     * @throws Exception
     */
    protected function compileWithGoogle() {
        $compiler = $this->thirdParty . '/google/compiler.jar';
        $command = "{$this->java} -jar {$compiler} --compilation_level=SIMPLE_OPTIMIZATIONS --warning_level=QUIET --js={$this->destinationMax} --js_output_file={$this->destinationMin}";
        exec($command);
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function compileWithYuiCompressor() {
        $compiler = $this->thirdParty . '/yuicompressor/2.4.8/yuicompressor.jar';
        $command = "{$this->java} -jar {$compiler} --type js --line-break 5000 --nomunge --preserve-semi --disable-optimizations -o {$this->destinationMin} {$this->destinationMax}";
        exec($command);
    }
}