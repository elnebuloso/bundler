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
        $this->_manifest = "javascript.php";
        $this->_compiler = "google";
        $this->_compilers = array(
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
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        parent::bundle();

        foreach($this->_filesSelected as $package => $data) {
            $this->_output->writeln("");
            $this->_output->writeln("<comment>package: {$package}</comment>");

            $this->_content = array();
            $this->_destinationMax = "{$this->_target}/{$package}.bundler.js";
            $this->_destinationMin = "{$this->_target}/{$package}.bundler.min.js";

            foreach($data['files'] as $file) {
                $this->_output->writeln("  <info>include: {$file}</info>");
                $this->_content[] = file_get_contents($file);
            }

            // create max file
            $this->_content = implode(PHP_EOL . PHP_EOL, $this->_content);
            file_put_contents($this->_destinationMax, $this->_content);

            switch($this->_compiler) {
                case "google":
                    $this->compileWithGoogle();
                    $this->_output->writeln("");
                    $this->_output->writeln("  <info>compiled by google closure compiler</info>");
                    break;

                case "yuicompressor":
                    $this->compileWithYuiCompressor();
                    $this->_output->writeln("");
                    $this->_output->writeln("  <info>compiled by yuicompressor</info>");
                    break;
            }

            $org = strlen(file_get_contents($this->_destinationMax));
            $new = strlen(file_get_contents($this->_destinationMin));
            $ratio = !empty($org) ? $new / $org : 0;

            $this->_output->writeln("");
            $this->_output->writeln("  <info>bundled: " . count($data['files']) . "</info>");
            $this->_output->writeln("  <info>include: " . count($data['includes']) . "</info>");
            $this->_output->writeln("  <info>exclude: " . count($data['excludes']) . "</info>");
            $this->_output->writeln("  <info>org:     {$org} bytes</info>");
            $this->_output->writeln("  <info>new:     {$new} bytes</info>");
            $this->_output->writeln("  <info>ratio:   {$ratio}</info>");

            $this->_output->writeln("");
            $this->_output->writeln("  <info>created: {$this->_destinationMax}</info>");
            $this->_output->writeln("  <info>created: {$this->_destinationMin}</info>");
        }

        $this->_output->writeln("");
    }

    /**
     * @link http://dl.google.com/closure-compiler/compiler-latest.zip
     * @return void
     * @throws Exception
     */
    private function compileWithGoogle() {
        $compiler = $this->_thirdParty . '/google/compiler.jar';
        $command = "{$this->_java} -jar {$compiler} --compilation_level=SIMPLE_OPTIMIZATIONS --warning_level=QUIET --js={$this->_destinationMax} --js_output_file={$this->_destinationMin}";
        exec($command);
    }

    /**
     * @return void
     * @throws Exception
     */
    private function compileWithYuiCompressor() {
        $compiler = $this->_thirdParty . '/yuicompressor/2.4.8/yuicompressor.jar';
        $command = "{$this->_java} -jar {$compiler} --type js --line-break 5000 --nomunge --preserve-semi --disable-optimizations -o {$this->_destinationMin} {$this->_destinationMax}";
        exec($command);
    }
}