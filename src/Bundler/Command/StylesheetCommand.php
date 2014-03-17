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
        $this->_manifest = "stylesheet.php";
        $this->_compiler = "yuicompressor";
        $this->_compilers = array(
            "yuicompressor",
            "cssmin"
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
        $output->writeln("<comment>bundling stylesheet</comment>");

        parent::execute($input, $output);

        $this->bundle();
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        parent::bundle();

        if(realpath($this->_target) == false) {
            if(!mkdir($this->_target, 0755, true)) {
                throw new Exception("unable to create target: {$this->_target}");
            }
        }

        foreach($this->_filesSelected as $package => $data) {
            $this->_output->writeln("");
            $this->_output->writeln("<comment>package: {$package}</comment>");

            $this->_content = array();
            $this->_destinationMax = "{$this->_target}/{$package}.bundler.css";
            $this->_destinationMin = "{$this->_target}/{$package}.bundler.min.css";

            foreach($data['files'] as $file) {
                $this->_output->writeln("  <info>include: {$file}</info>");

                $path = pathinfo($file);
                $path = $this->getRelativePath($path['dirname'], $this->_target);

                $css = file_get_contents($file);
                $css = $this->changeUrlPath($path, $css);

                $this->_content[] = $css;
            }

            // create max file
            $this->_content = implode(PHP_EOL . PHP_EOL, $this->_content);
            file_put_contents($this->_destinationMax, $this->_content);

            switch($this->_compiler) {
                case "yuicompressor":
                    $this->compileWithYuiCompressor();
                    $this->_output->writeln("");
                    $this->_output->writeln("  <info>compiled by yuicompressor</info>");
                    break;

                case "cssmin":
                    $this->compileWithCSSMin();
                    $this->_output->writeln("");
                    $this->_output->writeln("  <info>compiled by cssmin</info>");
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
    private function compileWithYuiCompressor() {
        $compiler = $this->_thirdParty . '/yuicompressor/2.4.8/yuicompressor.jar';
        $command = "{$this->_java} -jar {$compiler} --type css --line-break 5000 -o {$this->_destinationMin} {$this->_destinationMax}";
        exec($command);
    }

    /**
     * @return void
     */
    private function compileWithCSSMin() {
        require_once $this->_thirdParty . '/cssmin/3.0.1/source/CssMin.php';

        $cssFilter = array(
            'ImportImports' => false,
            // default false
            'ConvertLevel3Properties' => true
            // default true
        );

        $cssPlugins = array(
            'Variables' => true,
            // default true
            'ConvertFontWeight' => true,
            // default false
            'ConvertNamedColors' => true,
            // default false
            'CompressColorValues' => true,
            // default false
            'CompressUnitValues' => false
            // default false
        );

        $minifier = new CssMinifier($this->_content, $cssFilter, $cssPlugins, false);
        file_put_contents($this->_destinationMin, $minifier->getMinified());
    }
}