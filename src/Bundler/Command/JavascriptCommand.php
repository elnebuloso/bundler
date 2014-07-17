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
        parent::configure();

        $this->setName('bundle:javascript');
        $this->setDescription('bundling javascript');

        $this->manifest = "javascript.yaml";
        $this->compiler = "google-closure-compiler";
        $this->compilers = array(
            "google-closure-compiler",
            "yuicompressor"
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->output->writeln("<comment>bundling javascript</comment>");
        $this->output->writeln("");

        $this->selectFilesByPackages();
        $this->bundle();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function bundle() {
        foreach($this->fileSelectors as $this->currentPackage => $this->fileSelector) {
            $this->output->writeln("<comment>bundling files by package: {$this->currentPackage}</comment>");
            $this->output->writeln("");

            if(!empty($this->manifestDefinition['bundle'][$this->currentPackage]['compiler'])) {
                $compiler = $this->manifestDefinition['bundle'][$this->currentPackage]['compiler'];
                $this->compiler = in_array($compiler, $this->compilers) ? $compiler : $this->compiler;
            }

            $this->content = array();
            $this->destinationMax = "{$this->target}/{$this->currentPackage}.bundler.js";
            $this->destinationMin = "{$this->target}/{$this->currentPackage}.bundler.min.js";

            $this->output->writeln("  <info>compiling</info>");
            $this->output->writeln("");

            $progress = $this->getHelperSet()->get('progress');
            $progress->start($this->output, $this->fileSelector->getFilesCount());

            foreach($this->fileSelector->getFiles() as $file) {
                $this->content[] = file_get_contents($file);

                $progress->advance();
            }

            $progress->finish();
            $this->output->writeln("");

            // create max file
            $this->content = preg_replace('/\s+/', '', $this->content);
            $this->content = implode(PHP_EOL . PHP_EOL, $this->content);
            file_put_contents($this->destinationMax, $this->content);

            switch($this->compiler) {
                case "google-closure-compiler":
                    $this->compileWithGoogleClosureCompiler();
                    $this->output->writeln("  <info>compiled by google closure compiler</info>");
                    $this->output->writeln("");
                    break;

                case "yuicompressor":
                    $this->compileWithYuiCompressor();
                    $this->output->writeln("  <info>compiled by yuicompressor</info>");
                    $this->output->writeln("");
                    break;
            }

            $this->outputFileSelector();
            $this->outputBundlingFilesCompression();

            // create php loader file
            $pathMax = basename($this->target) . "/{$this->currentPackage}.bundler.js";
            $pathMin = basename($this->target) . "/{$this->currentPackage}.bundler.min.js";

            $paths = array(
                'max' => "{$pathMax}?v=" . md5_file($this->destinationMax),
                'min' => "{$pathMin}?v=" . md5_file($this->destinationMin),
            );

            file_put_contents("{$this->target}/{$this->currentPackage}.bundler.php", "<?php return " . var_export($paths, true) . ";");
        }
    }

    /**
     * @link http://dl.google.com/closure-compiler/compiler-latest.zip
     * @throws Exception
     * @return void
     */
    protected function compileWithGoogleClosureCompiler() {
        $command = $this->thirdParty . "/../bin/google-closure-compiler --compilation_level=SIMPLE_OPTIMIZATIONS --warning_level=QUIET --js={$this->destinationMax} --js_output_file={$this->destinationMin}";
        exec($command);
    }

    /**
     * @throws Exception
     * @return void
     */
    protected function compileWithYuiCompressor() {
        $command = $this->thirdParty . "/../bin/yuicompressor --type js --line-break 5000 --nomunge --preserve-semi --disable-optimizations -o {$this->destinationMin} {$this->destinationMax}";
        exec($command);
    }
}