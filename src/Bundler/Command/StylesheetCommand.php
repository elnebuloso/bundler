<?php
namespace Bundler\Command;

use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StylesheetCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetCommand extends AbstractPublicCommand {

    /**
     * @return void
     */
    protected function configure() {
        $this->manifest = "stylesheet.yaml";
        $this->compiler = "yuicompressor";
        $this->compilers = array(
            "yuicompressor"
        );

        parent::configure();

        $this->setName('bundle:stylesheet');
        $this->setDescription('bundling stylesheet');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        parent::execute($input, $output);

        $this->output->writeln("<comment>bundling stylesheet</comment>");
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
            $this->destinationMax = "{$this->target}/{$this->currentPackage}.bundler.max.css";
            $this->destinationMin = "{$this->target}/{$this->currentPackage}.bundler.min.css";

            $this->output->writeln("  <info>compiling</info>");
            $this->output->writeln("");

            $progress = $this->getHelperSet()->get('progress');
            $progress->start($this->output, $this->fileSelector->getFilesCount());

            foreach($this->fileSelector->getFiles() as $file) {
                $path = pathinfo($file);
                $path = $this->getRelativePath($path['dirname'], $this->target);

                $css = file_get_contents($file);
                $css = $this->changeUrlPath($path, $css);

                $this->content[] = $css;

                $progress->advance();
            }

            $progress->finish();
            $this->output->writeln("");

            // create max file
            $this->content = implode(null, $this->content);
            file_put_contents($this->destinationMax, $this->content);

            switch($this->compiler) {
                case "yuicompressor":
                    $this->compileWithYuiCompressor();
                    $this->output->writeln("  <info>compiled by yuicompressor</info>");
                    $this->output->writeln("");
                    break;
            }

            $this->outputFileSelector();
            $this->outputBundlingFilesCompression();
        }
    }

    /**
     * @param string $path
     * @param string $from
     * @return string
     */
    private function getRelativePath($path, $from) {
        $path = explode(DIRECTORY_SEPARATOR, $path);
        $from = rtrim($from, '/') . '/';
        $from = explode(DIRECTORY_SEPARATOR, dirname($from . '.'));
        $common = array_intersect_assoc($path, $from);

        $base = array('.');

        if($pre_fill = count(array_diff_assoc($from, $common))) {
            $base = array_fill(0, $pre_fill, '..');
        }

        $path = array_merge($base, array_diff_assoc($path, $common));

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * @param $baseUrl
     * @param $content
     * @return string
     */
    private function changeUrlPath($baseUrl, $content) {
        return preg_replace('/url\(\s*[\'"]?\/?(.+?)[\'"]?\s*\)/i', 'url(' . $baseUrl . '/$1)', $content);
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function compileWithYuiCompressor() {
        $command = $this->thirdParty . "/../bin/yuicompressor --type css --line-break 5000 -o {$this->destinationMin} {$this->destinationMax}";
        exec($command);
    }
}